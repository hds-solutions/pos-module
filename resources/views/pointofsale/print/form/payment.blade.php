<div class="col-12 d-flex flex-column">

    <div class="form-row py-2">
        <div class="col text-left">{{ __(Payment::PAYMENT_TYPES[$payment->receipmentPayment->payment_type]) }} {!! match($payment->receipmentPayment->payment_type) {
            Payment::PAYMENT_TYPE_Cash          => $payment->cash->cashBook->name,
            //Payment::PAYMENT_TYPE_Card          => $payment->card_holder.' <small>**** **** **** '.$payment->card_number.'</small>',
            Payment::PAYMENT_TYPE_Card          => '<small>'.$payment->document_number.'</small>',
            Payment::PAYMENT_TYPE_Credit        => trans_choice('sales::receipment.payments.dues.0', $payment->dues, [ 'dues' => $payment->dues ]).' <small>'.$payment->interest.'%</small>',
            Payment::PAYMENT_TYPE_Check         => $payment->document_number.'<small class="ml-2">'.$payment->bank_name.'</small>',
            Payment::PAYMENT_TYPE_CreditNote    => $payment->document_number.'<small class="ml-2">'.$payment->payment_amount.'</small>',
            default => null,
        } !!}</div>
        <div class="col text-right pr-2">{{ currency($payment->receipmentPayment->currency_id)->code }} <b>{{ number($payment->receipmentPayment->payment_amount, currency($payment->receipmentPayment->currency_id)->decimals) }}</b></div>
    </div>

</div>
