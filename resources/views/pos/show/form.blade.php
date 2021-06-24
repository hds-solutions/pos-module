@include('backend::components.errors')

<x-form-foreign name="currency_id" required
    :values="backend()->currencies()" default="{{ pos_settings()->currency()->id }}"

    append="decimals" show="code"
    class="d-none" />

<x-form-multiple name="payments" values-as="creditNotes"
    :values="$resource->partnerable->creditNotes" :selecteds="[]" grouped old-filter-fields="payment_type,payment_amount"
    contents-size="xxl" contents-view="pos::pos.show.payment" data-type="pos"

    card="bg-light" container-class="py-2">

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
                                value="{{ old('total') }}"
                                data-currency-by="[name=currency_id]" data-keep-id="true" data-decimals="0"
                                class="form-control form-control-lg text-right font-weight-bold"
                                placeholder="@lang('sales::order.lines.total.0')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

</x-form-multiple>

<x-backend-form-controls
    submit="pos::pos.save"
    cancel="pos::pos.cancel" cancel-route="backend.pos" />
