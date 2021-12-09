<div class="col-10 col-lg-11">
    <x-backend-form-select :resource="$resource ?? null" name="payments[payment_type][]"
        :values="Payment::PAYMENT_TYPES" default="{{ $old['payment_type'] ?? Payment::PAYMENT_TYPE_Cash }}"

        label="payments::payment.payment_type.0"
        placeholder="payments::payment.payment_type._"
        {{-- helper="payments::payment.payment_type.?" --}}
        row-class="m-0">

        <x-backend-form-amount :resource="null" name="payments[payment_amount][]"
            currency="[name=currency_id]" data-keep-id secondary
            default="{{ $old['payment_amount'] ?? $selected?->pivot->payment_amount }}"

            placeholder="payments::payment.payment_amount._"
            {{-- helper="payments::payment.payment_amount.?" --}}
            class="text-right font-weight-bold" />

    </x-backend-form-select>

    <div class="form-row" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Cash }}">
        <div class="col">
            {{-- Cash --}}
            <x-form-foreign name="payments[cash_id][]"
                :values="$cashes" default="{{ $old['cash_id'] ?? null }}"
                {{-- :values="$customers->pluck('cashes')->flatten()" --}}

                show="#description"

                placeholder="payments::payment.cash_id._"
                {{-- helper="payments::payment.cash_id.?" --}} />
        </div>
    </div>

    <div class="form-row mt-2" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Credit }}">
        <div class="col offset-2">
            {{-- Credit --}}
            <x-form-input type="number" :resource="null" name="payments[interest][]"
                value="{{ $old['interest'] ?? 0 }}"
                label="payments::payment.interest.0"
                placeholder="({{ __('optional') }}) {{ __('payments::payment.interest._') }}" />
            <x-form-input type="number" :resource="null" name="payments[dues][]"
                value="{{ $old['dues'] ?? 1 }}"
                label="payments::payment.dues.0"
                placeholder="({{ __('optional') }}) {{ __('payments::payment.dues._') }}" />
        </div>
    </div>

    <div class="form-row mt-2" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Check }}">
        <div class="col offset-2">
            {{-- Check --}}
            <x-form-input type="text" :resource="null" name="payments[bank_name][]"
                value="{{ $old['bank_name'] ?? null }}"
                placeholder="payments::payment.bank_name._" />
            <x-form-input type="text" :resource="null" name="payments[bank_account][]"
                value="{{ $old['bank_account'] ?? null }}"
                placeholder="payments::payment.bank_account._" />
            <x-form-input type="text" :resource="null" name="payments[account_holder][]"
                value="{{ $old['account_holder'] ?? null }}"
                placeholder="payments::payment.account_holder._" />
            <x-form-input type="text" :resource="null" name="payments[check_number][]"
                value="{{ $old['check_number'] ?? null }}"
                placeholder="payments::payment.check_number._" />
            <x-form-datetime name="payments[due_date][]"
                value="{{ $old['due_date'] ?? null }}"
                placeholder="payments::payment.due_date._" />
        </div>
    </div>

    <div class="form-row mt-2" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_CreditNote }}">
        <div class="col offset-2">
            {{-- CreditNote --}}
            <x-form-foreign name="payments[credit_note_id][]"
                filtered-by="[name=partnerable_id]" filtered-using="customer"
                data-filtered-keep-id="true" data-filtered-init="false"
                :values="$creditNotes" default="{{ $old['credit_note_id'] ?? null }}"
                {{-- :values="$customers->pluck('creditNotes')->flatten()" --}}

                show="#document_number payment_amount" append="customer:partnerable_id"

                placeholder="payments::payment.credit_note_id._"
                {{-- helper="payments::payment.credit_note_id.?" --}} />
        </div>
    </div>
    <div class="form-row mt-2" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Promissory }}">
        <div class="col offset-2">
            TODO: Promissory
        </div>
    </div>
    <div class="form-row mt-2" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Card }}">
        <div class="col offset-2">
            <x-form-input name="payments[card_holder][]"
                value="{{ $old['card_holder'] ?? null }}"
                placeholder="payments::payment.card_holder._" />
            <x-form-input name="payments[card_number][]"
                value="{{ $old['card_number'] ?? null }}"
                placeholder="payments::payment.card_number._" />
            <x-form-boolean name="payments[is_credit][]"
                value="{{ $old['is_credit'] ?? false }}"
                placeholder="payments::payment.is_credit._"
                helper="payments::payment.is_credit.?" />
        </div>
    </div>
</div>

<div class="col-2 col-lg-1 d-flex justify-content-end align-items-center">
    <button type="button" class="btn btn-danger"
        data-action="delete" tabindex="-1"
        @if ($selected !== null)
        data-confirm="Eliminar Linea?"
        data-text="Esta seguro de eliminar la linea con el producto {{ $selected->payment_amount }}?"
        data-accept="Si, eliminar"
        @endif>X
    </button>
</div>
