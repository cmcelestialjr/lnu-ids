@extends('layouts.header')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-tabs">
          <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#view" role="tab" aria-selected="true">Buildings</a>
              </li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" role="tabpanel" id="view">
                    <div class="card card-info card-outline">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label>Status</label>
                                    <select class="form-control select2" name="buildingsStatus">
                                        @foreach($statuses as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12">                                    
                                    <button class="btn btn-primary btn-primary-scan" name="buildingsNewModal" style="float:right;">
                                        <span class="fa fa-plus"></span> New Building
                                    </button><br><br>
                                    <table id="buildingsTable" class="table table-bordered table-fixed"
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
                                                <th data-field="f2" data-sortable="true" data-align="center">Name</th>
                                                <th data-field="f3" data-sortable="true" data-align="center">Shorten</th>
                                                <th data-field="f4" data-sortable="true" data-align="center">Status</th>
                                                <th data-field="f5" data-sortable="true" data-align="center">Remarks</th>
                                                <th data-field="f6" data-sortable="true" data-align="center">View</th>
                                                <th data-field="f7" data-sortable="true" data-align="center">Edit</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>                   
    </div>                    
</div>
@include('layouts.script')
<script src="{{ asset('assets/js/rims/buildings/buildings.js') }}"></script>
@endsection