@extends('pos::layouts.master')

@section('page-name', __('pos::pos.title'))

@section('content')

    <div class="card mb-3">
        <div class="card-header bg-primary text-white font-weight-bold">
            <div class="row">
                <div class="col-6">
                    <i class="fas fa-company-plus"></i>
                    @lang('pos::pos.index')
                </div>
                <div class="col-6 d-flex justify-content-end">
                    {{-- <a href="{{ route('backend.pos.create') }}"
                        class="btn btn-sm btn-primary">@lang('pos::pos.add')</a> --}}
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('backend.pos.session') }}" enctype="multipart/form-data">
                @csrf
                @include('pos::pos.index.form')
            </form>
        </div>
    </div>

@endsection
