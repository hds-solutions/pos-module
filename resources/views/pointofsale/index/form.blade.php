@include('backend::components.errors')

<div class="row">
    @foreach($pos as $setting)

        <label class="col-6">
            <input type="radio" name="pos" class="card-input-element d-none" value="{{ $setting->id }}" required />
            <div class="card cursor-pointer">
                <div class="card-body">
                    <h5 class="card-title">{{ $setting->name }}</h5>
                    <h6 class="card-subtitle mb-2">{{ currency($setting->currency_id)->name }}</h6>
                    <p class="card-text text-dark mb-0">{{ $setting->warehouse->name }} <small>[{{ $setting->branch->name }}]</small></p>
                    <p class="card-text text-dark mb-0">{{ $setting->cashBook->name }}</p>
                    <p class="card-text text-dark mb-0">{{ $setting->stamping->document_number }}</p>
                    <p class="card-text text-dark mb-0">{{ $setting->prepend }}{{ str_pad('', $setting->length, 'x') }}</p>
                </div>
            </div>
        </label>

    @endforeach
</div>

<x-backend-form-controls
    submit="pos::pointofsale.save"
    cancel="pos::pointofsale.cancel" cancel-route="backend.pointofsale" />
