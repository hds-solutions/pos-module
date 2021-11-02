@extends('pos::layouts.master')

@section('page-name', __('pos::pointofsale.title'))

@section('pos-title')
    <i class="fas fa-company-plus"></i>
    @lang('pos::pointofsale.show') <span class="font-weight-normal">[ {{ pos_settings()->currency()->name }} | {{ pos_settings()->branch()->name }} | {{ pos_settings()->warehouse()->name }} ]</span>
@endsection

@section('content')

    @include('pos::pointofsale.print.form')

    @include('pos::pointofsale.modals')

@endsection
