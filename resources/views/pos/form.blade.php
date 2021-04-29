@include('backend::components.errors')

{{-- <x-backend-form-foreign :resource="$resource ?? null" name="cash_id" required
    foreign="payments" :values="$payments"  default="123"
    request="payment"

    foreign-add-label="cash::cashes.add"
    show="cashBook.name"

    label="cash::cash_line.cash_id.0"
    placeholder="cash::cash_line.cash_id._"
    helper="{{ __('cash::cash_line.cash_id.?') }}" /> --}}

<x-backend-form-foreign :resource="$resource ?? null" name="customer_id" required
    foreign="customers" :values="$customers"

    foreign-add-label="{{ __('pos::customers.add') }}"
    show="business_name"

    label="{{ __('pos::order.customer_id.0') }}"
    placeholder="{{ __('pos::order.customer_id._') }}"
    {{-- helper="{{ __('pos::inventory.branch_id.?') }}" --}} />

{{-- TODO ADDRESSES--}}
{{-- <x-backend-form-foreign :resource="$resource ?? null" name="warehouse_id" required
    foreign="warehouses" :values="$branches->pluck('warehouses')->flatten()"
    filtered-by="[name=branch_id]" filtered-using="branch"

    foreign-add-label="{{ __('pos::warehouses.add') }}"

    label="{{ __('pos::inventory.warehouse_id.0') }}"
    placeholder="{{ __('pos::inventory.warehouse_id._') }}"
    helper="{{ __('pos::product.warehouse_id.?') }}" /> --}}

<x-backend-form-foreign :resource="$resource ?? null" name="currency_id" required
    foreign="currencies" :values="backend()->currencies()"

    foreign-add-label="{{ __('pos::currencies.add') }}"

    label="{{ __('pos::order.currency_id.0') }}"
    placeholder="{{ __('pos::order.currency_id._') }}"
    {{-- helper="{{ __('pos::inventory.branch_id.?') }}" --}} />

<x-backend-form-foreign :resource="$resource ?? null" name="branch_id" required
    foreign="branches" :values="$branches"

    foreign-add-label="{{ __('inventory::branches.add') }}"

    label="{{ __('inventory::inventory.branch_id.0') }}"
    placeholder="{{ __('inventory::inventory.branch_id._') }}"
    {{-- helper="{{ __('inventory::inventory.branch_id.?') }}" --}} />

<x-backend-form-foreign :resource="$resource ?? null" name="warehouse_id" required
    foreign="warehouses" :values="$branches->pluck('warehouses')->flatten()"
    filtered-by="[name=branch_id]" filtered-using="branch"

    foreign-add-label="{{ __('inventory::warehouses.add') }}"

    label="{{ __('inventory::inventory.warehouse_id.0') }}"
    placeholder="{{ __('inventory::inventory.warehouse_id._') }}"
    {{-- helper="{{ __('inventory::product.warehouse_id.?') }}" --}} />

<div class="form-row form-group mb-0">
    <label class="col-12 col-md-3 col-lg-2 control-label mt-2 mb-3">@lang('pos::inventory.lines.0')</label>
    <div class="col-12 col-md-9 col-lg-10" data-multiple=".order-line-container" data-template="#new">
        @php $old = old('lines') ?? []; @endphp
        {{-- add product current lines --}}
        {{-- @if (isset($resource)) @foreach($resource->lines as $idx => $selected)
            @include('pos::pos.line', [
                'products'  => $products,
                'selected'  => $selected,
                'old'       => $old[$idx] ?? null,
            ])
            @php unset($old[$idx]); @endphp
        @endforeach @endif --}}

        {{-- add new added --}}
        @foreach($old as $selected)
            @include('pos::pos.line', [
                'products'  => $products,
                'selected'  => 0,
                'old'       => $selected,
            ])
        @endforeach

        {{-- add empty for adding new lines --}}
        @include('pos::pos.line', [
            'products'  => $products,
            'selected'  => null,
            'old'       => null,
        ])
    </div>
</div>

<div class="form-row form-group mb-0">
    <label class="col-12 col-md-3 col-lg-2 control-label mt-2 mb-3">@lang('pos::inventory.payments.0')</label>
    <div class="col-12 col-md-9 col-lg-10" data-multiple=".payment-container" data-template="#new">
        @php $old = old('payments') ?? []; @endphp
        {{-- add product current lines --}}
        {{-- @if (isset($resource)) @foreach($resource->lines as $idx => $selected)
            @include('pos::orders.payment', [
                'products'  => $products,
                'selected'  => $selected,
                'old'       => $old[$idx] ?? null,
            ])
            @php unset($old[$idx]); @endphp
        @endforeach @endif --}}

        {{-- add new added --}}
        @foreach($old as $selected)
            @include('pos::pos.payment', [
                'products'  => $products,
                'selected'  => 0,
                'old'       => $selected,
            ])
        @endforeach

        {{-- add empty for adding new lines --}}
        @include('pos::pos.payment', [
            'products'  => $products,
            'selected'  => null,
            'old'       => null,
        ])
    </div>
</div>

<x-backend-form-controls
    submit="pos::inventories.save"
    cancel="pos::inventories.cancel" cancel-route="backend.pos" />
