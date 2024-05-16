@extends('layouts.header')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title"></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <i>* required fields</i>
                </div>
                <div class="col-lg-6">
                    <label for="type">Document Type*:</label>
                    <div id="typeDiv">
                        <select class="form-control select2" name="type" id="type">
                            <option value="">Please select...</option>
                            @foreach($documents as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label for="office">Forward Office To*:</label>
                    <div id="officeDiv">
                        <select class="form-control select2" name="office" id="office">
                            <option value="">Please select...</option>
                            @foreach($offices as $row)
                                <option value="{{$row->id}}">{{$row->shorten}} - {{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <label for="particulars">Particulars*:</label>
                    <input type="text" class="form-control" name="particulars" id="particulars">
                </div>
                <div class="col-lg-12">
                    <label for="description">Description*:</label>
                    <textarea class="form-control" name="description" id="description"></textarea>
                </div>
                <div class="col-lg-5">
                    <label for="amount">Amount:</label>
                    <input type="number" class="form-control" name="amount" id="amount">
                </div>
                <div class="col-lg-7">
                    <label for="remakrs">Remakrs:</label>
                    <textarea class="form-control" name="remarks" id="remarks"></textarea>
                </div>
                <div class="col-lg-6">
                    <label for="file">File:</label>
                    <div class="file-drop-area">
                       <button class="btn btn-primary btn-primary-scan">Choose file</button>
                       &nbsp; <span class="file-message">or drag and drop file here</span>
                       <input class="file-input" type="file" name="file[]" id="file" accept="application/pdf,image/*" multiple>
                   </div>
                   <div id="file-selected-count"></div>
                </div>
                <div class="col-lg-12"><br>
                    <button class="btn btn-success btn-success-scan" name="submit" style="width: 100%">
                        <span class="fa fa-check"></span> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-default-new" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div id="modal-danger-content"></div>
    </div>
</div>
@include('layouts.script')
<script src="{{ asset('assets/js/dts/new.js') }}"></script>
@endsection
