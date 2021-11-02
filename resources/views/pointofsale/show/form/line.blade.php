<div class="col-1 d-flex align-items-center justify-content-center">
    <div class="position-relative d-flex align-items-center h-50px">
        <img src="{{ asset($line->variant->images->first()->url ?? $line->product->images->first()->url ?? 'backend-module/assets/images/default.jpg') }}"
            class="img-fluid mh-50px" id="preview">
    </div>
</div>

<div class="col-2 d-flex align-items-center justify-content-center">
    <span id="sku">{{ $line->variant->sku ?? $line->product->code }}</span>
</div>

<div class="col d-flex align-items-center justify-content-start">
    <span id="product">{{ $line->product->name }}</span>
</div>

<div class="col-3">
    <div class="row h-100">
        <div class="col d-none d--flex align-items-center justify-content-end">
            <x-form-input-group {{-- prepend="{{ $resource->currency->code }}" prepend-class="d-none d-xl-block" text-class="border-0 bg-transparent" --}}>
                <x-form-input name="lines[price][]" type="text" thousand tabindex="-1"
                    data-decimals="{{ $resource->currency->decimals }}" value="{{ $line->price_invoiced }}"
                    class="form-control text-right pr-2 border-0 bg-transparent" readonly />
                {{-- <span class="pr-2" id="price"></span> --}}
            </x-form-input-group>
        </div>
        <div class="col d-flex align-items-center justify-content-center">
            <x-form-input name="lines[quantity][]" type="text"
                value="{{ $line->quantity_invoiced }}"
                class="form-control text-center pl-4 border-0 bg-transparent" readonly />
            {{-- <span id="quantity"></span> --}}
        </div>
        <div class="col d-flex align-items-center justify-content-end font-weight-bold">
            <x-form-input-group {{-- prepend="{{ $resource->currency->code }}" prepend-class="d-none d-xl-block" text-class="border-0 bg-transparent font-weight-bold" --}}>
                <x-form-input name="lines[total][]" type="text" thousand tabindex="-1"
                    data-decimals="{{ $resource->currency->decimals }}" value="{{ $line->total }}"
                    class="form-control text-right pr-2 border-0 bg-transparent font-weight-bold" readonly />
                {{-- <span class="pr-2" id="total"></span> --}}
            </x-form-input-group>
        </div>
    </div>
</div>
