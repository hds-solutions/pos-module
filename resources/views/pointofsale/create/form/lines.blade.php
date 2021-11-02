<header class="form-row bg-gray-400 p-1 text-dark">
    <div class="offset-1 col-1">SKU</div>
    <div class="col">@lang('sales::order.lines.product_id.0')</div>

    <div class="col-4">
        <div class="row">
            <div class="col text-right pr-4">Price</div>
            <div class="col">Quantity</div>
            <div class="col text-right pr-4">Total</div>
        </div>
    </div>

</header>

<section class="form-row flex-grow-1 pos-lines">
    <div class="col">
        <x-form-multiple name="lines" :values="[]" :selecteds="[]"
            grouped old-filter-fields="product_id,quantity" auto-add-lines="false"
            contents-view="pos::pointofsale.create.form.line" contents-size="xxl"
            row-class="px-1 py-0" data-type="pointofsale">

        </x-form-multiple>
    </div>
</section>
