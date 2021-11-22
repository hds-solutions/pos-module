@extends('pos::layouts.master')

@section('page-name', __('pos::pointofsale.title'))

@section('pos-title')
    <i class="fas fa-company-plus"></i>
    @lang('pos::pointofsale.show') <span class="font-weight-normal">[ {{ pos_settings()->currency()->name }} | {{ pos_settings()->branch()->name }} | {{ pos_settings()->warehouse()->name }} ]</span>
@endsection

@section('content')

    <form method="POST" action="{{ route('backend.pointofsale.pay', $resource) }}" enctype="multipart/form-data"
        autocomplete="off" class="d-flex flex-column h-100">
        <input type="text" name="autocomplete" autocomplete="false" class="d-none">

        @csrf
        @include('pos::pointofsale.show.form')
    </form>

    <form method="POST" action="{{ route('backend.pointofsale.destroy', $resource) }}" id="pos-delete">
        @csrf
        @method('DELETE')
    </form>

    @include('pos::pointofsale.modals')

@endsection
