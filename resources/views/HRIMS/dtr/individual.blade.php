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
<link rel="stylesheet" href="{{ asset('assets/css/error/error.css') }}">
<!-- Content -->
<div class="content" id="dtrDiv">
  <!-- Container-fluid -->
  <div class="container-fluid">
      <input type="hidden" name="id_no" value="{{$id_no}}">
      <input type="hidden" name="dtr_type" value="1">
      <div class="row">
          <div class="col-lg-12">              
              <div class="card card-primary card-outline">
                  <div class="card-header">
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-3">
                        <label>Year</label>
                        <select class="form-control select2" name="year">
                          @for ($i = date('Y'); $i >= 2023; $i--)
                            <option value="{{$i}}">{{$i}}</option>
                          @endfor
                        </select>
                      </div>
                      <div class="col-lg-3">
                        <label>Month</label>
                        <select class="form-control select2" name="month">
                            @for($i=1;$i<=12;$i++)
                              @if(date('m')==$i)
                                <option value="{{$i}}" selected>{{date('F', strtotime(date('Y').'-'.$i.'-01'))}}</option>
                              @else
                                <option value="{{$i}}">{{date('F', strtotime(date('Y').'-'.$i.'-01'))}}</option>
                              @endif
                            @endfor
                        </select>
                      </div>
                      <div class="col-lg-3">
                        <label>Range</label>
                        <div class="input-group mb-3">
                          <select class="form-control select2" name="range">
                            <option value="1">Whole Month</option>
                            <option value="2">Half Month (1-15)</option>
                          </select>
                          <div class="input-group-prepend">
                            <button type="button" class="btn btn-info btn-info-scan" name="submit">
                              <span class="fa fa-check"></span></button>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-12">
                        <div class="card card-info card-outline">
                          <div class="card-body row">
                              <div class="col-lg-12" id="previewDiv">
                                  <br>
                              </div>
                              <div class="col-lg-12 center" id="body">
                                <section class="hide" id="not-found">
                                  <div id="title"><br><br><br>                               
                                  </div>
                                  <div class="circles">
                                    <p><br>
                                     <small>No Data Found!</small>
                                     <br><br><br><br>
                                    </p>
                                    <span class="circle big"></span>
                                    <span class="circle med"></span>
                                    <span class="circle small"></span>
                                  </div>
                                </section>
                              </div>
                          </div>
                      </div>
                      </div>
                    </div>
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
<script src="{{ asset('assets/js/hrims/dtr/individual.js') }}"></script>
@endsection