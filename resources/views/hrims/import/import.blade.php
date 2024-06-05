@extends('layouts.header')
@section('content')
<div class="row">
  <div class="col-lg-12">
      <div class="card card-primary card-outline">
          <div class="card-header">
            <h4>Import</h4>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <i>Reminder: Please use the excel format when importing excel file here.</i>
                </div>
                <div class="col-lg-3">
                    <label for="option">Option</label>
                    <select class="form-control select2" id="option" name="option">
                        <option value="employee">Employee</option>
                        <option value="position">Position</option>
                    </select>
                </div>
                <div class="col-lg-4">
                    <div class="file-drop-area">
                       <button class="btn btn-primary btn-primary-scan">Choose file</button>
                       &nbsp; <span class="file-message">or drag and drop file here</span>
                       <input class="file-input" type="file" id="files" name="files" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                   </div>
                   <span id="file-selected-count"></span>
                   <div class="progress progress" id="progress-bar">
                        <div class="progress-bar bg-success progress-bar-striped" role="progressbar"
                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span class="sr-only">0% Complete</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12"><br>
                    <button class="btn btn-primary btn-primary-scan" id="submit-import" style="width: 100%;">Submit</button>
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
<script src="{{ asset('assets/js/hrims/import/import.js') }}"></script>

@endsection
