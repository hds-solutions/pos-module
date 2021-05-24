@include('backend::components.errors')

<x-backend-form-foreign :resource="null" name="currency_id" required
    foreign="currencies" :values="backend()->currencies()" default="{{ pos_settings()->currency()->id }}"

    {{-- foreign-add-label="{{ __('customers::customers.add') }}" --}}
    {{-- show="business_name" --}}

    label="{{ __('pos::pos.currency_id.0') }}"
    placeholder="{{ __('pos::pos.currency_id._') }}"
    {{-- helper="{{ __('pos::pos.currency_id.?') }}" --}}
    class="d-none" />

<div class="form-row form-group mb-2">
    {{-- <label class="col-12 col-md-3 col-lg-2 control-label mt-2 mb-3">@lang('pos::pos.payments.0')</label> --}}
    {{-- <div class="col-12 col-md-9 col-lg-10"> --}}
    <div class="col-12">
        <div class="card bg-light">

            <div class="card-body pt-0 pb-2" data-multiple=".payment-container" data-template="#new">

                @php $old_lines = array_group(old('payments') ?? []); @endphp

                @foreach($old_lines as $old)
                    {{-- ignore empty --}}
                    @if ( ($old['payment_type'] ?? null) === null &&
                        ($old['payment_amount'] ?? null) === null)
                        @continue
                    @endif
                    @include('pos::pos.show.payment', [
                        'creditNotes'   => $resource->partnerable->creditNotes,
                        'selected'      => null,
                        'old'           => $old,
                    ])
                @endforeach

                @include('pos::pos.show.payment', [
                    'creditNotes'   => $resource->partnerable->creditNotes,
                    'selected'      => null,
                    'old'           => null,
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
