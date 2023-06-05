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
<div class="content" id="scheduleDiv">
  <!-- Container-fluid -->
  <div class="container-fluid">
      <div class="row">
          <div class="col-lg-12">
            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" data-toggle="pill" href="#list" role="tab" aria-selected="true">View</a>
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane fade show active" id="list" role="tabpanel">
                      <div class="row">
                        <div class="col-lg-4">
                          <label>School Year</label>
                          <select class="form-control select2" name="school_year">
                            @foreach($school_year as $row)
                              <option value="{{$row->id}}">{{$row->year_from}}-{{$row->year_to}} ({{$row->grade_period->name}})</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-lg-4" id="gradeLevelDiv">
                            
                        </div>
                        <div class="col-lg-12 table-responsive">
                          <br>
                          <div id="scheduleTable"></div>                          
                        </div>
                      </div>
                    </div>
                  </div>
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
<script src="{{ asset('assets/js/fis/schedule/_function.js') }}"></script>
<script src="{{ asset('assets/js/fis/schedule/view.js') }}"></script>
<script src="{{ asset('assets/js/fis/schedule/modal.js') }}"></script>
<script src="{{ asset('assets/js/fis/schedule/new.js') }}"></script>
<script src="{{ asset('assets/js/fis/schedule/update.js') }}"></script>
<script src="{{ asset('assets/js/fis/schedule/delete.js') }}"></script>
@endsection