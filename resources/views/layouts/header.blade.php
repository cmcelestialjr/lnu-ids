<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>LNU - IDS</title>
  
  @include('layouts.stylesheet')
  
</head>   
{{-- <body class="hold-transition sidebar-mini layout-fixed"> --}}
<body class="hold-transition layout-top-nav">
<div class="wrapper">
    <!-- Preloader -->
  {{-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('assets/images/logo/lnu_logo.png') }}" alt="IDSLogo" height="150" width="150">
  </div> --}}

  {{-- <!-- Navbar -->
  @include('layouts.navigation')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('layouts.leftnav') --}}

  @include('layouts.topnav')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <div class="row">   
      <div class="col-lg-12">
        <div class="float-sm-right">
          <span class="navigation-selected">{{$system_selected}} /</span>
          <span class="navigation-selected navigation-selected-active">{{mb_strtoupper($nav_selected)}}</span>
        </div>
      </div>
    </div>
    @yield('content')

  </div>
  <!-- /.content-wrapper -->
 
  @include('layouts.modal')
  @include('layouts.footer')
  
</div>
<!-- ./wrapper -->

</body>


</html>