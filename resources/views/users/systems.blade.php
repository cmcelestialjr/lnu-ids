@extends('layouts.header')
@section('content')
<div class="row" id="systemsDiv">          
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header hide">
              
            </div>
            <div class="card-body">
              <button class="btn btn-primary btn-primary-scan" id="new"><span class="fa fa-plus"></span> New System</button>
              <table id="viewTable" class="table table-bordered table-fixed"
                      data-toggle="table"
                      data-search="false"
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
                          <th data-field="f3" data-sortable="true" data-align="center">Shorten</th>
                          <th data-field="f4" data-sortable="true" data-align="center">Navigation</th>
                          <th data-field="f5" data-sortable="true" data-align="center">Edit</th>
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
@include('layouts.script')
<script src="{{ asset('assets/js/users/systems.js') }}"></script>
@endsection