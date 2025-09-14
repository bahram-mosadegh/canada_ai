<!--
=========================================================
* Nilgam Admin Dashboard - v1.0.3
=========================================================

* Copyright 2023 Nilgam Group (https://www.nilgam.com)
* Coded by Baharam Mosadegh

=========================================================
-->
<!DOCTYPE html>

    <html dir="rtl" lang="fa">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/img/apple-icon.png')}}">
  <link rel="icon" type="image/png" href="{{asset('assets/img/favicon.png')}}">
  <title>
    {{ __('message.site_name') }}
  </title>
  <script src="{{asset_versioned('assets/js/core/jquery-3.7.1.min.js')}}"></script>
  <link href="{{asset_versioned('assets/css/app.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="{{asset_versioned('assets/fontawesome/css/all.css')}}">
  <link href="{{asset_versioned('assets/data-tables/DataTables-1.13.1/css/jquery.dataTables.min.css')}}" rel="stylesheet">
  <link href="{{asset_versioned('assets/data-tables/DataTables-1.13.1/css/dataTables.responsive.min.css')}}" rel="stylesheet">
  <link href="{{asset_versioned('assets/data-tables/DataTables-1.13.1/css/buttons.dataTables.min.css')}}" rel="stylesheet" />
  <link href="{{asset_versioned('assets/css/persian-datepicker.css')}}" rel="stylesheet" />
  <link href="{{asset_versioned('assets/css/select2.min.css')}}" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100 rtl" @if(Request::is('desktop')) style="background: url('/assets/img/desktop-bg.jpg') center center/cover;" @endif>
  @auth
    @yield('auth')
  @endauth
  @guest
    @yield('guest')
  @endguest
    <!--   Core JS Files   -->
  <script src="{{asset_versioned('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{asset_versioned('assets/data-tables/DataTables-1.13.1/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset_versioned('assets/data-tables/DataTables-1.13.1/js/dataTables.responsive.min.js')}}"></script>
  <script src="{{asset_versioned('assets/data-tables/DataTables-1.13.1/js/dataTables.buttons.min.js')}}"></script>
  <script src="{{asset_versioned('assets/js/core/bootstrap.bundle.min.js')}}" type="text/javascript"></script>
  <script src="{{asset_versioned('assets/js/core/bootstrap-multiselect.js')}}" type="text/javascript"></script>
  <script src="{{asset_versioned('vendor/datatables/buttons.server-side.js')}}"></script>
  <script src="{{asset_versioned('assets/js/plugins/select2.min.js')}}"></script>
  @stack('rtl')
  <script src="{{asset_versioned('assets/js/app.js')}}"></script>
  @stack('script')
</body>

</html>
