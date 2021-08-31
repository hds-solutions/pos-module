<div class="col-10 col-lg-11">
    <x-backend-form-select :resource="$resource ?? null" name="payments[payment_type][]"
        :values="Payment::PAYMENT_TYPES" default="{{ $old['payment_type'] ?? Payment::PAYMENT_TYPE_Cash }}"

        label="payments::payment.payment_type.0"
        placeholder="payments::payment.payment_type._"
        {{-- helper="payments::payment.payment_type.?" --}}
        row-class="m-0">

        <x-backend-form-amount :resource="null" name="payments[payment_amount][]"
            currency="[name=currency_id]" data-keep-id secondary
            default="{{ $old['payment_amount'] ?? null }}"

            placeholder="payments::payment.payment_amount._"
            {{-- helper="payments::payment.payment_amount.?" --}}
            class="font-weight-bold" />

    </x-backend-form-select>

    <div class="form-row" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Cash }}">
        <div class="col">
            {{-- Cash --}}
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
            <x-form-foreign name="payments[bank_id][]"
                :values="$banks ?? []" default="{{ $old['bank_id'] ?? null }}"
                foreign="banks" foreign-add-label="banking::banks.add"

                placeholder="payments::check.bank_id._"
                {{-- helper="payments::payment.bank_id.?" --}} />
            {{-- <x-form-input type="text" :resource="null" name="payments[bank_name][]"
                default="{{ $old['bank_name'] ?? null }}"
                placeholder="payments::payment.bank_name._" /> --}}
            {{-- <x-form-input type="text" :resource="null" name="payments[bank_account][]"
                default="{{ $old['bank_account'] ?? null }}"
                placeholder="payments::payment.bank_account._" /> --}}
            <x-form-input type="text" :resource="null" name="payments[account_holder][]"
                default="{{ $old['account_holder'] ?? null }}"
                placeholder="payments::check.account_holder._" />
            <x-form-input type="text" :resource="null" name="payments[check_number][]"
                default="{{ $old['check_number'] ?? null }}"
                placeholder="payments::check.document_number._" />
            <x-form-date name="payments[due_date][]"
                default="{{ $old['due_date'] ?? null }}"
                placeholder="payments::check.due_date._" />
        </div>
    </div>

    <div class="form-row mt-2" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_CreditNote }}">
        <div class="col offset-2">
            {{-- CreditNote --}}
            <x-form-foreign name="payments[credit_note_id][]"
                {{-- filtered-by="[name=customer_id]" filtered-using="customer" data-filtered-keep-id="true" --}}
                :values="$creditNotes" default="{{ $old['credit_note_id'] ?? null }}"
                {{-- :values="$customers->pluck('creditNotes')->flatten()" --}}

                show="payment_amount" append="customer:partnerable_id"

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
                default="{{ $old['card_holder'] ?? null }}"
                placeholder="payments::payment.card_holder._" />
            <x-form-input name="payments[card_number][]"
                default="{{ $old['card_number'] ?? null }}"
                placeholder="payments::payment.card_number._" />
            <x-form-boolean name="payments[is_credit][]"
                default="{{ $old['is_credit'] ?? false }}"
                placeholder="payments::payment.is_credit._"
                helper="payments::payment.is_credit.?" />
        </div>
    </div>
</div>

<div class="col-2 col-lg-1 d-flex justify-content-end align-items-center">
    <button type="button" class="btn btn-danger"
        data-action="delete">X
    </button>
</div>