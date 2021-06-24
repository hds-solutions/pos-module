@include('backend::components.errors')

<x-form-foreign name="currency_id" required
    :values="backend()->currencies()" default="{{ pos_settings()->currency()->id }}"

    append="decimals" show="code"
    class="d-none" />

<x-backend-form-foreign name="customer_id" required
    foreign="customers" :values="$customers"

    foreign-add-label="customers::customers.add"
    show="business_name"

    label="pos::pos.customer_id.0"
    placeholder="pos::pos.customer_id._"
    {{-- helper="pos::pos.customer_id.?" --}} />

<x-backend-form-options :resource="null" name="payment_rule" required
    :values="\HDSSolutions\Finpar\Models\Invoice::PAYMENT_RULES"
    default="{{ \HDSSolutions\Finpar\Models\Invoice::PAYMENT_RULE_Cash }}"

    label="pos::pos.payment_rule.0"
    placeholder="pos::pos.payment_rule._"
    {{-- helper="pos::pos.payment_rule.?" --}} />

<x-backend-form-text name="stamping" required
    :default="$highs['stamping'] ?? null"

    label="pos::pos.stamping.0"
    placeholder="pos::pos.stamping._"
    {{-- helper="pos::pos.stamping.?" --}} />

<x-backend-form-text name="document_number" required
    :default="$highs['document_number'] ?? null"
    label="pos::pos.document_number.0"
    placeholder="pos::pos.document_number._"
    {{-- helper="pos::pos.document_number.?" --}} />

{{-- TODO ADDRESSES--}}
{{-- <x-backend-form-foreign :resource="$resource ?? null" name="warehouse_id" required
    foreign="warehouses" :values="$branches->pluck('warehouses')->flatten()"
    filtered-by="[name=address_id]" filtered-using="branch"

    foreign-add-label="pos::warehouses.add"

    label="pos::pos.warehouse_id.0"
    placeholder="pos::pos.warehouse_id._"
    helper="pos::pos.warehouse_id.?" /> --}}

<x-backend-form-multiple name="lines" values-as="products"
    :values="$products" :selecteds="[]" grouped old-filter-fields="product_id,quantity"
    contents-size="xxl" contents-view="pos::pos.create.line" class="my-2" data-type="pos"
    card="bg-light"

    label="pos::pos.lines.0">

    <x-slot name="card-footer">
        <div class="row">
            <div class="col-9 col-xl-10 offset-1">
                <div class="row">
                    <div class="col-3 offset-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold px-3">Total:</span>
                            </div>
                            <input name="total" type="number" min="0" thousand readonly
                                value="{{ old('total') }}" tabindex="-1"
                                data-currency-by="[name=currency_id]" data-keep-id="true" data-decimals="0"
                                class="form-control form-control-lg text-right font-weight-bold"
                                placeholder="@lang('sales::order.lines.total.0')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

</x-backend-form-multiple>

<x-backend-form-controls
    submit="pos::pos.save"
    cancel="pos::pos.cancel" cancel-route="backend.pos" />
