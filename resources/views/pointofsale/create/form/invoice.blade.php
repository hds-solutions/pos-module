<div class="row mb-2">
    <label class="col-3 h4 mb-0 d-flex align-items-center text-primary font-weight-bold">Fecha</label>
    <div class="col">
        <h3 class="mb-0 text-dark">{{ pretty_date($resource->transacted_at ?? now(), true) }}</h3>
    </div>
</div>

<div class="row mb-2">
    <label class="col-3 h4 mb-0 d-flex align-items-center text-primary font-weight-bold">Factura</label>
    <div class="col">
        {{-- <input type="hidden" name="document_number" value="{{ $resource->document_number ?? $highs['document_number'] }}"> --}}
        <h3 class="mb-0 text-dark">{{ $resource->document_number ?? pos_settings()->prepend().'xxxxxx' }}
            <small class="text-gray-400">[{{ $resource->stamping->document_number ?? pos_settings()->stamping()->document_number }}]</small>
        </h3>
    </div>
</div>

