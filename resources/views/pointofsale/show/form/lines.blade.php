<header class="form-row bg-gray-400 p-1 text-dark">
    <div class="offset-1 col-2">SKU</div>
    <div class="col">@lang('sales::order.lines.product_id.0')</div>

    <div class="col-3">
        <div class="row">
            {{-- <div class="col text-right pr-4">Price</div> --}}
            <div class="col">Quantity</div>
            <div class="col text-right pr-4">Total</div>
        </div>
    </div>

</header>

<section class="form-row flex-grow-1 pos-lines">
    <div class="col">
        <x-form-row class="px-1 py-0">
            <div class="col">
                <div class="row">
                    <div class="col">

                        @foreach($resource->lines as $line)
                            <div class="form-row">
                                @include('pos::pointofsale.show.form.line', compact('resource', 'line'))
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </x-form-row>
    </div>
</section>
