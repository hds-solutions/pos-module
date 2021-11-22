<div class="col-12 d-flex flex-column">
    <div class="form-row min-h-50px">
        <div class="col-5 d-flex align-items-center">
            <x-form-select name="payments[payment_type][]"
                :values="Payment::PAYMENT_TYPES" default="{{ $old['payment_type'] ?? Payment::PAYMENT_TYPE_Cash }}"

                placeholder="payments::payment.payment_type._"
                {{-- helper="payments::payment.payment_type.?" --}} />
        </div>

        <div class="col-5 d-flex align-items-center">
            <x-form-amount name="payments[payment_amount][]"
                default="{{ $old['payment_amount'] ?? null }}" data-decimals="0"

                placeholder="payments::payment.payment_amount._"
                {{-- helper="payments::payment.payment_amount.?" --}}
                class="text-right" />
        </div>

        <div class="col-2 d-flex justify-content-end align-items-center">
            <button type="button" class="btn btn-danger" tabindex="-1"
                data-action="delete">X
            </button>
        </div>
    </div>

    <div class="form-row">
        <div class="col-12">
            <div class="form-row" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Cash }}">
                <div class="col">
                    {{-- Cash --}}
                </div>
            </div>

            <div class="form-row" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Credit }}">
                <div class="col col-xl-6">
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

            <div class="form-row" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Check }}">
                <div class="col col-xl-6">
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

            <div class="form-row" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_CreditNote }}">
                <div class="col col-xl-6">
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
            <div class="form-row" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Promissory }}">
                <div class="col col-xl-6">
                    TODO: Promissory
                </div>
            </div>
            <div class="form-row" data-only="payments[payment_type][]={{ Payment::PAYMENT_TYPE_Card }}">
                <div class="col col-xl-6">
                    {{-- <x-form-input name="payments[card_holder][]"
                        default="{{ $old['card_holder'] ?? null }}"
                        placeholder="payments::payment.card_holder._" /> --}}
                    <x-form-input name="payments[card_number][]"
                        default="{{ $old['card_number'] ?? null }}"
                        placeholder="payments::payment.document_number._" />
                    <x-form-boolean name="payments[is_credit][]"
                        default="{{ $old['is_credit'] ?? false }}"
                        placeholder="payments::payment.is_credit._"
                        helper="payments::payment.is_credit.?" />
                </div>
            </div>
        </div>
    </div>
</div>
