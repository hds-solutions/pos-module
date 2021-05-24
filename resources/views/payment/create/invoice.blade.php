<div class="form-row my-2 receipment-invoice-container" @if ($selected === null && $old === null) id="new" @else data-used="true" @endif>
    <div class="col-1 d-flex justify-content-center">
        <div class="position-relative d-flex align-items-center">
            <img src="" class="img-fluid mh-50px" id="line_preview">
        </div>
    </div>
    <div class="col-9 col-xl-10 d-flex align-items-center">

        <div class="w-100">
            <div class="form-row">

                <div class="col-4">
                    <select name="invoices[invoice_id][]" data-live-search="true"
                        @if ($selected !== null) required @endif
                        data-filtered-by='[name="customer_id"]' data-filtered-using="customer"
                        data-preview="#line_preview" data-preview-init="false" data-filtered-keep-id="true"
                        value="{{ $old['invoice_id'] ?? $selected?->invoice_id ?? null }}"
                        class="form-control selectpicker"
                        placeholder="@lang('sales::order.lines.invoice_id._')">

                        <option value="" selected disabled hidden>@lang('sales::order.lines.invoice_id.0')</option>

                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}" data-customer="{{ $invoice->partnerable_id }}"
                                @if ($invoice->id == ($old['invoice_id'] ?? $selected?->invoice_id ?? null)) selected @endif>
                                [{{ $invoice->document_number }}] {{ amount($invoice->total, $invoice->currency) }}
                            </option>
                        @endforeach
                    </select>
                </div>

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
