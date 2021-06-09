@include('backend::components.errors')

<x-backend-form-foreign :resource="$resource ?? null" name="currency_id" required
    foreign="currencies" :values="backend()->currencies()"
    append="decimals"

    foreign-add-label="{{ __('cash::currencies.add') }}"

    label="{{ __('pos::pos.currency_id.0') }}"
    placeholder="{{ __('pos::pos.currency_id._') }}"
    {{-- helper="{{ __('pos::pos.currency_id.?') }}" --}} />

<x-backend-form-foreign :resource="$resource ?? null" name="branch_id" required
    foreign="branches" :values="$branches"

    foreign-add-label="{{ __('backend::branches.add') }}"

    label="{{ __('pos::pos.branch_id.0') }}"
    placeholder="{{ __('pos::pos.branch_id._') }}"
    {{-- helper="{{ __('pos::pos.branch_id.?') }}" --}} />

<x-backend-form-foreign :resource="$resource ?? null" name="warehouse_id" required
    foreign="warehouses" :values="$branches->pluck('warehouses')->flatten()"
    filtered-by="[name=branch_id]" filtered-using="branch"

    foreign-add-label="{{ __('inventory::warehouses.add') }}"

    label="{{ __('pos::pos.warehouse_id.0') }}"
    placeholder="{{ __('pos::pos.warehouse_id._') }}"
    {{-- helper="{{ __('pos::pos.warehouse_id.?') }}" --}} />

<x-backend-form-foreign :resource="$resource ?? null" name="cash_book_id" required
    foreign="cash_books" :values="$cashBooks" foreign-add-label="{{ __('cash::cash_books.add') }}"

    label="{{ __('cash::cash.cash_book_id.0') }}"
    placeholder="{{ __('cash::cash.cash_book_id._') }}"
    {{-- helper="{{ __('cash::cash.cash_book_id.?') }}" --}} />

<x-backend-form-controls
    submit="pos::pos.save"
    cancel="pos::pos.cancel" cancel-route="backend.pos" />
