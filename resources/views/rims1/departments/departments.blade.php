@extends('layouts.header')
@section('content')
<div class="card card-primary card-outline">
  <div class="card-body">
      <div class="row" id="departmentsDiv">
          <div class="col-lg-12">
              @if($user_access->level_id==1 || $user_access->level_id==2)
              <button class="btn btn-primary btn-primary-scan newModal" style="float:right">
                  <span class="fa fa-plus-square"></span> New Department</button>
              <br><br>
              @endif
              <table id="viewTable" class="table table-bordered table-fixed"
                          data-toggle="table"
                          data-search="true"
                          data-height="460"
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

@include('layouts.script')
<script src="{{ asset('assets/js/rims/departments/_function.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/view.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/modal.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/new.js') }}"></script>
<script src="{{ asset('assets/js/rims/departments/update.js') }}"></script>
@endsection