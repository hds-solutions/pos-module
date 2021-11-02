<input type="hidden" name="customer_id" value="{{ $resource->partnerable->id ?? $customer->id }}">

<div class="row mb-2">
    <label class="col-3 h4 mb-0 d-flex align-items-center text-primary font-weight-bold">RUC</label>
    <div class="col">
        <h3 class="mb-0 text-dark" name="customer_ftid">{{ $resource->partnerable->ftid ?? $customer->ftid }}</h3>
    </div>
</div>

<div class="row mb-2">
    <label class="col-3 h4 mb-0 d-flex align-items-center text-primary font-weight-bold">Cliente</label>
    <div class="col">
        <h3 class="mb-0 text-dark" name="customer_name">{{ $resource->partnerable->full_name ?? $customer->full_name }}</h3>
    </div>
</div>
