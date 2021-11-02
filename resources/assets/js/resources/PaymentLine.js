import DocumentLine from '../../../../../backend-module/resources/assets/js/resources/DocumentLine';
import Payment from '../models/Payment';

export default class PaymentLine extends DocumentLine {

    #fields = new Map;
    #paymentType;
    #paymentAmount;
    #btnDelete;

    #used = false;
    #type;
    #amount = 0;

    constructor(document, container, focus = false) {
        super(document, container);
        // get payment type selector
        this.#paymentType = this.container.querySelector('[name="payments[payment_type][]"]'),
        this.#type = this.#paymentType.value;
        this.#paymentAmount = this.container.querySelector('[name="payments[payment_amount][]"]'),
        this.#btnDelete = this.container.querySelector('[data-action="delete"]');
        this._init(focus);
    }

    destructor() {
        // update total
        this.#updateTotal(null);
    }

    get type() { return this.#type; }
    get amount() { return this.#amount; }

    _init(focus) {
        // hide delete btn by default
        this.#btnDelete.classList.add('d-none');
        // Credit payment type fields
        let interest = this.container.querySelector('[name="payments[interest][]"]'),
            dues = this.container.querySelector('[name="payments[dues][]"]'),
            // Check payment type fields
            bank_id = this.container.querySelector('[name="payments[bank_id][]"]'),
            account_holder = this.container.querySelector('[name="payments[account_holder][]"]'),
            check_number = this.container.querySelector('[name="payments[check_number][]"]'),
            due_date = this.container.querySelector('[name="payments[due_date][]"]'),
            // CreditNote payment type fields
            credit_note_id = this.container.querySelector('[name="payments[credit_note_id][]"]'),
            // Card payment type fields
            card_holder = this.container.querySelector('[name="payments[card_holder][]"]'),
            card_number = this.container.querySelector('[name="payments[card_number][]"]'),
            is_credit = this.container.querySelector('[name="payments[is_credit][]"]');
        // group fields by PaymentType
        this.#fields = new Map([
            [ Payment.PAYMENT_TYPE_Cash,        [] ],
            [ Payment.PAYMENT_TYPE_Credit,      [ interest, dues ] ],
            [ Payment.PAYMENT_TYPE_Check,       [ bank_id, account_holder, check_number, due_date ] ],
            [ Payment.PAYMENT_TYPE_CreditNote,  [ credit_note_id ] ],
            [ Payment.PAYMENT_TYPE_Card,        [ card_holder, card_number, is_credit ] ],
        ]);
        // capture payment type change
        this.#paymentType.addEventListener('change', e => {
            // set PaymentType and Amount fields as mandatory
            this.#paymentType.setAttribute('required', true);
            this.#paymentAmount.setAttribute('required', true);
            // reset fields state
            this.#fields.forEach(group => group.forEach(field => field.removeAttribute('required')));
            if (this.#paymentType.value) {
                // save payment type
                this.#type = this.#paymentType.value;
                // enable selected paymentType fields only
                this.#fields.get(this.#paymentType.value).forEach(field => field.setAttribute('required', true));
            } else {
                // remove PaymentType and Amount fields required
                this.#paymentType.removeAttribute('required');
                this.#paymentAmount.removeAttribute('required');
            }
        });
        // capture total change
        this.#paymentAmount.addEventListener('change', e => {
            // ignore if field doesn't have form (deleted line)
            if (this.#paymentAmount.form === null) return;

            // save payment amount
            this.#amount = parseFloat(this.#paymentAmount.value.replace(/\,*/g, ''));

            // update total
            this.#updateTotal(e);

            // redirect event to listener
            this.updated(e);
        });

        // capture amount event
        this.#paymentAmount.addEventListener('keydown', e => {
            // ignore if key isn't <enter>
            if (e.keyCode !== 13) return false;
            // disable default event
            else e.preventDefault();

            // ignore empty
            if (this.#paymentAmount.value.length === 0) return false;

            // add new line
            if (!this.#used) this.#used = this.document.multiple.new() || true;
            // set focus on last line
            this.document.lines[this.document.lines.length - 1].focus( false );

            // show delete btn
            this.#btnDelete.classList.remove('d-none');
        });

        if (focus) this.focus();
    }

    focus(onAmount = true) {
        if (onAmount) {
            this.#paymentAmount.focus();
            this.#paymentAmount.select();
        } else
            this.#paymentType.focus();
    }

    #updateTotal(event) {
        // total acumulator
        let total = 0;
        // foreach lines
        this.document.lines.forEach(line => {
            // ignore if not invoice line
            if (!(line instanceof PaymentLine)) return;

            // parse total
            let lineTotal = line.container.querySelector('[name="payments[payment_amount][]"]').value.replace(/\,*/g, '') * 1;
            // ignore if is empty
            if (lineTotal == 0) return;
            // add to acumulator
            total += lineTotal;
        });

        // set payment totals
        this.document.payments.value = total > 0 ? total : 0;
        // fire format
        if (total > 0) PaymentLine.fire('blur', this.document.payments);
    }

}
