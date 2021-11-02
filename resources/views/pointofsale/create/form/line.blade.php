@php
    if ($old) {
        $old['product'] = Product::find($old['product_id']);
        $old['variant'] = Variant::find($old['variant_id']);
    }
@endphp
<div class="col-1 d-flex align-items-center justify-content-center">
    <input type="hidden" name="lines[product_id][]" value="{{ $old['product_id'] ?? null }}" />
    <input type="hidden" name="lines[variant_id][]" value="{{ $old['variant_id'] ?? null }}" />

    <div class="position-relative d-flex align-items-center h-50px">
        <img src="{{ $old ? (
            $old['variant']?->images()->first()->url ??
            $old['product']?->images()->first()->url ??
            null) : null }}" class="img-fluid h-50px mh-50px" id="preview">
    </div>
    <x-form-input type="text" name="product-finder"
        class="{{ ($old ?? false) ? 'd-none' : null }}"
        placeholder="sales::order.lines.product_id.0" />
</div>

<div class="col-1 d-flex align-items-center justify-content-center">
    <span id="sku">{{ $old['variant']?->sku ?? $old['product']?->code ?? null }}</span>
</div>

<div class="col d-flex align-items-center justify-content-start">
    <span id="product">{{ $old['product']->name ?? null }}</span>
</div>

<div class="col-4">
    <div class="row h-100">
        <div class="col d-flex align-items-center justify-content-end">
            <x-form-input-group prepend="{{ pos_settings()->currency()->code }}" prepend-class="d-none d-xl-block" text-class="border-0 bg-transparent">
                <x-form-input name="lines[price][]" type="text" min="1" thousand tabindex="-1"
                    value="{{ $old['price'] ?? null }}"
                    data-decimals="{{ pos_settings()->currency()->decimals }}"
                    class="form-control text-right pr-2 border-0 bg-transparent" readonly />
                {{-- <span class="pr-2" id="price"></span> --}}
            </x-form-input-group>
        </div>
        <div class="col d-flex align-items-center justify-content-center">
            <x-form-input name="lines[quantity][]" type="number" min="1"
                value="{{ $old['quantity'] ?? null }}"
                class="form-control text-center pl-4 border-0 bg-transparent" />
            {{-- <span id="quantity"></span> --}}
        </div>
        <div class="col d-flex align-items-center justify-content-end font-weight-bold">
            <x-form-input-group prepend="{{ pos_settings()->currency()->code }}" prepend-class="d-none d-xl-block" text-class="border-0 bg-transparent font-weight-bold">
                <x-form-input name="lines[total][]" type="text" min="1" thousand tabindex="-1"
                value="{{ $old['total'] ?? null }}"
                    data-decimals="{{ pos_settings()->currency()->decimals }}"
                    class="form-control text-right pr-2 border-0 bg-transparent font-weight-bold" readonly />
                {{-- <span class="pr-2" id="total"></span> --}}
            </x-form-input-group>
        </div>
    </div>
</div>
