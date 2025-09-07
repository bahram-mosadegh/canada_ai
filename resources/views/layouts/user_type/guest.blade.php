@extends('layouts.app')

@section('guest')
    @if(\Request::is('online*') || \Request::is('error') || \Request::is('barcode*') || \Request::is('go_to_shaparak*'))
        @if(!isset($hide_header) || !$hide_header)
        <div class="container position-sticky z-index-sticky top-0">
            <div class="row">
                <div class="col-12">
                    @include('layouts.navbars.guest.pay')
                </div>
            </div>
        </div>
        @endif
        @yield('content')
        @include('layouts.footers.guest.pay-footer')
    @elseif(\Request::is('kiosk') || \Request::is('check_kiosk_barcode'))
        @if(\Request::is('kiosk'))
        <div class="container position-sticky z-index-sticky top-0" style="max-width: 100% !important;">
            <div class="row">
                <div class="col-12">
                    @include('layouts.navbars.guest.kiosk')
                </div>
            </div>
        </div>
        @endif
        @yield('content')
    @else
        <div class="container position-sticky z-index-sticky top-0">
            <div class="row">
                <div class="col-12">
                    @include('layouts.navbars.guest.nav')
                </div>
            </div>
        </div>
        @yield('content')        
        @include('layouts.footers.guest.footer')
    @endif
@endsection