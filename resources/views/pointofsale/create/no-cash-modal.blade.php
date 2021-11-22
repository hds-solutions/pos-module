<div class="modal fade show" id="no-cash"
    tabindex="-1" role="dialog" aria-labelledby="no-cash-label" aria-hidden="false"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="no-cash-label">Caja del día</h5>
            </div>
            <div class="modal-body">
                Para poder utilizar el POS debe haber una caja del día abierta.
            </div>
            <div class="modal-footer">
                <a href="{{ route('backend.cashes.create', [ 'cash_book' => pos_settings()->cashBook() ]) }}" class="btn btn-outline-primary">Abrir caja</a>
            </div>
        </div>
    </div>
</div>
