@extends('pos::layouts.master')

@section('page-name', __('pos::pointofsale.title'))

@section('content')

    <div class="card mb-3">
        <div class="card-header bg-primary text-white font-weight-bold">
            <div class="row">
                <div class="col-6">
                    <i class="fas fa-company-plus"></i>
                    @lang('pos::pointofsale.create') <span class="font-weight-normal">[ {{ pos_settings()->currency()->name }} | {{ pos_settings()->branch()->name }} | {{ pos_settings()->warehouse()->name }} ]</span>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    {{-- <a href="{{ route('backend.pointofsale.create') }}"
                        class="btn btn-sm btn-primary">@lang('pos::pointofsale.add')</a> --}}
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('backend.pointofsale.store') }}" enctype="multipart/form-data">
                @csrf
                @include('pos::pointofsale.create.form')
            </form>
        </div>
    </div>

@endsection
