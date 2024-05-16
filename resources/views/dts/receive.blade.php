@extends('layouts.header')
@section('content')
<div class="card card-primary card-tabs">
    <div class="card-header p-0 pt-1">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="receive-link"
                data-toggle="pill"
                href="#receive-tab"
                role="tab"
                aria-controls="receive-tabs"
                aria-selected="true">For Receive</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="received-link"
                data-toggle="pill"
                href="#received-tab"
                role="tab"
                aria-controls="received-tabs"
                aria-selected="false">Received</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content">
        <div class="tab-pane fade show active" id="receive-tab"
                role="tabpanel"
                aria-labelledby="for-receive-tabs">

        </div>
        <div class="tab-pane fade"
                id="received-tab"
                role="tabpanel"
                aria-labelledby="received-tabs">

        </div>
      </div>
    </div>
</div>
@include('layouts.script')
<script src="{{ asset('assets/js/dts/receive.js') }}"></script>
<script src="{{ asset('assets/js/dts/forward.js') }}"></script>
@endsection
