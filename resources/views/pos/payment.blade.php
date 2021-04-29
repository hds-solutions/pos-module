<div class="form-row mb-3 payment-container" @if ($selected === null) id="new" @else data-used="true" @endif>
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body py-2">
                <div class="form-row">
                    <div class="col-1 d-flex justify-content-center">
                        <div class="position-relative d-flex align-items-center">
                            <img src=""
                                 class="img-fluid mh-75px" id="line_preview">
                        </div>
                    </div>

                    <div class="col-9 col-xl-10">
                        <x-backend-form-select :resource="$resource ?? null" name="cash_type" required
                            :values="\HDSSolutions\Finpar\Models\Payment::PAYMENT_TYPES"
                            default="{{ \HDSSolutions\Finpar\Models\Payment::PAYMENT_TYPE_Cash }}"

                            label="{{ __('payment::payment.0') }}"
                            placeholder="{{ __('payment::payment._') }}"
                            helper="{{ __('payment::payment.?') }}"  />

                        <div class="form-row" data-only="cash_type=123">
                            <div class="col-4 d-flex align-items-center mb-2">
                                cash
                            </div>
                        </div>
                        <div class="form-row" data-only="cash_type=123">
                            <div class="col-4 d-flex align-items-center mb-2">
                                cash
                            </div>
                        </div>
                        <div class="form-row" data-only="cash_type=123">
                            <div class="col-4 d-flex align-items-center mb-2">
                                cash
                            </div>
                        </div>
                        <div class="form-row" data-only="cash_type=123">
                            <div class="col-4 d-flex align-items-center mb-2">
                                cash
                            </div>
                        </div>
                    </div>
                    <div class="col-2 col-xl-1 d-flex justify-content-end align-items-center">
                        <button type="button" class="btn btn-danger"
                            data-action="delete"
                            @if ($selected !== null)
                            data-confirm="Eliminar Linea?"
                            data-text="Esta seguro de eliminar la linea con el producto {{ $selected->product->name }}?"
                            data-accept="Si, eliminar"
                            @endif>X
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
