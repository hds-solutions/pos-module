<div class="col-12 d-flex mb-1">
    <x-form-foreign name="employees[]"
        :values="$employees" default="{{ $old ?? $selected?->id }}"
        show="full_name"

        foreign="employees" foreign-add-label="people::employees.add"

        label="pos::pos.employees.employee_id.0"
        placeholder="pos::pos.employees.employee_id._"
        {{-- helper="pos::pos.employees.employee_id.?" --}} />

    <button type="button" class="btn btn-danger ml-2"
        data-action="delete"
        @if ($selected !== null)
        data-confirm="Eliminar @lang('Check')?"
        data-text="Esta seguro de eliminar la @lang('Check') {{ $selected->document_numbner }}?"
        data-accept="Si, eliminar"
        @endif>X</button>
</div>
