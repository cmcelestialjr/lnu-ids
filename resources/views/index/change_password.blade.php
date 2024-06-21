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
  <link rel="stylesheet" href="{{ asset('assets/css/change_password.css') }}" nonce="{{ csp_nonce() }}">
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
                    <br>
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-12">
                            <div class="small-box button-nav" style="height:100% !important;">
                                <div class="row">
                                    <div class="col-12" style="padding-top: 0 !important; padding-right: 15px !important; padding-bottom: 0px !important; padding-left: 15px !important;">
                                        <div class="inner">
                                            <h3 class="button-nav button-nav-h3 button-info">
                                                <span style="font-size:30px;">Change Password</span></h3>{{$update_password}}
                                            @if($update_password==NULL || $update_password==0)
                                            <p class="button-desc">
                                                Welcome! As this is your first login, we kindly request you to change your password for security purposes.
                                            </p>
                                            @else
                                            <p class="button-desc">
                                                You may now change your password.
                                            </p>
                                            @endif
                                            <p class="button-desc">
                                                <div class="row">
                                                    <div class="col-lg-6 col-12">
                                                        <div class="input-group">
                                                            <input type="password" class="form-control" id="password">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text" id="togglePassword">
                                                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </p>
                                            <p class="button-desc" id="password-policies" style="font-size: 14px !important;">
                                                <i id="policy-1"><span id="policy-span-1">*</span> must atleast 8 characters</i><br>
                                                <i id="policy-2"><span id="policy-span-2">*</span> must contain a small letter</i><br>
                                                <i id="policy-3"><span id="policy-span-3">*</span> must contain a capital letter</i><br>
                                                <i id="policy-4"><span id="policy-span-4">*</span> must contain a number</i><br>
                                                <i id="policy-5"><span id="policy-span-5">*</span> must contain a special character</i>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-11" style="padding-top: 0px !important; padding-right: 15px !important; padding-bottom: 15px !important; padding-left: 15px !important;"><br><br><br>
                                        <button class="btn btn-info btn-info-scan small-box-btn" id="submit" disabled style="width: 100% !important;">
                                            <span class="fa fa-check"></span> Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script src="{{ asset('_adminLTE/plugins/toastr/toastr.min.js') }}" nonce="{{ csp_nonce() }}"></script>
    <script src="{{ asset('assets/js/change_password.js') }}" nonce="{{ csp_nonce() }}"></script>
</html>
