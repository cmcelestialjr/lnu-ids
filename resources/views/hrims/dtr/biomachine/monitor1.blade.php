<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>LNU IDS V1.0</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link rel="icon" href="{{ asset('assets/images/logo/lnu_logo.png') }}" type="image/gif">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- adminLTE style -->
  <link rel="stylesheet" href="{{ asset('_adminLTE/dist/css/adminlte.min.css') }}">
  <!-- master style -->
  <link rel="stylesheet" href="{{ asset('assets/master/master.css') }}">
  @livewireStyles
  <style>
    .image{
        border-radius: 22px 22px 22px 22px;
        -webkit-border-radius: 22px 22px 22px 22px;
        -moz-border-radius: 22px 22px 22px 22px;
        border: 8px solid #a4a4a4;
    }
    .border-table{
        border: 5px double #8f8989;
    }
  </style>
</head>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-light">
            {{-- <div style="width:100%;"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                <img src="{{ asset('assets/images/logo/lnu_logo.png') }}" alt="lnu-logo" style="height:120px;width:120px;">                
            </div>
            <div style="width:100%;">
                <img src="{{ asset('assets/images/logo/ids_logo_full_light.png') }}" alt="lnu-ids" style="height:120px;width:180px;float:right">
            </div> --}}
        </nav>
        
        <div class="content-wrapper">
            @livewire('h-r-i-m-s.d-t-r.b-i-o-m-a-c-h-i-n-e.d-t-r-monitor1')            
        </div>
    </div>
</body>
<script src="{{ asset('_adminLTE/plugins/jquery/jquery-3.6.3.min.js') }}"></script>
<script src="{{ asset('assets/js/hrims/dtr/biomachine/monitor1.js') }}"></script>
@livewireScripts
</html>