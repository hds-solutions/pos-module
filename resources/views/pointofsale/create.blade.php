@extends('pos::layouts.master')

@section('page-name', __('pos::pointofsale.title'))

@section('pos-title')
    <i class="fas fa-company-plus"></i>
    @lang('pos::pointofsale.create') <span class="font-weight-normal">[ {{ pos_settings()->currency()->name }} | {{ pos_settings()->branch()->name }} | {{ pos_settings()->warehouse()->name }} ]</span>
@endsection

@section('content')

    <form method="POST" action="{{ route('backend.pointofsale.store') }}" enctype="multipart/form-data"
        autocomplete="off" class="d-flex flex-column h-100">
        <input type="text" name="autocomplete" autocomplete="false" class="d-none">

        @csrf
        @include('pos::pointofsale.create.form')
    </form>

    @include('pos::pointofsale.modals')

@endsection
