import Event from '../../../../backend-module/resources/assets/js/utils/consoleevent';
import Payment from './models/Payment';

class POS {
    constructor() {
        this.total = document.querySelector('[name="total"]');
        this.lines = [];
    }

    register(lineContainer, totalFieldName = 'lines[total][]') {
        // register lineContainer
        this.lines.push(lineContainer);
        // capture total change on line
        lineContainer.querySelector('[name="'+totalFieldName+'"]')
            .addEventListener('change', e => this._update(totalFieldName));
        // check orderline form
        if (lineContainer.classList.contains('order-line-container'))
            // register orderline events
            this._orderLine(lineContainer);
        // check payment form
        if (lineContainer.classList.contains('payment-container'))
            // register payment events
            this._payment(lineContainer);
    }

    unregister(lineContainer, totalFieldName = 'lines[total][]') {
        // remove container from list
        this.lines.splice(this.lines.indexOf(lineContainer), 1);
        // update total price
        this._update(totalFieldName);
    }

    _update(totalFieldName) {
        // total acumulator
        let total = 0;
        // foreach lines
        this.lines.forEach(line => {
            // parse total
            let lineTotal = line.querySelector('[name="'+totalFieldName+'"]').value.replace(/\,*/g, '') * 1;
            // ignore if is empty
            if (lineTotal == 0) return;
            // add to acumulator
            total += lineTotal;
        });
        // set total
        this.total.value = total > 0 ? total : '';
        // fire format
        if (total > 0) (new Event('blur')).fire( this.total );
    }

    _orderLine(orderLineContainer) {
        // TODO: move here all JS that is on backend-module/app.js
    }

    _payment(paymentContainer) {
        // get payment type selector
        let paymentType = paymentContainer.querySelector('[name="payments[payment_type][]"]'),
            amount = paymentContainer.querySelector('[name="payments[payment_amount][]"]'),
            // Credit payment type fields
            interest = paymentContainer.querySelector('[name="payments[interest][]"]'),
            dues = paymentContainer.querySelector('[name="payments[dues][]"]'),
            // Check payment type fields
            bank_name = paymentContainer.querySelector('[name="payments[bank_name][]"]'),
            bank_account = paymentContainer.querySelector('[name="payments[bank_account][]"]'),
            account_holder = paymentContainer.querySelector('[name="payments[account_holder][]"]'),
            check_number = paymentContainer.querySelector('[name="payments[check_number][]"]'),
            due_date = paymentContainer.querySelector('[name="payments[due_date][]"]'),
            // CreditNote payment type fields
            credit_note_id = paymentContainer.querySelector('[name="payments[credit_note_id][]"]'),
            // Card payment type fields
            card_holder = paymentContainer.querySelector('[name="payments[card_holder][]"]'),
            card_number = paymentContainer.querySelector('[name="payments[card_number][]"]'),
            is_credit = paymentContainer.querySelector('[name="payments[is_credit][]"]'),
            // group fields by PaymentType
            fieldGroups = new Map([
                [ Payment.PAYMENT_TYPE_Cash,        [] ],
                [ Payment.PAYMENT_TYPE_Credit,      [ interest, dues ] ],
                [ Payment.PAYMENT_TYPE_Check,       [ bank_name, bank_account, account_holder, check_number, due_date ] ],
                [ Payment.PAYMENT_TYPE_CreditNote,  [ credit_note_id ] ],
                [ Payment.PAYMENT_TYPE_Card,        [ card_holder, card_number, is_credit ] ],
            ]);
        // capture payment type change
        paymentType.addEventListener('change', e => {
            // set PaymentType and Amount fields as mandatory
            paymentType.setAttribute('required', true);
            amount.setAttribute('required', true);
            // reset fields state
            fieldGroups.forEach(group => group.forEach(field => field.removeAttribute('required')));
            // enable selected paymentType fields only
            fieldGroups.get(paymentType.value).forEach(field => field.setAttribute('required', true));
        });
    }
}

window.pos = new POS;
