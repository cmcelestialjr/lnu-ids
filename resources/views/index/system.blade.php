<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>LNU - Integrated Data System</title>
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
  background: url('../../assets/images/background/buidling.jpg') top center;
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
.title{
    margin-left:20px;
}
#logout{
    float:right;
    margin-top:10px;
    margin-right:40px;
}
.button-info {
  background: #17a2b8;
}
.button-primary {
  background: #007bff;
}
.button-success {
  background: #28a745;
}
.button-warning {
  background: #ffc107;
}
.button-danger {
  background: #dc3545;
}
.small-box{
    color:white;
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
                <a class="btn-no-design title" href="#">
                    <img src="{{ asset('assets/images/logo/lnu_logo.png') }}"  id="logo" alt="">
                            <span id="title"> &nbsp;LNU-Integrated Data System(IDS)</span>                                    
                </a>
                <a class="btn-no-design" href="{{ url('logout') }}" id="logout"><span class="fa fa-reply-all"></span> Logout</a>
            </div>
        </nav>
        <div class="content-wrapper">
                <br><br>
            <section class="content">
                <div class="container-fluid">
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
                                $url = url('/ids/'.mb_strtolower($row->shorten).'/home/n');
                            @endphp                    
                            <div class="col-lg-3 col-6">
                                <!-- small box -->
                                <div class="small-box {{$row->button}}">
                                    <div class="inner">
                                        <h3>{{$row->shorten}}</h3>

                                        <p>{{$row->name}}</p>
                                    </div>
                                    <div class="icon">
                                        <i class="{{$row->icon}}"></i>
                                    </div>
                                    <a href="{{$url}}" class="small-box-footer">Proceed <i class="fas fa-arrow-circle-right"></i></a>
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
</body>
    
    <!-- jQuery -->
    <script src="{{ asset('_adminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('_adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</html>