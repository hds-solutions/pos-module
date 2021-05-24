@include('backend::components.errors')

<x-backend-form-foreign :resource="null" name="currency_id" required
    foreign="currencies" :values="backend()->currencies()" default="{{ pos_settings()->currency()->id }}"

    {{-- foreign-add-label="{{ __('customers::customers.add') }}" --}}
    {{-- show="business_name" --}}

    label="{{ __('pos::pos.currency_id.0') }}"
    placeholder="{{ __('pos::pos.currency_id._') }}"
    {{-- helper="{{ __('pos::pos.currency_id.?') }}" --}}
    class="d-none" />

<x-backend-form-foreign :resource="null" name="customer_id" required
    foreign="customers" :values="$customers"

    foreign-add-label="{{ __('customers::customers.add') }}"
    show="business_name"

    label="{{ __('pos::pos.customer_id.0') }}"
    placeholder="{{ __('pos::pos.customer_id._') }}"
    {{-- helper="{{ __('pos::pos.customer_id.?') }}" --}}
    class="mb-2" />

<x-backend-form-options :resource="null" name="payment_rule" required
    :values="\HDSSolutions\Finpar\Models\Invoice::PAYMENT_RULES"
    default="{{ \HDSSolutions\Finpar\Models\Invoice::PAYMENT_RULE_Cash }}"

    label="{{ __('pos::pos.payment_rule.0') }}"
    placeholder="{{ __('pos::pos.payment_rule._') }}"
    {{-- helper="{{ __('pos::pos.payment_rule.?') }}" --}} />

<x-backend-form-text :resource="null" name="document_number" required
    label="{{ __('pos::pos.document_number.0') }}"
    placeholder="{{ __('pos::pos.document_number._') }}"
    class="mb-2" />

{{-- TODO ADDRESSES--}}
{{-- <x-backend-form-foreign :resource="$resource ?? null" name="warehouse_id" required
    foreign="warehouses" :values="$branches->pluck('warehouses')->flatten()"
    filtered-by="[name=address_id]" filtered-using="branch"

    foreign-add-label="{{ __('pos::warehouses.add') }}"

    label="{{ __('pos::pos.warehouse_id.0') }}"
    placeholder="{{ __('pos::pos.warehouse_id._') }}"
    helper="{{ __('pos::pos.warehouse_id.?') }}" /> --}}

<div class="form-row form-group mb-2">
    <label class="col-12 col-md-3 col-lg-2 control-label mt-2 mb-3">@lang('pos::pos.lines.0')</label>
    <div class="col-12 col-md-9 col-lg-10">
        <div class="card bg-light">
            <div class="card-body py-0" data-multiple=".order-line-container" data-template="#new">
                @php $old_lines = array_group(old('lines') ?? []); @endphp
                {{-- add new added --}}
                @foreach($old_lines as $old)
                    {{-- ignore empty --}}
                    @if ( ($old['product_id'] ?? null) === null &&
                        ($old['variant_id'] ?? null) === null)
                        @continue
                    @endif
                    @include('pos::pos.create.line', [
                        'products'  => $products,
                        'selected'  => null,
                        'old'       => $old,
                    ])
                @endforeach

                {{-- add empty for adding new lines --}}
                @include('pos::pos.create.line', [
                    'products'  => $products,
                    'selected'  => null,
                    'old'       => null,
                ])

            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-9 col-xl-10 offset-1">
                        <div class="row">
                            <div class="col-3 offset-9">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold px-3">Total:</span>
                                    </div>
                                    <input name="total" type="number" min="0" thousand readonly
                                        value="{{ old('total') }}"
                                       data-currency-by="[name=currency_id]" data-keep-id="true" data-decimals="0"
                                       class="form-control form-control-lg text-right font-weight-bold"
                                       placeholder="@lang('sales::order.lines.total.0')">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-backend-form-controls
    submit="pos::pos.save"
    cancel="pos::pos.cancel" cancel-route="backend.pos" />
