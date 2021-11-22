<?php

namespace HDSSolutions\Laravel\Http\Controllers;

use App\Http\Controllers\Controller;
use Closure;
use HDSSolutions\Laravel\Contracts\PaymentContract;
use HDSSolutions\Laravel\Http\Request;
use HDSSolutions\Laravel\Interfaces\Document;
use HDSSolutions\Laravel\Models\Card;
use HDSSolutions\Laravel\Models\CashBook;
use HDSSolutions\Laravel\Models\CashLine;
use HDSSolutions\Laravel\Models\Check;
use HDSSolutions\Laravel\Models\Credit;
use HDSSolutions\Laravel\Models\CreditNote;
use HDSSolutions\Laravel\Models\Customer;
use HDSSolutions\Laravel\Models\Employee;
use HDSSolutions\Laravel\Models\Invoice;
use HDSSolutions\Laravel\Models\Order;
use HDSSolutions\Laravel\Models\OrderLine;
use HDSSolutions\Laravel\Models\Payment;
use HDSSolutions\Laravel\Models\Product;
use HDSSolutions\Laravel\Models\PromissoryNote;
use HDSSolutions\Laravel\Models\Receipment;
use HDSSolutions\Laravel\Models\ReceipmentInvoice;
use HDSSolutions\Laravel\Models\ReceipmentPayment;
use HDSSolutions\Laravel\Models\Variant;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller {

    public function index(Request $request) {
return redirect()->route('backend.payment.create');
        // load branches from current company
        $branches = backend()->company()->branches()->with([ 'warehouses.locators' ])->get();
        // load cashBooks
        $cashBooks = CashBook::all();

        // return configure POS view
        return view('pos::payment.index', compact('branches', 'cashBooks'));
    }

    public function session(Request $request) {
        // save data to POS
        pos_settings()->configure(
            currency:   $request->currency_id,
            branch:     $request->branch_id,
            warehouse:  $request->warehouse_id,
            cashBook:   $request->cash_book_id,
        );

        // redirect to POS.create
        return redirect()->route('backend.payment.create');
    }

    public function create(Request $request) {
        // load employees
        $employees = Employee::all();
        // load customers
        $customers = Customer::with([
            // 'addresses', // TODO: Customer.addresses
            // load available CreditNotes of Customer
            'creditNotes'   => fn($creditNote) => $creditNote->available()->with([ 'identity' ]),
            // load pending invoices of customer
            'invoices'      => fn($invoice) => $invoice->completed()->paid(false),
        ])->get();

        $highs = [
            'document_number'   => Receipment::nextDocumentNumber(),
        ];

        // show main form
        return view('pos::payment.create', compact('employees', 'customers', 'highs'));
    }

    public function store(Request $request) {
        // start a transaction
        DB::beginTransaction();
        // create resource
        $resource = new Receipment( $request->input() );
        $resource->partnerable()->associate( Customer::findOrFail($request->partnerable_id) );

        // save resource
        if (!$resource->save())
            // redirect with errors
            return back()->withInput()
                ->withErrors( $resource->errors() );

        // sync receipment invoices
        if (($redirect = $this->saveInvoices($resource, $request->get('invoices'))) !== true)
            // return redirection
            return $redirect;

        // sync receipment payments
        if (($redirect = $this->savePayments($resource, $request->get('payments'))) !== true)
            // return redirection
            return $redirect;

        // complete receipment
        if (!$resource->processIt( Document::ACTION_Complete ))
            // return with error
            return back()->withInput()
                ->withErrors( $resource->getDocumentError() );

        // commit changes to database
        DB::commit();

        // go to receipment details
        return redirect()->route('backend.receipments.show', $resource);
    }

    private function saveInvoices(Receipment $resource, array $invoices) {
        // foreach new/updated invoices
        foreach (($invoices = array_group( $invoices )) as $invoice) {
            // ignore line if invoice wasn't specified
            if (!isset($invoice['invoice_id']) || is_null($invoice['imputed_amount'])) continue;

            // create ReceipmentInvoice for current Invoice
            $receipmentInvoice = ReceipmentInvoice::make([
                'receipment_id'     => $resource->id,
                'invoice_id'        => $invoice['invoice_id'],
                'imputed_amount'    => $invoice['imputed_amount'],
            ]);

            // save receipment line
            if (!$receipmentInvoice->save())
                return back()->withInput()
                    ->withErrors( $receipmentInvoice->errors() );
        }

        // return success
        return true;
    }

    private function savePayments(Receipment $resource, array $payments) {
        // foreach new/updated payments
        foreach (($payments = array_group( $payments )) as $payment) {
            // ignore line if invoice wasn't specified
            if (!isset($payment['payment_type']) || is_null($payment['payment_amount'])) continue;

            // check payment type
            switch ($payment['payment_type']) {
                case Payment::PAYMENT_TYPE_Cash:
                    // get open cash
                    if (($cash = pos_settings()->cashBook()->cashes()->open()->first()) === null)
                        // return with error
                        return back()->withInput()
                            ->withErrors(__('pos::payment.payment.no-open-cash', [
                                'cashBook'  => pos_settings()->cashBook()->name,
                            ]));

                    $paymentResource = $cash->lines()->make([
                        'transacted_at'     => $resource->transacted_at,
                        'currency_id'       => pos_settings()->currency()->id,
                        'cash_type'         => CashLine::CASH_TYPE_Receipment,
                        'description'       => __('pos::payment.payment.cash-line.description', [
                            'receipment'    => $resource->document_number,
                        ]),
                        'amount'            => $payment['payment_amount'],
                    ]);
                    $paymentResource->referable()->associate( $resource );
                    break;

                case Payment::PAYMENT_TYPE_Card:
                    $paymentResource = new Card([
                        'card_holder'       => $payment['card_holder'],
                        'card_number'       => $payment['card_number'],
                        'is_credit'         => filter_var($payment['is_credit'], FILTER_VALIDATE_BOOLEAN),
                        'payment_amount'    => $payment['payment_amount'],
                    ]);
                    break;

                case Payment::PAYMENT_TYPE_Credit:
                    $paymentResource = new Credit([
                        'document_number'   => Credit::nextDocumentNumber() ?? '000001',
                        'interest'          => $payment['interest'],
                        'dues'              => $payment['dues'],
                        'payment_amount'    => $payment['payment_amount'],
                    ]);
                    break;

                case Payment::PAYMENT_TYPE_Check:
                    $paymentResource = new Check([
                        'bank_id'           => $payment['bank_id'],
                        'account_holder'    => $payment['account_holder'],
                        'due_date'          => $payment['due_date'],
                        'document_number'   => $payment['check_number'],
                        'payment_amount'    => $payment['payment_amount'],
                    ]);
                    break;

                case Payment::PAYMENT_TYPE_CreditNote:
                    // get CreditNote
                    $paymentResource = CreditNote::find( $payment['credit_note_id'] );
                    // TODO:
                    break;

                case Payment::PAYMENT_TYPE_Promissory:
                    // TODO: create Promissory payment
                    $paymentResource = new PromissoryNote;
                    break;

                // return with error
                default: return back()->withInput()
                    ->withErrors(__('pos::pos.payment.unknown-payment-type', [
                        'type'  => $payment['payment_type'],
                    ]));
            }

            // set payment currency
            $paymentResource->currency()->associate( $resource->currency );
            // link with partner
            $paymentResource->partnerable()->associate( $resource->partnerable );
            if (!$paymentResource->save())
                // return with errors
                return back()->withInput()
                    ->withErrors( $paymentResource->errors() );

            // create ReceipmentPayment
            $receipmentPayment = new ReceipmentPayment([
                'receipment_id'     => $resource->id,
                'payment_type'      => $payment['payment_type'],
                'payment_amount'    => $payment['payment_amount'],
            ]);
            $receipmentPayment->currency()->associate( $paymentResource->currency );
            $receipmentPayment->paymentable()->associate( $paymentResource );
            if (!$receipmentPayment->save())
                // return with errors
                return back()->withInput()
                    ->withErrors( $receipmentPayment->errors() );
        }

        // return success
        return true;
    }

}
