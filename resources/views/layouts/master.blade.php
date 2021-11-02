@extends('backend::layouts.app')

@section('app')

    <div class="container-fluid vh-100 p-3 bg-light" id="pos-container">
        <div class="row h-100">
            <div class="col">

                <div class="card h-100">
                    <div class="card-header bg-dark text-white font-weight-bold">
                        <div class="row">
                            <div class="col">
                                @yield('pos-title')
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @yield('content')
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset(mix('pos-module/assets/css/app.css')) }}">
@endpush
@push('pre-scripts')
    <script src="{{ asset(mix('pos-module/assets/js/app.js')) }}"></script>
@endpush
