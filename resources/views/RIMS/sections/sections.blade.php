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
<div class="content" id="schoolYearDiv">
  <!-- Container-fluid -->
    <div class="container-fluid">
        <div class="card card-primary card-tabs">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5">
                        <label>School Year</label>
                        <select class="form-control select2">
                            @foreach($school_year as $row)
                                <option value="{{$row->id}}">{{$row->year_from}} - {{$row->year_to}} ({{$row->grade_period->name}})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <table id="viewTable" class="table table-bordered table-fixed"
                                    data-toggle="table"
                                    data-search="true"
                                    data-height="700"
                                    data-buttons-class="primary"
                                    data-show-export="true"
                                    data-show-columns-toggle-all="true"
                                    data-pagination="false"
                                    data-page-size="50"
                                    data-loading-template="loadingTemplate"
                                    data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                            <thead>
                                <tr>
                                    <th data-field="f1" data-sortable="true" data-align="center">#</th>
                                    <th data-field="f2" data-sortable="true" data-align="center">Department</th>
                                    <th data-field="f3" data-sortable="true" data-align="center">Programs</th>
                                    <th data-field="f4" data-sortable="true" data-align="center">Sections</th>
                                    @if($user_access->level_id==1 || $user_access->level_id==2)
                                    <th data-field="f5" data-sortable="true" data-align="center">Edit</th>
                                    @endif
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.Content -->

@include('layouts.script')
<script src="{{ asset('assets/js/rims/sections.js') }}"></script>
@endsection