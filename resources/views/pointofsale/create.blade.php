@extends('pos::layouts.master')

@section('page-name', __('pos::pointofsale.title'))

@section('pos-title')
    <div class="row">
        <div class="col d-flex align-items-center">
            <i class="fas fa-company-plus"></i>
            <span class="mr-3 fs-1-25x">@lang('pos::pointofsale.create') {{ pos_settings()->pos()->name }}</span>
            <div class="mt-1">
                <span class="font-weight-normal text-gray-500">{{ pos_settings()->currency()->name }}</span>
                <span class="mx-1">|</span>
                <span class="font-weight-normal text-gray-500">{{ pos_settings()->branch()->name }}</span>
                <span class="mx-1">|</span>
                <span class="font-weight-normal text-gray-500">{{ pos_settings()->warehouse()->name }}</span>
                <span class="mx-1">|</span>
                <span class="font-weight-normal text-gray-500">{{ pos_settings()->priceList()->name }}</span>
                <span class="mx-1">|</span>
                <span class="font-weight-bold">{{ pos_settings()->employee()->full_name }}</span>
            </div>
        </div>
        <div class="col text-right">
            @if ($cash = pos_settings()->cashBook()->cashes()->open()->first())
            <a href="{{ route('backend.cashes.show', $cash) }}"
                class="btn btn-sm btn-outline-light btn-hover-danger font-weight-bold"
                data-modal-type="danger"
                data-confirm="Salir del Punto de Venta"
                data-text-type="danger" data-text="Esta seguro de salir del Punto de Venta?"
                data-accept="Si, salir" data-accept-class="btn-outline-danger btn-hover-danger"
                data-cancel-class="btn-danger">x</a>
            @endif
        </div>
    </div>
@endsection

@section('content')

    @if ($cash)
        <form method="POST" action="{{ route('backend.pointofsale.store') }}" enctype="multipart/form-data"
            autocomplete="off" class="d-flex flex-column h-100">
            <input type="text" name="autocomplete" autocomplete="false" class="d-none">

            @csrf
            @include('pos::pointofsale.create.form')
        </form>

    @else
        @include('pos::pointofsale.create.no-cash-modal')

    @endif

    @include('pos::pointofsale.modals')

@endsection
