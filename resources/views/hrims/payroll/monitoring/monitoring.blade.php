@extends('layouts.header')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                    <label>Option</label>
                    <select class="form-control select2" id="monitoringOption">
                        <option value="partTime">Part-time</option>
                        <option value="overLoad">Overload</option>
                    </select>
                </div>
              </div>
              <div class="row" id="monitoringDiv">
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
<script src="{{ asset('assets/js/hrims/payroll/monitoring/monitoring.js') }}"></script>
@endsection
