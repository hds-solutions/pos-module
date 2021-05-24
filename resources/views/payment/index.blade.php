@extends('pos::layouts.master')

@section('page-name', __('pos::payment.title'))

@section('content')

    <div class="card mb-3">
        <div class="card-header bg-primary text-white font-weight-bold">
            <div class="row">
                <div class="col-6">
                    <i class="fas fa-company-plus"></i>
                    @lang('pos::payment.index')
                </div>
                <div class="col-6 d-flex justify-content-end">
                    {{-- <a href="{{ route('backend.payment.create') }}"
                        class="btn btn-sm btn-primary">@lang('inventory::companieies.add')</a> --}}
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('backend.payment.session') }}" enctype="multipart/form-data">
                @csrf
                @include('pos::payment.index.form')
            </form>
        </div>
    </div>

@endsection
