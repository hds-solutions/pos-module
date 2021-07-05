<div class="form-row mt-1 pt-1 payment-container" @if ($selected === null && $old === null) id="new" @else data-used="true" @endif>

    <div class="col-10 col-lg-11">
        <x-backend-form-select :resource="$resource ?? null" name="payments[payment_type][]"
            :Payment::PAYMENT_TYPES"
            default="{{ $old['payment_type'] ?? Payment::PAYMENT_TYPE_Cash }}"

            label="{{ __('payments::payment.payment_type.0') }}"
            placeholder="{{ __('payments::payment.payment_type._') }}"
            {{-- helper="{{ __('payments::payment.payment_type.?') }}" --}}
            class="mb-0">

            <x-backend-form-amount :resource="null" name="payments[payment_amount][]"
                currency="[name=currency_id]" data-keep-id secondary
                default="{{ $old['payment_amount'] ?? null }}"

                label="{{ __('payments::payment.payment_amount.0') }}"
                placeholder="{{ __('payments::payment.payment_amount._') }}"
                {{-- helper="{{ __('payments::payment.payment_amount.?') }}" --}}
                class="mb-0" field-class="font-weight-bold" />

        </x-backend-form-select>

        <div class="form-row" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Cash }}">
            <div class="col">
                {{-- Cash --}}
            </div>
        </div>

        <div class="form-row" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Credit }}">
            <div class="col">
                {{-- Credit --}}
                <x-backend-form-number :resource="null" name="payments[interest][]"
                    default="{{ $old['interest'] ?? 0 }}"
                    label="{{ __('payments::payment.interest.0') }}"
                    placeholder="({{ __('optional') }}) {{ __('payments::payment.interest._') }}"
                    class="my-1"/>
                <x-backend-form-number :resource="null" name="payments[dues][]"
                    default="{{ $old['dues'] ?? 1 }}"
                    label="{{ __('payments::payment.dues.0') }}"
                    placeholder="({{ __('optional') }}) {{ __('payments::payment.dues._') }}"
                    class="mt-1 mb-0"/>
            </div>
        </div>

        <div class="form-row" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Check }}">
            <div class="col">
                {{-- Check --}}
                <x-backend-form-text :resource="null" name="payments[bank_name][]"
                    default="{{ $old['bank_name'] ?? null }}"
                    label="{{ __('payments::payment.bank_name.0') }}"
                    placeholder="{{ __('payments::payment.bank_name._') }}"
                    class="my-1" />
                <x-backend-form-text :resource="null" name="payments[bank_account][]"
                    default="{{ $old['bank_account'] ?? null }}"
                    label="{{ __('payments::payment.bank_account.0') }}"
                    placeholder="{{ __('payments::payment.bank_account._') }}"
                    class="my-1" />
                <x-backend-form-text :resource="null" name="payments[account_holder][]"
                    default="{{ $old['account_holder'] ?? null }}"
                    label="{{ __('payments::payment.account_holder.0') }}"
                    placeholder="{{ __('payments::payment.account_holder._') }}"
                    class="my-1" />
                <x-backend-form-text :resource="null" name="payments[check_number][]"
                    default="{{ $old['check_number'] ?? null }}"
                    label="{{ __('payments::payment.check_number.0') }}"
                    placeholder="{{ __('payments::payment.check_number._') }}"
                    class="my-1" />
                <x-backend-form-date :resource="null" name="payments[due_date][]"
                    default="{{ $old['due_date'] ?? null }}"
                    label="{{ __('payments::payment.due_date.0') }}"
                    placeholder="{{ __('payments::payment.due_date._') }}"
                    class="mt-1 mb-0" />
            </div>
        </div>

        <div class="form-row" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_CreditNote }}">
            <div class="col">
                {{-- CreditNote --}}
                <x-backend-form-foreign :resource="null" name="payments[credit_note_id][]"
                    {{-- filtered-by="[name=customer_id]" filtered-using="customer" data-filtered-keep-id="true" --}}
                    foreign="" :values="$creditNotes"
                    default="{{ $old['credit_note_id'] ?? null }}"
                    {{-- :values="$customers->pluck('creditNotes')->flatten()" --}}

                    show="payment_amount" append="customer:partnerable_id"

                    label="{{ __('payments::payment.credit_note_id.0') }}"
                    placeholder="{{ __('payments::payment.credit_note_id._') }}"
                    {{-- helper="{{ __('payments::payment.credit_note_id.?') }}" --}}
                    class="mt-1 mb-0" />
            </div>
        </div>
        <div class="form-row" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Promissory }}">
            <div class="col">
                TODO: Promissory
            </div>
        </div>
        <div class="form-row" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Card }}">
            <div class="col">
                <x-backend-form-text :resource="null" name="payments[card_holder][]"
                    default="{{ $old['card_holder'] ?? null }}"
                    label="{{ __('payments::payment.card_holder.0') }}"
                    placeholder="{{ __('payments::payment.card_holder._') }}"
                    class="my-1" />
                <x-backend-form-text :resource="null" name="payments[card_number][]"
                    default="{{ $old['card_number'] ?? null }}"
                    label="{{ __('payments::payment.card_number.0') }}"
                    placeholder="{{ __('payments::payment.card_number._') }}"
                    class="my-1" />
                <x-backend-form-boolean :resource="null" name="payments[is_credit][]"
                    default="{{ $old['is_credit'] ?? false }}"
                    label="{{ __('payments::payment.is_credit.0') }}"
                    placeholder="{{ __('payments::payment.is_credit._') }}"
                    helper="{{ __('payments::payment.is_credit.?') }}"
                    class="mt-1 mb-0" />
            </div>
        </div>
    </div>

    <div class="col-2 col-lg-1 d-flex justify-content-end align-items-center">
        <button type="button" class="btn btn-danger"
            data-action="delete"
            @if ($selected !== null)
            data-confirm="Eliminar medio de pago?"
            data-text="Esta seguro de eliminar el medio de pago?"
            data-accept="Si, eliminar"
            @endif>X
        </button>
    </div>

</div>
