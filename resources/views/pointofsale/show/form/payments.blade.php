<header class="form-row bg-gray-400 p-1 text-dark">
    <div class="col">Pago</div>
    <div class="col">Monto</div>
</header>

<section class="form-row flex-grow-1 pos-payments">
    <div class="col">
        <x-form-multiple name="payments" :selecteds="[]"
            :values="$resource->partnerable->creditNotes" values-as="creditNotes"
            :extra="$banks" extra-as="banks"

            grouped old-filter-fields="payment_type,payment_amount" auto-add-lines="false"
            contents-view="pos::pointofsale.show.form.payment" contents-size="xxl"

            data-type="pointofsale" container-class="min-h-50px" />
    </div>
</section>
