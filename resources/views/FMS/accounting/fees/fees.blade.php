@extends('layouts.header')
@section('content')
<!-- Content Header (Page header) -->
{{-- <div class="content-header">
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
</div> --}}
<!-- /.content-header -->

{{-- <!-- Content -->
<div class="content">
  <!-- Container-fluid -->
  <div class="container-fluid"> --}}
      <div class="row">
          <div class="col-lg-12">
                  {{-- <div class="card-header">
                  </div> --}}
                    <div class="card card-primary card-tabs">
                        <div class="card-header p-0 pt-1">
                          <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" data-toggle="pill" href="#fees" role="tab" aria-selected="true">Fees</a>
                            </li>
                            <li class="nav-item" id="listLink">
                              <a class="nav-link" data-toggle="pill" href="#list" role="tab" aria-selected="false">List</a>
                            </li>
                            <li class="nav-item" id="discountLink">
                                <a class="nav-link" data-toggle="pill" href="#discount" role="tab" aria-selected="false">Discount/Scholarship</a>
                              </li>
                          </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="fees" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label>Branch</label>
                                            <select class="form-control select2" name="branch">
                                                @foreach($branch as $row)
                                                    <option value="{{$row->id}}">{{$row->name}} ({{$row->code}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Level</label>
                                            <select class="form-control select2" name="level">
                                                @foreach($program_level as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-12" id="feesDiv">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="list" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button class="btn btn-primary btn-primary-scan" name="new" style="float: right">
                                                <span class="fa fa-plus"></span> New Fee
                                            </button><br>
                                            <br>
                                            <table id="listTable" class="table table-bordered table-fixed"
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
                                                        <th data-field="f2" data-sortable="true" data-align="center">Name</th>
                                                        <th data-field="f3" data-sortable="true" data-align="center">Type</th>
                                                        <th data-field="f4" data-sortable="true" data-align="center">Option</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="discount" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button class="btn btn-primary btn-primary-scan" name="new" style="float: right">
                                                <span class="fa fa-plus"></span> New Discount/Scholarship
                                            </button><br>
                                            <br>
                                            <table id="discountTable" class="table table-bordered table-fixed"
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
                                                        <th data-field="f2" data-sortable="true" data-align="center">Discount/Scholarship</th>
                                                        <th data-field="f3" data-sortable="true" data-align="center">Percent</th>
                                                        <th data-field="f4" data-sortable="true" data-align="center">Fees Discount</th>
                                                        <th data-field="f5" data-sortable="true" data-align="center">Discount To</th>
                                                        <th data-field="f6" data-sortable="true" data-align="center">Status</th>
                                                        <th data-field="f7" data-sortable="true" data-align="center">Option</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  <div class="card-footer">
                  
                  </div>
          </div>
      <!-- /.col-md-6 -->
      </div>
  <!-- /.row -->
  {{-- </div><!-- /.container-fluid -->
</div>
<!-- /.Content --> --}}
@include('layouts.script')
<script src="{{ asset('assets/js/fms/accounting/fees.js') }}"></script>
@endsection