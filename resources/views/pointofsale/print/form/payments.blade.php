<header class="form-row bg-gray-400 p-1 text-dark">
    <div class="col">Pago</div>
    <div class="col">Monto</div>
</header>

<section class="form-row flex-grow-1 pos-payments">
    <div class="col">
        <x-form-row class="px-1 py-0">
            <div class="col">
                <div class="row">
                    <div class="col">

                        @foreach($resource->receipments->pluck('payments')->flatten() as $payment)
                            <div class="form-row">
                                @include('pos::pointofsale.print.form.payment', compact('resource', 'payment'))
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </x-form-row>
    </div>
</section>
