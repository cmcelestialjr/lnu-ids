<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>LNU - Integrated Data System</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link rel="icon" href="{{ asset('assets/images/logo/lnu_logo.png') }}" type="image/gif" nonce="{{ csp_nonce() }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/fontawesome-free/css/all.min.css') }}" nonce="{{ csp_nonce() }}">
   <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/toastr/toastr.min.css') }}" nonce="{{ csp_nonce() }}">
  <!-- adminLTE style -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/dist/css/adminlte.min.css') }}" nonce="{{ csp_nonce() }}">
  <!-- master style -->
  <link rel="stylesheet" href="{{ asset('assets/master/master.css') }}" nonce="{{ csp_nonce() }}">

  <link rel="stylesheet" href="{{ asset('assets/css/systems.css') }}" nonce="{{ csp_nonce() }}">
</head>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-light">
            <a href="" class="navbar-brand">
                <img src="{{ asset('assets/images/logo/ids_logo_dark.png') }}" alt="LNU IDS Logo" class="brand-image elevation-3">
                <span class="brand-text font-weight-light">LNU IDS</span>
            </a>
            <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{url('logout')}}">
                        <i class="fa fa-reply"> Logout</i>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <span class="default-header">My Modules</span><br>
                    <span class="default-desc">Choose the module that you want to use.</span>
                    <!-- Small boxes (Stat box) -->
                    <div class="row" id="row-size">
                        @if($count_systems<=2)
                            <div class="col-lg-3 col-2">
                            </div>
                        @endif
                        @php
                        $x = 1;
                        @endphp
                        @foreach($systems as $row)
                            @php
                                $url = url('/ids/'.mb_strtolower($row['shorten']).$row['nav_url']);
                            @endphp
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box button-nav">
                                    <div class="inner">
                                        <h3 class="button-nav button-nav-h3 {{$row['button']}}">{{$row['shorten']}}</h3>
                                        <p class="button-desc">{{$row['name']}}</p>
                                    </div>
                                    <div class="icon">
                                        <i class="{{$row['icon']}}"></i>
                                    </div>
                                    <a href="{{$url}}" class="{{$row['button']}} small-box-btn">Proceed</a>
                                </div>
                            </div>
                            @php
                            $x++;
                            @endphp
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>
    @extends('layouts.footer')

</body>

    <!-- jQuery -->
    <script src="{{ asset('_adminLTE/plugins/jquery/jquery.min.js') }}" nonce="{{ csp_nonce() }}"></script>
    <script src="{{ asset('_adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}" nonce="{{ csp_nonce() }}"></script>
    <script src="{{ asset('_adminLTE/plugins/toastr/toastr.min.js') }}"></script>
    <script>
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "preventOpenDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "300",
            "timeOut": "2000",
            "extendedTimeOut": "800",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        toastr.success('Welcome');
    </script>
</html>
