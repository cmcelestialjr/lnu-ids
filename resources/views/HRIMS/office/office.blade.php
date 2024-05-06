@extends('layouts.header')
@section('content')
<div class="row" id="officeDiv">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
              <div class="row">
                  <div class="col-lg-3">
                      <label>Office Type</label>
                      <select class="form-control" name="office_type">
                          <option value="All">All</option>
                          
                      </select>
                  </div>
                  <div class="col-lg-12">
                      <br>
                      <table id="officeTable" class="table table-bordered table-fixed"
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
                                  <th data-field="f2" data-sortable="true" data-align="center">Office</th>
                                  <th data-field="f3" data-sortable="true" data-align="center">Shorten</th>
                                  <th data-field="f4" data-sortable="true" data-align="center">Type</th>
                                  <th data-field="f5" data-sortable="true" data-align="center">Parent Office</th>
                                  <th data-field="f6" data-sortable="true" data-align="center">No. of Employee</th>
                                
                              </tr>
                          </thead>
                      </table>
                  </div>
              </div>
            </div>
            <div class="card-footer">
              
            </div>
        </div>
    </div>
</div>
<!-- /.row -->
@include('layouts.script')
<script src="{{ asset('assets/js/hrims/office/office.js') }}"></script>
<script src="{{ asset('assets/js/hrims/office/office_new.js') }}"></script>
<script src="{{ asset('assets/js/hrims/office/office_update.js') }}"></script>
@endsection