@extends('layouts.app')

@section('auth')

    @if(\Request::is('online*') || \Request::is('error') || \Request::is('barcode*') || \Request::is('go_to_shaparak*'))
        <div class="container position-sticky z-index-sticky top-0">
            <div class="row">
                <div class="col-12">
                    @include('layouts.navbars.guest.pay')
                </div>
            </div>
        </div>
        @yield('content')
        @include('layouts.footers.guest.pay-footer')
    @else
        @include('layouts.navbars.auth.sidebar-rtl')
        <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg" style="overflow: unset !important;{{session('sidebar_show') ? '' : 'margin-right: 0px;'}}">
            @include('layouts.navbars.auth.nav-rtl')
            <div class="container-fluid py-4">
                @yield('content')
                @include('layouts.footers.auth.footer')
            </div>
        </main>
    @endif

@endsection