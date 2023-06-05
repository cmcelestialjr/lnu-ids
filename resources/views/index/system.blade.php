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
<style>
.carousel-item{
    height:95vh;
}
.carousel-image{
    height:100%;
}
#logo{
  height: 60px;
  width: 60px;
}
.content-wrapper {
  width: 100%;
  height: 100vh;
  background: url('../../assets/images/background/systems_bg.jpg') center;
  background-size: cover;
  position: relative;
}

#title{
    font-size: 20px;
    font-weight: bold;
    color: #6c757d;
    text-align: center;
    letter-spacing: 2px;
    text-shadow: -1px -1px 3px #f8f9fa, 
        2px 2px 4px #f8f9fa;
}
.button-nav{
  background: #ffffff;
  font-weight: 600;
  margin-bottom: 30px !important;
}
.button-info{
  color: #17a2b8;
  background: #17a2b8;
  border-radius: 10px;
  transition: 0.5s;
}
.button-primary {
  color: #4385F5;
  background: #4385F5;
  border-radius: 10px;
  transition: 0.5s;
}
.button-success {
  color: #109D59;
  background: #109D59;
  border-radius: 10px;
  transition: 0.5s;
}
.button-warning {
  color: #F5B400;
  background: #F5B400;
  border-radius: 10px;
  transition: 0.5s;
}
.button-danger {
  color: #DC4437;
  background: #DC4437;
  border-radius: 10px;
  transition: 0.5s;
}
.button-desc{
  color: #808080;
  font-size: 16px !important;
}
.small-box{
    margin: 10px;
    padding: 10px;
    color:white;
    border-radius: 10px;
    border: 1px solid #f5f5f5;
    box-shadow: none;
    transition: 0.5s;
}

#row-size{
    padding-left:100px;
    padding-right:100px;
}
@media (max-width: 850px){
    #row-size{
        padding-left:0px;
        padding-right:0px;
    }
}
</style>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-light">
            <div style="width:100%;">
                <img src="{{ asset('assets/images/logo/ids_logo_full_light.png') }}"  id="logo" alt="">   
                <a class="btn btn-primary" href="{{ url('logout') }}" id="logout">Logout</a>
            </div>
        </nav>
        <div class="content-wrapper">
                <br><br>
            <section class="content">
                <div class="container-fluid">
                    <h1 class="default-header">My Modules</h1>
                    <p class="default-desc">Choose the module that you want to use.</p>
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
                                        <h3 class="button-nav {{$row['button']}}" style="background: none !important; font-size: 55px;">{{$row['shorten']}}</h3>
                                        <p class="button-desc">{{$row['name']}}</p>
                                    </div>
                                    <div class="icon">
                                        <i class="{{$row['icon']}}"></i>
                                    </div>
                                    <a href="{{$url}}" class="{{$row['button']}}" style="display: block; width: 100% !important; 
                                    color: #f5f5f5; text-align: center; padding: 10px 5px; border-radius: 5px;">Proceed</a>
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
    <script src="{{ asset('_adminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('_adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</html>