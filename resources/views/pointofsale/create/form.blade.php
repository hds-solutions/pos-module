@include('backend::components.errors')

<x-form-foreign name="currency_id" required
    :values="backend()->currencies()" default="{{ pos_settings()->currency()->id }}"

    append="decimals" show="code"
    class="d-none" />

<x-backend-form-foreign name="customer_id" required
    foreign="customers" :values="$customers"

    foreign-add-label="customers::customers.add"
    show="business_name"

    label="pos::pointofsale.customer_id.0"
    placeholder="pos::pointofsale.customer_id._"
    {{-- helper="pos::pointofsale.customer_id.?" --}} />

<x-backend-form-options :resource="null" name="payment_rule" required
    :values="\HDSSolutions\Laravel\Models\Invoice::PAYMENT_RULES"
    default="{{ Invoice::PAYMENT_RULE_Cash }}"

    label="pos::pointofsale.payment_rule.0"
    placeholder="pos::pointofsale.payment_rule._"
    {{-- helper="pos::pointofsale.payment_rule.?" --}} />

<x-backend-form-text name="stamping" required
    :default="$highs['stamping'] ?? null"

    label="pos::pointofsale.stamping.0"
    placeholder="pos::pointofsale.stamping._"
    {{-- helper="pos::pointofsale.stamping.?" --}} />

<x-backend-form-text name="document_number" required
    :default="$highs['document_number'] ?? null"
    label="pos::pointofsale.document_number.0"
    placeholder="pos::pointofsale.document_number._"
    {{-- helper="pos::pointofsale.document_number.?" --}} />

{{-- TODO ADDRESSES--}}
{{-- <x-backend-form-foreign :resource="$resource ?? null" name="warehouse_id" required
    foreign="warehouses" :values="$branches->pluck('warehouses')->flatten()"
    filtered-by="[name=address_id]" filtered-using="branch"

    foreign-add-label="pos::warehouses.add"

    label="pos::pointofsale.warehouse_id.0"
    placeholder="pos::pointofsale.warehouse_id._"
    helper="pos::pointofsale.warehouse_id.?" /> --}}

<x-backend-form-multiple name="lines" values-as="products"
    :values="$products" :selecteds="[]" grouped old-filter-fields="product_id,quantity"
    contents-size="xxl" contents-view="pos::pointofsale.create.line" class="my-2" data-type="pointofsale"
    card="bg-light"

    label="pos::pointofsale.lines.0">

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
    submit="pos::pointofsale.save"
    cancel="pos::pointofsale.cancel" cancel-route="backend.pointofsale" />
