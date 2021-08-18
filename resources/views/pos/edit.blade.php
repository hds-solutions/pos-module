@extends('backend::layouts.master')

@section('page-name', __('pos::pos.title'))

@section('content')

<div class="card mb-3">
    <div class="card-header">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
                <i class="fas fa-company-plus"></i>
                @lang('pos::pos.edit')
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a href="{{ route('backend.pos.create') }}"
                    class="btn btn-sm btn-outline-primary">@lang('pos::pos.create')</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('backend.pos.update', $resource) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('pos::pos.form')
        </form>
    </div>
</div>

@endsection
