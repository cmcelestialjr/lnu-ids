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
<div class="content" id="departmentsDiv">
  <!-- Container-fluid -->
    <div class="container-fluid">
        <div class="card card-primary card-tabs">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if($user_access->level_id==1 || $user_access->level_id==2)
                        <button class="btn btn-primary btn-primary-scan newModal" style="float:right">
                            <span class="fa fa-plus-square"></span> New Department</button>
                        <br><br>
                        @endif
                        <table id="viewTable" class="table table-bordered table-fixed"
                                    data-toggle="table"
                                    data-search="true"
                                    data-height="600"
                                    data-buttons-class="primary"
                                    data-show-export="true"
                                    data-show-columns-toggle-all="true"
                                    data-mobile-responsive="true"
                                    data-pagination="true"
                                    data-page-size="10"
                                    data-page-list="[10, 50, 100, All]"
                                    data-loading-template="loadingTemplate"
                                    data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                            <thead>
                                <tr>
                                    <th data-field="f1" data-sortable="true" data-align="center">#</th>
                                    <th data-field="f2" data-sortable="true" data-align="center">Department</th>
                                    <th data-field="f3" data-sortable="true" data-align="center">Shorten</th>                                  
                                    <th data-field="f4" data-sortable="true" data-align="center">Code</th>
                                    <th data-field="f5" data-sortable="true" data-align="center">Programs</th>
                                    @if($user_access->level_id==1 || $user_access->level_id==2)
                                    <th data-field="f6" data-sortable="true" data-align="center">Edit</th>
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
<script src="{{ asset('assets/js/rims/departments/_function.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/view.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/modal.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/new.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/update.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/delete.js') }}"></script>
@endsection