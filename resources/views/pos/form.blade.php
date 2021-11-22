@include('backend::components.errors')

<x-backend-form-text :resource="$resource ?? null" name="name" required
    label="pos::pos.name.0"
    placeholder="pos::pos.name._" />

<x-backend-form-foreign :resource="$resource ?? null" name="currency_id" required
    :values="backend()->currencies()"

    foreign="currencies" foreign-add-label="cash::currencies.add"
    data-live-search="true"

    label="pos::pos.currency_id.0"
    placeholder="pos::pos.currency_id._"
    {{-- helper="pos::pos.currency_id.?" --}} />

<x-backend-form-foreign :resource="$resource ?? null" name="branch_id" required
    :values="backend()->company()->branches"

    foreign="branches" foreign-add-label="backend::branches.add"
    data-live-search="true"

    label="pos::pos.warehouse_id.0"
    placeholder="pos::pos.branch_id._"
    helper="pos::pos.warehouse_id.?">

    <x-backend-form-foreign :resource="$resource ?? null" name="warehouse_id" required secondary
        :values="backend()->company()->branches->pluck('warehouses')->flatten()"

        foreign="warehouses" foreign-add-label="inventory::warehouses.add"
        filtered-by="[name=branch_id]" filtered-using="branch" append="branch:branch_id"
        data-live-search="true"

        label="pos::pos.warehouse_id.0"
        placeholder="pos::pos.warehouse_id._"
        {{-- helper="pos::pos.warehouse_id.?" --}} />

</x-backend-form-foreign>

<x-backend-form-foreign :resource="$resource ?? null" name="stamping_id" required
    :values="$stampings" show="document_number" data-show-subtext="true"
    subtext="valid_from_pretty - valid_until_pretty"

    foreign="stampings" foreign-add-label="cash::stampings.add"
    data-live-search="true"

    label="pos::pos.stamping_id.0"
    placeholder="pos::pos.stamping_id._"
    {{-- helper="pos::pos.stamping_id.?" --}} />

<x-backend-form-text :resource="$resource ?? null" name="prepend"
    label="pos::pos.prepend.0"
    placeholder="pos::pos.prepend.optional"
    helper="pos::pos.prepend.?" />

<x-backend-form-foreign :resource="$resource ?? null" name="cash_book_id" required
    :values="backend()->cashBooks()"

    foreign="cash_books" foreign-add-label="cash::cash_books.add"
    data-live-search="true"

    label="pos::pos.cash_book_id.0"
    placeholder="pos::pos.cash_book_id._"
    helper="pos::pos.cash_book_id.?" />

<x-backend-form-foreign :resource="$resource ?? null" name="customer_id" required
    :values="$customers"

    foreign="customers" foreign-add-label="customers::customers.add"
    data-live-search="true" show="full_name"

    label="pos::pos.customer_id.0"
    placeholder="pos::pos.customer_id._"
    helper="pos::pos.customer_id.?" />

<x-backend-form-multiple name="employees"
    :values="$employees" :selecteds="isset($resource) ? $resource->employees : []"
    contents-view="pos::pos.form.employee" data-type="pos"
    label="pos::pos.employees.0"
    helper="pos::pos.employees.?" />

<x-backend-form-controls
    submit="pos::pos.save"
    cancel="pos::pos.cancel" cancel-route="backend.pos" />
