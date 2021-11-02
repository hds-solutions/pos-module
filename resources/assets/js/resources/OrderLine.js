import DocumentLine from '../../../../../backend-module/resources/assets/js/resources/DocumentLine';

export default class OrderLine extends DocumentLine {

    #thousands;
    #fields = [];
    #finder;
    #loading = false;

    constructor(document, container, focus = false) {
        super(document, container);
        this.#thousands = this.container.querySelectorAll('[name^="lines"][thousand]');
        this.#fields.push(...this.container.querySelectorAll('select'));
        this.#fields.push(...this.container.querySelectorAll('[name="lines[price][]"],[name="lines[quantity][]"]'));
        this.#finder = this.container.querySelector('[name="product-finder"]');
        this._init(focus);
    }

    destructor() {
        // update total
        this.#updateTotal(null);
    }

    _init(focus) {
        // capture change on fields
        this.#fields.forEach(field => field.addEventListener('change', e => {
            // ignore if field doesn't have form (deleted line)
            if (field.form === null) return;

            // if field is <select> fire product/variant change
            if (field.localName.match(/^select/)) this.#loadProduct(field);
            // if field is <input> fire product/variant change
            if (field.localName.match(/^input/)) this.#updatePrice(field);

            // update total
            this.#updateTotal(e);

            // redirect event to listener
            this.updated(e);
        }));

        // capture product finder event
        this.#finder.addEventListener('keydown', e => {
            // ignore if key isn't <enter>
            if (e.keyCode !== 13) return false;
            // disable default event
            else e.preventDefault();

            // ignore empty
            if (this.#finder.value.length === 0) return false;

            // parse quantity
            let match, qty = (match = this.#finder.value.match(/^(\d*\.?\d*)\*/)) ? (match[1] ?? 1) : 1,
                current = this.container.querySelector('[name="lines[quantity][]"]');
            // remove quantity from code
            if (qty !== null && current.value.length === 0) {
                // remove qty from code
                this.#finder.value = this.#finder.value.replace(qty+'*', '');
                // set qty on field
                current.value = qty;
            }

            // disable field while working
            this.#finder.setAttribute('disabled', true);

            // set focus on next line
            this.document.lines.last().container.querySelector('[name="'+this.#finder.name+'"]').focus();

            // find product
            Application.$.ajax({
                method: 'POST',
                url: '/sales/product',
                data: {
                    product: this.#finder.value,
                },
                success: data => {
                    // active flag to prevent multiple ajax requests
                    this.#loading = true;

                    // show last selected product
                    this.document.show(data);

                    // check if product exists or don't have price
                    if (data.product === null || data.price === null) {
                        // disable flag
                        this.#loading = false;
                        // re-enable field
                        this.#finder.removeAttribute('disabled');
                        // select all text
                        return this.#finder.select();
                    }

                    // set product on line
                    this.setProduct(data);
                },
            });
        });

        if (focus) this.focus();
    }

    setProduct(data) {
        // set product and variant
        this.container.querySelector('[name="lines[product_id][]"]').value = data.product.id;
        this.container.querySelector('[name="lines[variant_id][]"]').value = data.variant.id ?? null;

        // set product / variant values
        this.container.querySelector('#sku').textContent = data.variant.sku ?? data.product.code;
        this.container.querySelector('#product').textContent = data.product.name;//+' '+(data.variant.values ?? '');
        this.container.querySelector('#preview').src = data.image ?? null;

        // set price
        this.container.querySelector('[name="lines[price][]"]').value = data.price ?? null;

        // get line quantity
        let quantity = this.container.querySelector('[name="lines[quantity][]"]');
        // parse or set to 1 as default
        quantity.value = !data.price || quantity.value.length > 0 ? quantity.value : 1;
        // execute change event on quantity field
        OrderLine.fire('change', quantity);

        // disable flag
        this.#loading = false;

        // remove product finder
        this.#finder.remove();

        // add new line
        this.document.multiple.new();
        this.document.lines[this.document.lines.length - 1].focus();
    }

    focus() {
        this.#finder.focus();
        this.#finder.select();
    }

    #loadProduct(field) {
        // build request data
        let data = { _token: this.document.token },
            option;
        // load product,variant,currency selected options
        if ((option = this.container.querySelector('[name="lines[product_id][]"]').selectedOptions[0]).value) data.product = option.value;
        if ((option = this.container.querySelector('[name="lines[variant_id][]"]').selectedOptions[0]).value) data.variant = option.value;
        if ((option = field.form.querySelector('[name="currency_id"]').selectedOptions[0]).value) data.currency = option.value;
        // ignore if no product
        if (this.#loading || !data.product) return;
        // remove product finder
        this.#finder.remove();
        // request current price quantity
        Application.$.ajax({
            method: 'POST',
            url: '/sales/price',
            data: data,
            // update current price for product+variant on locator
            success: data => {
                // set price
                this.container.querySelector('[name="lines[price][]"]').value = data.price ?? null;
                // get line quantity
                let quantity = this.container.querySelector('[name="lines[quantity][]"]');
                // parse or set to 1 as default
                quantity.value = !data.price || quantity.value.length > 0 ? quantity.value : 1;
                // execute change event on quantity field
                OrderLine.fire('change', quantity);
            },
        });
    }

    #updatePrice(field) {
        // get fields
        let price = this.container.querySelector('[name="lines[price][]"]'),
            quantity = this.container.querySelector('[name="lines[quantity][]"]'),
            total = this.container.querySelector('[name="lines[total][]"]');

        // update total value
        total.value = (
            // convert price to integer without decimals
            parseInt(price.value.replace(/[^0-9\.]/g,'') * Math.pow(10, price.dataset.decimals))
            // multiply for quantity
            * parseFloat(quantity.value.length ? quantity.value : 0)
        // divide total for currency decimals
        ) / Math.pow(10, price.dataset.decimals);

        // fire thousands plugin formatter
        this.#thousands.forEach(thousand => OrderLine.fire('blur', thousand));
        // fire total change
        OrderLine.fire('change', total);
    }

    #updateTotal(event) {
        // total acumulator
        let total = 0;
        // foreach lines
        this.document.lines.forEach(line => {
            // parse total
            let lineTotal = line.container.querySelector('[name="lines[total][]"]').value.replace(/\,*/g, '') * 1;
            // ignore if is empty
            if (lineTotal == 0) return;
            // add to acumulator
            total += lineTotal;
        });
        // set total
        this.document.total.value = total > 0 ? total : '';
        // fire format
        if (total > 0) OrderLine.fire('blur', this.document.total);
    }

}
