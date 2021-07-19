@extends('backend::layouts.app')

@section('app')
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                @include('backend::layouts.topbar')
                <!-- Begin Page Contents -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    @hasSection('description')
                    <div class="row">
                        <div class="col">
                            <p>@yield('description')</p>
                        </div>
                    </div>
                    @endif
                    <!-- Main Content -->
                    @yield('content')
                <!-- End Page Contents -->
                </div>
            <!-- End of Main Content -->
            </div>
        <!-- End of Content Wrapper -->
        </div>
    <!-- End of Page Wrapper -->
    </div>

    {{-- modals --}}
    @include('backend::layouts.modals')
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset(mix('pos-module/assets/css/app.css')) }}">
@endpush
@push('pre-scripts')
    <script src="{{ asset(mix('pos-module/assets/js/app.js')) }}"></script>
@endpush
