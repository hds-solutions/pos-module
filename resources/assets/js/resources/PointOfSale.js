require('../../../../../backend-module/resources/assets/js/utils/transaction');

import Application from '../../../../../backend-module/resources/assets/js/resources/Application';

import Alert from '../../../../../backend-module/resources/assets/js/utils/alert';
import Event from '../../../../../backend-module/resources/assets/js/utils/consoleevent';
import Document from '../../../../../backend-module/resources/assets/js/resources/Document';
import OrderLine from './OrderLine';
import PaymentLine from './PaymentLine';
import Payment from '../models/Payment';

export default class PointOfSale extends Document {

    submitting = false;

    constructor() {
        super();
        this.form = document.querySelector('form');
        this.last_product = this.form.querySelector('#last-product');
        if (this.last_product) this.last_product.bg_classes = Array.from(this.last_product.classList.values()).filter(class_name => class_name.match(/^bg-/));
        this.total = this.form.querySelector('[name="total"]');
        this.payments = this.form.querySelector('[name="payments_amount"]');
        if (this.payments) this.payments.oClassList = Array.from( this.payments.classList.values() ).filter(class_name => (class_name.match(/^text-(left|right)$/) && (class_name.match(/^text-/) || class_name.match(/^bg-/))));
        this.return = this.form.querySelector('[name="return_amount"]');
        this.currency = this.form.querySelector('[name="currency_id"]');
            this.currency = this.currency !== null ? parseInt(this.currency.value) : null;
        this.price_list = this.form.querySelector('[name="price_list_id"]');
            this.price_list = this.price_list !== null ? parseInt(this.price_list.value) : null;
        this.noImage = document.querySelector('[name="assets-path"]').content+'backend-module/assets/images/default.jpg';
        this.printable = document.querySelector('[data-printable][data-print="true"]');
        this.transacted_at = this.form.querySelector('#transacted-at');
        this.modals = {
            active: false,
            customer: Application.instance('customers-modal'),
            product: Application.instance('products-modal'),
        };
        this.actions = {
            F3: 'customer',
            F6: 'product',
            F9: 'pay',
            F12: 'finish',
        };
        this._init();
    }

    _init() {
        // capture user keys interation
        this._captureKeys();
        // capture window re-sizing
        this._captureResize();
        // capture modals events
        this._modalEvents();
        // update datetime
        this._updateTransactedAt();

        // keep session alive
        setInterval(_ => Application.$.get('/keep-alive'), 60 * 1000 * 5);

        // check if print button exists
        if (this.printable) PointOfSale.fire('click', this.printable);
    }

    _getContainerInstance(container) {
        switch (true) {
            case container.classList.contains('line-container'):
                return new OrderLine(this, container, this.lines.length == 0);
            case container.classList.contains('payment-container'):
                let line = new PaymentLine(this, container, this.lines.length == 0);
                line
                    .updated(e => { this.#updateReturnAmount(); })
                    .removed(e => { this.#updateReturnAmount(); });
                return line;
        }
        return null;
    }

    #updateReturnAmount() {
        // check if amount of payments is greater
        let total;
        if (parseFloat(this.payments.value.replace(/\,*/g, '')) > (total = parseFloat(this.total.value.replace(/\,*/g, '')))) {
            // reset payments amount colors
            this.payments.classList.remove('text-white');
            this.payments.classList.remove('bg-danger');
            this.payments.oClassList.forEach(class_name => this.payments.classList.add(class_name));
        }

        let cash = 0;
        this.lines.forEach(payment => payment.type == Payment.PAYMENT_TYPE_Cash ?
            cash += payment.amount : null);
        // update cash return amount
        this.return.value = cash > total ? cash - total : 0;
        PointOfSale.fire('blur', this.return);
    }

    show(data) {
        // reset bg classes
        Array.from(this.last_product.classList.values()).forEach(class_name => {
            // ignore class if isn't background
            if (!class_name.match(/^bg-/)) return;
            // remove bg class
            this.last_product.classList.remove(class_name);
        });

        // if product wasnt found, add bg danger class
        if (data.product === null || data.price === null) this.last_product.classList.add('bg-danger');
        // add default bg classes
        else this.last_product.bg_classes.forEach(class_name => this.last_product.classList.add( class_name ));

        // set product data
        this.last_product.querySelector('#preview').style.backgroundImage = 'url('+(data.image ?? this.noImage)+')';
        this.last_product.querySelector('#name').textContent = data.product !== null ? data.product.name : 'Producto inexistente!';
        this.last_product.querySelector('#description').textContent = (data.variant !== null || data.product !== null) ? (data.variant.sku ?? data.product.brief) : null;
        this.last_product.querySelector('#price').textContent = data.price ?? '--';
    }

    customer(e) {
        // prevent default action
        e.preventDefault();
        // check if another modal is open
        if (this.modals.active) return;
        // show customers modal
        this.modals.customer.show();
        // change active flag
        this.modals.active = 'customer';
    }

    product(e) {
        // prevent default action
        e.preventDefault();
        // check if another modal is open
        if (this.modals.active || this.payments) return;
        // show customers modal
        this.modals.product.show();
        // change active flag
        this.modals.active = 'product';
    }

    pay(e) {
        if (this.submitting) return;
        this.submitting = true;
        // check payments amount
        if (this.payments && parseFloat(this.total.value.replace(/\,*/g, '')) > parseFloat(this.payments.value.replace(/\,*/g, ''))) {
            // cancel form submission
            e.preventDefault();
            this.payments.classList.forEach(class_name => (!class_name.match(/^text-(left|right)$/) && (class_name.match(/^bg-/) || class_name.match(/^text-/))) && this.payments.classList.remove( class_name ));
            this.payments.classList.add('text-white');
            this.payments.classList.add('bg-danger');
            // show error
            return Alert.danger('Monto de Pagos', 'El monto de pagos no puede ser menor al monto de la factura!')
                // set focus on payment after modal close
                .then(e => this.lines[this.lines.length - 1].focus());
        }

        // FIXME: thousand plugin not removing comas
        this.form.querySelectorAll('[thousand]').forEach(thousand => thousand.value = thousand.value.replace(/\,*/g, ''));
        // submit form
        this.form.submit();
    }

    finish(e, button) {
        // prevent default action
        e.preventDefault();
        // redirect to button href
        document.location = button.href;
    }

    _captureKeys() {
        document.addEventListener('keydown', event => {
            // check if action is registered
            if (!this.actions.hasOwnProperty( event.key )) return;
            // get button
            let button = document.querySelector('[data-key="'+event.key+'"]');
            // execute local action
            if (button) this[ this.actions[event.key] ]( event, button );
        });
        // find buttons
        Object.keys(this.actions).forEach((key, action) => {
            // find button
            let button = document.querySelector('[data-key="'+key+'"]');
            // check if exists
            if (!button) return;
            // capture click event
            button.addEventListener('click', event => {
                // execute local action
                this[ this.actions[key] ]( event, button );
            });
        });
    }

    _captureResize() {
        // capture window resize event
        window.addEventListener('resize', e => { this._resize(); }, true);
        // fire once to set size on start
        this._resize();
    }

    _modalEvents() {
        // capture customer selection
        this.modals.customer && this.modals.customer.selected(customer => {
            this.form.querySelector('[name="customer_id"]').value = customer.id;
            this.form.querySelector('[name="customer_ftid"]').textContent = customer.ftid;
            this.form.querySelector('[name="customer_name"]').textContent = customer.business_name ?? customer.full_name;
        });
        // capture product selection
        this.modals.product && this.modals.product.selected(data => {
            // get Variant price
            let price = null;
            data.prices.forEach(_price => {
                // ignore if not current price_list
                if (_price.price_list_id != this.price_list) return;
                price = _price.price.price;
            });

            // if variant dont have price, get Product price
            if (price === null) data.product.prices.forEach(_price => {
                // ignore if not current price_list
                if (_price.price_list_id != this.price_list) return;
                price = _price.price.price;
            });

            // show last selected product
            this.show(data = {
                product: data.product,
                variant: data,
                image: data.images && data.images.length ? data.images[0].url : (
                    data.product.images && data.product.images.length ? data.product.images[0].url :
                    this.noImage),
                price: price,
            });

            // ignore if product dont have price
            if (data.price === null) return false;

            // set product on last line
            this.lines[ this.lines.length - 1 ].setProduct(data);
        });
        // capture modals close
        for (let i in this.modals)
            // set active flag to false
            this.modals[i] && this.modals[i].hide(e => {
                // remove active modal
                this.modals.active = false;
                // set focus on last product line
                this.lines[this.lines.length - 1].focus();
            });
    }

    _updateTransactedAt() {
        if (!this.transacted_at) return;
        let transacted_at = this.transacted_at.textContent,
            date = transacted_at.substr(0, transacted_at.length - 6),
            time;
        // parse time
        setInterval(_ => {
            time = (new Date).toTimeString().substr(0, 5);
            this.transacted_at.textContent = date+' '+time;
        }, 1000);
    }

    _resize() {
        if (document.querySelector('section.pos-lines') === null) return;
        // get lines
        let container = document.querySelector('section.pos-lines'),
            lines = container.querySelector('*');
        // hide container temporal
        let display = lines.style.display;
        lines.style.display = 'none';
        // remove calculated height
        container.style.removeProperty('height');
        // set new height
        container.style.setProperty('height', container.clientHeight+'px');
        // show container
        lines.style.display = display;
    }

    static printable() {
        // find print button
        let print = document.querySelector('[data-printable]');
        // check if button exists and fire click
        if (print && print.dataset.print == 'true') PointOfSale.fire('click', print);
    }

}
