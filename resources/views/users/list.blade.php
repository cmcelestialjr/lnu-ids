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
                  <div class="card-header" id="listDiv">
                    @if($user->level_id==1)
                      <div class="row">
                        <div class="col-lg-3">
                          <label>Option:</label>
                          <select class="form-control select2" name="option" style="width:100%;">
                            <option value="Employee">Employee</option>
                            <option value="Student">Student</option>
                          </select>
                        </div>
                      </div>
                    @endif
                  </div>
                  <div class="card-body" id="user_div">
                    <form action="{{ url('/import/import') }}" method="POST" enctype="multipart/form-data">
                      @csrf
                        <div class="col-lg-6">
                             <div class="file-drop-area">
                                <button class="btn btn-primary btn-primary-scan">Choose file</button>
                                &nbsp; <span class="file-message">or drag and drop file here</span>
                                <input class="file-input" type="file" name="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                            </div>
                            <button class="btn btn-primary btn-primary-scan">Submit Import</button>
                       </div>
                     </form>
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
                                <th data-field="f2" data-sortable="true" data-align="center">Name</th>
                                <th data-field="f3" data-sortable="true" data-align="center">Access</th>
                                @if($system_selected=='USERS')
                                <th data-field="f4" data-sortable="true" data-align="center">User Access</th>
                                @endif
                                <th data-field="f5" data-sortable="true" data-align="center">Status</th>
                            </tr>
                        </thead>
                    </table>
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
<script src="{{ asset('assets/js/users/status.js') }}"></script>
<script src="{{ asset('assets/js/users/table.js') }}"></script>
<script src="{{ asset('assets/js/users/access.js') }}"></script>
@endsection