<div class="row mb-2">
    <label class="col-3 h4 mb-0 d-flex align-items-center text-primary font-weight-bold">Fecha</label>
    <div class="col">
        <h3 class="mb-0 text-dark" id="transacted-at">{{ pretty_date($resource->transacted_at ?? now(), true) }}</h3>
    </div>
</div>

<div class="row mb-2">
    <label class="col-3 h4 mb-0 d-flex align-items-center text-primary font-weight-bold">Factura</label>
    <div class="col">
        {{-- <input type="hidden" name="document_number" value="{{ $resource->document_number ?? $highs['document_number'] }}"> --}}
        <h3 class="mb-0 text-dark">{{ (isset($resource) && get_class($resource) == get_class(new Invoice) ? $resource->document_number : null) ?? pos_settings()->prepend().str_pad('', pos_settings()->stamping()->length, 'x') }}
            <small class="text-gray-400">[{{ (isset($resource) && get_class($resource) == get_class(new Invoice) ? $resource->stamping->document_number : null) ?? pos_settings()->stamping()->document_number }}]</small>
        </h3>
    </div>
</div>
