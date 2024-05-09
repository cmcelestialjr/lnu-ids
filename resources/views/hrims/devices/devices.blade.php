
@extends('layouts.header')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <div class="card card-primary card-outline">
          <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <button class="btn btn-primary btn-primary-scan" id="devicesNewModal">
                        <span class="fa fa-plus"></span> New Device
                    </button> &nbsp;
                    <button class="btn btn-info btn-info-scan" id="devicesUpdateStatus">
                        <span class="fa fa-edit"></span> Update Devices Status
                    </button>
                </div>
                <div class="col-lg-12">
                    <table id="devicesTable" class="table table-bordered table-fixed"
                        data-toggle="table"
                        data-search="true"
                        {{-- data-height="400" --}}
                        data-buttons-class="primary"
                        data-show-export="true"
                        data-show-columns-toggle-all="true"
                        data-mobile-responsive="true"
                        data-pagination="false"
                        {{-- data-page-size="10"
                        data-page-list="[10, 50, 100, All]" --}}
                        data-loading-template="loadingTemplate"
                        data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                      <thead>
                        <tr>
                          <th data-field="f1" data-sortable="true" data-align="center">#</th>
                          <th data-field="f2" data-sortable="true" data-align="center">Device Name</th>
                          <th data-field="f3" data-sortable="true" data-align="center">Ipaddress</th>
                          <th data-field="f4" data-sortable="true" data-align="center">Port</th>
                          <th data-field="f5" data-sortable="true" data-align="center">Remarks</th>
                          <th data-field="f6" data-sortable="true" data-align="center">Status</th>
                          <th data-field="f7" data-sortable="true" data-align="center">DateTime</th>
                          {{-- <th data-field="f8" data-sortable="true" data-align="center">Logs Device</th> --}}
                          <th data-field="f9" data-sortable="true" data-align="center">Edit</th>
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
<!-- /.col-md-6 -->
</div>
<!-- /.row -->
@include('layouts.script')
<script src="{{ asset('assets/js/hrims/devices/devices.js') }}"></script>
<script src="{{ asset('assets/js/hrims/devices/logs.js') }}"></script>
@endsection
