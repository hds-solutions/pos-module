@include('backend::components.errors')

<input type="hidden" name="currency_id" value="{{ pos_settings()->currency()->id }}">

<div class="row">
    <div class="col-12 col-md-8 d-flex align-items-center min-h-100px">
        <div class="row w-100">
            <div class="col-12 col-xl-6">
                @include('pos::pointofsale.create.form.client')
            </div>
            <div class="col-12 col-xl-6">
                @include('pos::pointofsale.create.form.invoice')
            </div>
        </div>
    </div>
    <div class="col">
        {{-- @include('pos::pointofsale.create.form.product') --}}
    </div>
</div>

<div class="row flex-grow-1">
    <div class="col">

        <div class="card h-100 text-center bg-transparent border-0">
            <div class="card-header py-1 bg-transparent border-0">

                <nav class="nav nav-justified position-relative">
                    <div class="d-flex w-100">
                        <span class="nav-item nav-link py-1 py-xl-2 d-flex justify-content-center completed">
                            <div class="w-75px h-75px rounded-circle border border-3 border-gray-400 d-flex justify-content-center align-items-center bg-white">
                                <i class="fas fa-2x fa-cash-register text-gray-500"></i>
                            </div>
                        </span>

                        <hr class="connecting-line m-0 border-2 border-success">

                        <span class="nav-item nav-link py-1 py-xl-2 d-flex justify-content-center completed">
                            <div class="w-75px h-75px rounded-circle border border-3 border-gray-400 d-flex justify-content-center align-items-center bg-white">
                                <i class="fas fa-2x fa-money-bill-wave text-gray-500"></i>
                            </div>
                        </span>

                        <hr class="connecting-line m-0 border-2 offset border-success">

                        <span class="nav-item nav-link py-1 py-xl-2 d-flex justify-content-center active">
                            <div class="w-75px h-75px rounded-circle border border-3 border-gray-400 d-flex justify-content-center align-items-center bg-white">
                                <i class="fas fa-2x fa-receipt text-gray-500"></i>
                            </div>
                        </span>
                    </div>
                </nav>

            </div>
            <div class="card-body d-flex flex-column bg-white rounded-top border border-bottom-0 p-0 overflow-hidden">
                <div class="row h-100">
                    <div class="col-12 col-lg-8 col-xl-9 d-flex flex-column h-100">
                        @include('pos::pointofsale.show.form.lines')
                    </div>
                    <div class="col-12 col-lg">
                        @include('pos::pointofsale.print.form.payments')
                    </div>
                </div>
            </div>
            <div class="card-footer border">
                <div class="row">
                    <div class="offset-2 offset-md-6 offset-lg-8 offset-xl-9 col-10 col-md-6 col-lg-4 col-xl-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold px-3 min-w-100px">TOTAL</span>
                            </div>
                            <input name="total" type="number" min="0" thousand readonly
                                value="{{ $resource->total }}" tabindex="-1" data-decimals="{{ $resource->currency->decimals }}"
                                class="form-control form-control-lg text-right font-weight-bold"
                                placeholder="@lang('sales::order.lines.total.0')">
                        </div>
                    </div>
                </div>
                <div class="row pt-1">
                    <div class="offset-2 offset-md-6 offset-lg-8 offset-xl-9 col-10 col-md-6 col-lg-4 col-xl-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold px-3 min-w-100px">PAGOS</span>
                            </div>
                            <input name="payments_amount" type="number" min="0" thousand readonly
                                value="0" tabindex="-1" data-decimals="{{ $resource->currency->decimals }}"
                                class="form-control form-control-lg text-right font-weight-bold text-primary"
                                placeholder="@lang('sales::order.lines.payments.0')">
                        </div>
                    </div>
                </div>
                <div class="row pt-1">
                    <div class="offset-2 offset-md-6 offset-lg-8 offset-xl-9 col-10 col-md-6 col-lg-4 col-xl-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold px-3 min-w-100px">VUELTO</span>
                            </div>
                            <input name="return_amount" type="number" min="0" thousand readonly
                                value="0" tabindex="-1" data-decimals="{{ $resource->currency->decimals }}"
                                class="form-control form-control-lg text-right font-weight-bold text-success"
                                placeholder="@lang('sales::order.lines.return.0')">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border border-top-0 d-flex justify-content-around">
                <button class="btn btn-lg btn-gray" disabled data-key="F3"><i class="fas fa-user-edit mr-2"></i>Cliente <small>[F3]</small></button>
                <button class="btn btn-lg btn-gray" disabled data-key="F6"><i class="fas fa-search mr-2"></i>Buscar producto <small>[F6]</small></button>
                <button class="btn btn-lg btn-gray" disabled data-key="F9"><i class="fas fa-hand-holding-usd mr-2"></i>Pagar <small>[F9]</small></button>
                <a href="{{ route('backend.pointofsale.create') }}" data-key="F12"
                    {{-- data-confirm="Cancelar venta"
                    data-text="Esta seguro de cancelar la venta actual?" data-text-type="danger"
                    data-modal-type="danger"
                    data-accept="Si, cancelar venta" data-cancel="Continuar"
                    data-accept-class="btn-outline-danger" data-cancel-class="btn-success" --}}
                    class="btn btn-lg btn-success"><i class="fas fa-check mr-2"></i>Finalizar Venta <small>[F12]</small></a>
            </div>
        </div>

    </div>
</div>
