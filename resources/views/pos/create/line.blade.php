{{-- <div class="form-row my-2 order-line-container" @if ($selected === null && $old === null) id="new" @else data-used="true" @endif> --}}
    <div class="col-1 d-flex justify-content-center">
        <div class="position-relative d-flex align-items-center">
            <img src="" class="img-fluid h-50px" id="line_preview">
        </div>
    </div>
    <div class="col-9 col-xl-10 d-flex align-items-center">
        <div class="w-100">
            <div class="form-row">

                <div class="col-4">
{{--
                    <select name="lines[product_id][]" data-live-search="true"
                        @if ($selected !== null) required @endif
                        data-preview="#line_preview" data-preview-init="false"
                        value="{{ $old['product_id'] ?? $selected?->product_id ?? null }}"
                        class="form-control selectpicker"
                        placeholder="@lang('sales::order.lines.product_id._')">

                        <option value="" selected disabled hidden>@lang('sales::order.lines.product_id.0')</option>

                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                url="{{ asset($product->images->first()->url ?? 'backend-module/assets/images/default.jpg') }}"
                                @if ($product->id == ($old['product_id'] ?? $selected?->product_id ?? null)) selected @endif>{{ $product->name }}</option>
                        @endforeach
                    </select>
 --}}
                    <x-form-foreign name="lines[product_id][]"
                        :values="$products" data-live-search="true"
                        default="{{ $old['product_id'] ?? $selected?->product_id }}"
                        :required="$selected !== null"

                        {{-- show="code name" title="code" --}}
                        append="url:images.0.url??backend-module/assets/images/default.jpg"
                        data-preview="#line_preview" data-preview-init="false"
                        data-preview-url-prepend="{{ asset('') }}"

                        {{-- foreign="products" foreign-add-label="products-catalog::products.add" --}}

                        {{-- label="sales::order.lines.product_id.0" --}}
                        placeholder="sales::order.lines.product_id._"
                        {{-- helper="sales::order.lines.product_id.?" --}} />
                </div>

                <div class="col-4">
{{--
                    <select name="lines[variant_id][]"
                        data-filtered-by='[name="lines[product_id][]"]' data-filtered-using="product"
                        data-filtered-init="false"
                        value="{{ $old['variant_id'] ?? $selected?->variant_id ?? null }}"
                        class="form-control selectpicker"
                        placeholder="@lang('sales::order.lines.variant_id._')">

                        <option value="" selected disabled hidden>@lang('sales::order.lines.variant_id.0')</option>

                        @foreach($products->pluck('variants')->flatten() as $variant)
                            <option value="{{ $variant->id }}" data-product="{{ $variant->product_id }}"
                                @if ($variant->id == ($old['variant_id'] ?? $selected?->variant_id ?? null)) selected @endif>{{ $variant->sku }}</option>
                        @endforeach
                    </select>
 --}}
                    <x-form-foreign name="lines[variant_id][]"
                        :values="$products->pluck('variants')->flatten()" data-live-search="true"
                        default="{{ $old['variant_id'] ?? $selected?->variant_id }}"
                        :required="$selected !== null"

                        filtered-by='[name="lines[product_id][]"]' filtered-using="product"
                        data-filtered-init="false"

                        show="sku" {{-- title="code" --}}
                        {{-- append="url:images.0.url??backend-module/assets/images/default.jpg" --}}
                        {{-- data-preview="#line_preview" data-preview-init="false" --}}
                        {{-- data-preview-url-prepend="{{ asset('') }}" --}}

                        {{-- foreign="variants" foreign-add-label="products-catalog::variants.add" --}}

                        {{-- label="sales::order.lines.variant_id.0" --}}
                        placeholder="sales::order.lines.variant_id._"
                        {{-- helper="sales::order.lines.variant_id.?" --}} />
                </div>
                <div class="col-4">
                    <div class="input-group">
{{--
                        <input name="lines[price][]" type="number" min="0" thousand
                           value="{{ $old['price'] ?? $selected?->price ?? null }}" data-decimals="{{ isset($resource) ? $resource->currency->decimals : 0 }}"
                           class="form-control text-right"
                           placeholder="@lang('sales::order.lines.price.0')">
 --}}
                        <x-form-amount name="lines[price][]" min="1"
                            :required="$selected !== null"
                            data-decimals="{{ pos_settings()->currency()->decimals }}"
                            data-currency-by="[name='currency_id']" data-keep-id="true"
                            value="{{ $old['price'] ?? null }}"
                            class="text-right"
                            placeholder="sales::order.lines.price._" />
{{--
                        <input name="lines[quantity][]" type="number" min="1"
                           value="{{ $old['quantity'] ?? $selected?->quantity ?? null }}"
                           class="form-control text-center"
                           placeholder="@lang('sales::order.lines.quantity.0')">
 --}}
                        <x-form-input type="number" name="lines[quantity][]" min="1"
                            :required="$selected !== null"
                            value="{{ $old['quantity'] ?? null }}"
                            class="text-center"
                            placeholder="sales::order.lines.quantity._" />
{{--
                        <input name="lines[total][]" type="number" min="0" thousand readonly
                           value="{{ $old['total'] ?? $selected?->total ?? null }}" data-decimals="{{ isset($resource) ? $resource->currency->decimals : 0 }}"
                           class="form-control text-right font-weight-bold"
                           placeholder="@lang('sales::order.lines.total.0')">
 --}}
                        <x-form-amount name="lines[total][]" min="1" readonly tabindex="-1"
                            data-decimals="{{ pos_settings()->currency()->decimals }}"
                            value="{{ $old['total'] ?? null }}"
                            class="text-right font-weight-bold"
                            placeholder="sales::order.lines.total._" />
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-2 col-xl-1 d-flex justify-content-end align-items-center">
        <button type="button" class="btn btn-danger" tabindex="-1"
            data-action="delete"
            @if ($selected !== null)
            data-confirm="Eliminar Linea?"
            data-text="Esta seguro de eliminar la linea con el producto {{ $selected->product->name }}?"
            data-accept="Si, eliminar"
            @endif>X
        </button>
    </div>
{{-- </div> --}}
