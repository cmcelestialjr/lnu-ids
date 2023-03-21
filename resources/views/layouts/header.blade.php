<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>LNU - IDS</title>
   <!-- Favicons -->
   <link rel="icon" href="{{ asset('assets/images/logo/lnu_logo.png') }}" type="image/gif">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Bootstrap-table -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/bootstrap-table/bootstrap-table.min.css') }}">
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/bootstrap-table/extensions/fixed-columns/bootstrap-table-fixed-columns.min.css') }}">
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/bootstrap-table/extensions/filter-control/bootstrap-table-filter-control.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/summernote/summernote-bs4.min.css') }}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- datepicker -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/datepicker/bootstrap-datepicker.min.css') }}">
  <!-- timepicker -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/timepicker/jquery.timepicker.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/toastr/toastr.min.css') }}">
  <!-- Bootstrap Table -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/bootstrap-table/bootstrap-table.min.css') }}">
  <!-- adminLTE style -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/dist/css/adminlte.min.css') }}">
  <!-- master style -->
  <link rel="stylesheet" href="{{ asset('assets/master/master.css') }}">
  @livewireStyles
  
</head>   
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('assets/images/logo/lnu_logo.png') }}" alt="IDSLogo" height="150" width="150">
  </div>

  <!-- Navbar -->
  @include('layouts.navigation')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('layouts.leftnav')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    @yield('content')

  </div>
  <!-- /.content-wrapper -->
 
  @include('layouts.modal')
  @include('layouts.footer')
  
</div>
<!-- ./wrapper -->

</body>


</html>