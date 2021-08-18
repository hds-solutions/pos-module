@extends('pos::layouts.master')

@section('page-name', __('pos::pointofsale.title'))

@section('content')

    <div class="card mb-3">
        <div class="card-header bg-primary text-white font-weight-bold">
            <div class="row">
                <div class="col-6">
                    <i class="fas fa-company-plus"></i>
                    @lang('pos::pointofsale.index')
                </div>
                <div class="col-6 d-flex justify-content-end">
                    {{-- <a href="{{ route('backend.pointofsale.create') }}"
                        class="btn btn-sm btn-primary">@lang('pos::pointofsale.add')</a> --}}
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('backend.pointofsale.session') }}" enctype="multipart/form-data">
                @csrf
                @include('pos::pointofsale.index.form')
            </form>
        </div>
    </div>

@endsection
