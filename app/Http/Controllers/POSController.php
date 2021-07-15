<?php

namespace HDSSolutions\Finpar\Http\Controllers;

use App\Http\Controllers\Controller;
use Closure;
use HDSSolutions\Finpar\Contracts\PaymentContract;
use HDSSolutions\Finpar\Http\Request;
use HDSSolutions\Finpar\Interfaces\Document;
use HDSSolutions\Finpar\Models\Card;
use HDSSolutions\Finpar\Models\CashBook;
use HDSSolutions\Finpar\Models\CashLine;
use HDSSolutions\Finpar\Models\Check;
use HDSSolutions\Finpar\Models\Credit;
use HDSSolutions\Finpar\Models\CreditNote;
use HDSSolutions\Finpar\Models\Customer;
use HDSSolutions\Finpar\Models\Employee;
use HDSSolutions\Finpar\Models\Invoice;
use HDSSolutions\Finpar\Models\Order;
use HDSSolutions\Finpar\Models\OrderLine;
use HDSSolutions\Finpar\Models\Payment;
use HDSSolutions\Finpar\Models\Product;
use HDSSolutions\Finpar\Models\PromissoryNote;
use HDSSolutions\Finpar\Models\Receipment;
use HDSSolutions\Finpar\Models\ReceipmentInvoice;
use HDSSolutions\Finpar\Models\ReceipmentPayment;
use HDSSolutions\Finpar\Models\Variant;
use Illuminate\Support\Facades\DB;

class POSController extends Controller {

    public function __construct() {
        // check if POS is configured
        $this->middleware(function(Request $request, Closure $next) {
            // check POS configuration
            if ( pos_settings()->currency() === null ||
                 pos_settings()->branch() === null ||
                 pos_settings()->warehouse() === null ||
                 pos_settings()->cashBook() === null )
                // redirect to pos index
                return redirect()->route('backend.pos');
            // continue normal execution
            return $next($request);
        })->except([ 'index', 'session' ]);
    }

    public function index(Request $request) {
        // load branches from current company
        $branches = backend()->company()->branches()->with([ 'warehouses.locators' ])->get();
        // load cashBooks
        $cashBooks = CashBook::all();

        // return configure POS view
        return view('pos::pos.index', compact('branches', 'cashBooks'));
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
        return redirect()->route('backend.pos.create');
    }

    public function create(Request $request) {
        // load cash_books
        $customers = Customer::with([
            // 'addresses',
        ])->get();
        // load products
        $products = Product::with([
            'images',
            'prices',
            'variants.prices',
        ])->get();

        $highs = [
            'stamping'          => $stamping = Invoice::currentStamping(),
            'document_number'   => Invoice::nextDocumentNumber( $stamping ),
        ];

        // show main form
        return view('pos::pos.create', compact('customers', 'products', 'highs'));
    }

    public function store(Request $request) {
        // start a transaction
        DB::beginTransaction();

        // POS always creates a Sales document
        $request->merge([ 'is_purchase' => false ]);
        // create resource
        $order = Order::make( $request->input() );
        $order->transacted_at = now();
        $order->document_number = Order::nextDocumentNumber();
        $order->partnerable()->associate( Customer::find($request->get('customer_id')) );
        $order->branch()->associate( pos_settings()->branch() );
        $order->warehouse()->associate( pos_settings()->warehouse() );
        $order->currency()->associate( pos_settings()->currency() );
        // TODO: get logged Employee
        $order->employee()->associate( Employee::inRandomOrder()->first() );

        // save resource
        if (!$order->save())
            // redirect with errors
            return back()->withInput()
                ->withErrors( $order->errors() );

        // sync order lines
        if (($redirect = $this->syncLines($order, $request->get('lines'))) !== true)
            // return redirection
            return $redirect;

        // complete order
        if (!$order->processIt( Document::ACTION_Complete ))
            // return document error
            return back()->withInput()
                ->withErrors( $order->getDocumentError() );

        // create inovice from order
        $invoice = Invoice::createFromOrder($order, [
            'stamping'          => $request->input('stamping'),
            'document_number'   => $request->input('document_number'),
            'is_credit'         => $request->input('payment_rule') == Invoice::PAYMENT_RULE_Credit,
        ]);
        // check if invoice was created
        if (!$invoice->exists)
            // return invoice errors
            return back()->withInput()
                ->withErrors( $invoice->errors() );
        // check if lines were created
        foreach ($invoice->lines as $line)
            // check if line is created
            if (!$line->exists)
                // return invoiceLine errors
                return back()->withInput()
                    ->withErrors( $line->errors() );

        // complete invoice
        if (!$invoice->processIt( Document::ACTION_Complete ))
            // return document error
            return back()->withInput()
                ->withErrors( $invoice->getDocumentError() );

        // commit changes to database
        DB::commit();

        // go to payment window
        return redirect()->route('backend.pos.show', $invoice);
    }

    public function show(Request $request, Invoice $resource) {
        // check if invoice is already paid
        if ($resource->is_paid)
            // reject with error
            return redirect()->route('backend.pos.create')
                ->withErrors(__('pos::pos.invoice.already-paid'));

        // eager load resource data
        $resource->load([
            'currency',
            'partnerable' => fn($customer) => $customer->with([
                // load available CreditNotes of Customer
                'creditNotes' => fn($creditNote) => $creditNote->available()->with([ 'identity' ]),
            ]),
            'lines' => fn($line) => $line->with([
                'currency',
                'product.images',
                'variant' => fn($variant) => $variant->with([
                    'images',
                    'values' => fn($value) => $value->with([
                        'option_value',
                    ]),
                ]),
            ]),
        ]);

        // show invoice and payment methods
        return view('pos::pos.show', compact('resource'));
    }

    public function pay(Request $request, Invoice $resource) {
        // start a transaction
        DB::beginTransaction();

        // create receipment
        $receipment = new Receipment([
            'document_number'   => Receipment::nextDocumentNumber(),
        ]);
        $receipment->employee()->associate( $resource->employee );   // TODO: get employee from session
        $receipment->partnerable()->associate( $resource->partnerable );
        $receipment->currency()->associate( $resource->currency );
        if (!$receipment->save())
            // return with errors
            return back()->withInput()
                ->withErrors( $receipment->errors() );

        // assign Invoice to Receipment
        $receipmentInvoice = new ReceipmentInvoice([
            'receipment_id'     => $receipment->id,
            'imputed_amount'    => $resource->total,
        ]);
        $receipmentInvoice->invoice()->associate( $resource );
        if (!$receipmentInvoice->save())
            // return with errors
            return back()->withInput()
                ->withErrors( $receipmentInvoice->errors() );

        // create payments
        foreach (array_group($request->input('payments')) as $payment) {
            // ignore empty payments
            if ($payment['payment_type'] === null || $payment['payment_amount'] === null) continue;

            // check payment type
            switch ($payment['payment_type']) {
                case Payment::PAYMENT_TYPE_Cash:
                    // get open cash
                    if (($cash = pos_settings()->cashBook()->cashes()->open()->first()) === null)
                        // return with error
                        return back()->withInput()
                            ->withErrors(__('pos::pos.payment.no-open-cash', [
                                'cashBook'  => pos_settings()->cashBook()->name,
                            ]));

                    $paymentResource = $cash->lines()->make([
                        'cash_type'         => CashLine::CASH_TYPE_Invoice,
                        'description'       => __('pos::pos.payment.cash-line.description', [
                            'invoice'       => $resource->document_number,
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
                        'document_number'   => Credit::nextDocumentNumber()
                        'interest'          => $payment['interest'],
                        'dues'              => $payment['dues'],
                        'payment_amount'    => $payment['payment_amount'],
                    ]);
                    break;

                case Payment::PAYMENT_TYPE_Check:
                    $paymentResource = new Check([
                        'bank_name'         => $payment['bank_name'],
                        'bank_account'      => $payment['bank_account'],
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
                'receipment_id'     => $receipment->id,
                'payment_type'      => $payment['payment_type'],
                'payment_amount'    => $paymentResource->payment_amount ?? $paymentResource->amount,
                // 'used_amount'       => $paymentResource->payment_amount ?? $paymentResource->amount,
            ]);
            $receipmentPayment->currency()->associate( $paymentResource->currency );
            $receipmentPayment->paymentable()->associate( $paymentResource );
            if (!$receipmentPayment->save())
                // return with errors
                return back()->withInput()
                    ->withErrors( $receipmentPayment->errors() );
        }

        // complete receipment
        if (!$receipment->processIt( Document::ACTION_Complete ))
            // return with error
            return back()->withInput()
                ->withErrors( $receipment->getDocumentError() );

        // commit transaction
        DB::commit();

        // redirect to POS home
        return redirect()->route('backend.pos.create');
    }

    private function syncLines(Order $order, array $lines) {
        // load order lines
        $order->load(['lines']);

        // foreach new/updated lines
        foreach (($lines = array_group($lines)) as $line) {
            // ignore line if product wasn't specified
            if (!isset($line['product_id']) || is_null($line['quantity'])) continue;
            // load product
            $product = Product::find($line['product_id']);
            // load variant, if was specified
            $variant = isset($line['variant_id']) ? $product->variants->firstWhere('id', $line['variant_id']) : null;

            // find existing line or create a new one
            $orderLine = $order->lines()->firstOrNew([
                'product_id'        => $product->id,
                'variant_id'        => $variant->id ?? null,
            ], [
                // TODO: get logged Employee
                'employee_id'       => $order->employee_id,
                'currency_id'       => $order->currency->id,
                'price_reference'   => $variant?->price($order->currency)->pivot->price ?? $product->price($order->currency)->pivot->price,
            ]);

            // update line values
            $orderLine->fill([
                'price_ordered'     => $line['price'] ?? 0,
                'quantity_ordered'  => $line['quantity'] ?? null,
            ]);
            // save order line
            if (!$orderLine->save())
                return back()->withInput()
                    ->withErrors( $orderLine->errors() );
        }

        // find removed order lines
        foreach ($order->lines as $line) {
            // deleted flag
            $deleted = true;
            // check against $request->lines
            foreach ($lines as $rLine) {
                // ignore empty lines
                if (!isset($rLine['product_id'])) continue;
                // check if line exists
                if ($line->product_id == $rLine['product_id'] &&
                    $line->variant_id == ($rLine['variant_id'] ?? null))
                    // change flag to keep line
                    $deleted = false;
            }
            // remove line if was deleted
            if ($deleted) $line->delete();
        }

        // return success
        return true;
    }

}
