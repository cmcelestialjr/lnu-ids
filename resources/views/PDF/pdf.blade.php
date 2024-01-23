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
</head>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <div class="content-wrapper">            
            <section class="content center">
                <div class="container-fluid" style="width: 95%">
                    <div id="loader" style="width:100%;"></div>
                    <input type="hidden" id="pdf_option" value="{{$pdf_option}}">
                    <iframe id="documentPreview" src="" style="width:100%;"></iframe>
                </div>
            </section>
        </div>
    </div>    
</body>
<script src="{{ asset('_adminLTE/plugins/jquery/jquery-3.6.3.min.js') }}"></script>
<script src="{{ asset('assets/js/pdf/src.js') }}"></script>
</html>
