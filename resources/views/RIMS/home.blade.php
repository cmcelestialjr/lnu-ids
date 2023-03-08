@extends('layouts.header')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">{{$system_selected}}</a></li>
            <li class="breadcrumb-item active">{{mb_strtoupper($nav_selected)}}</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->


<!-- Content -->
<div class="content">
  <!-- Container-fluid -->
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
            </div>
            <div class="card-body">
              
            </div>
            <div class="card-footer">
                
            </div>
        </div>
      </div>
    <!-- /.col-md-6 -->
    </div>
  <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.Content -->
@include('layouts.script')
@endsection