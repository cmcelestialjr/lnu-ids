@extends('layouts.header')
@section('content')
<div class="card card-primary card-tabs">
    <div class="card-header p-0 pt-1">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="forward-link"
                data-toggle="pill"
                href="#forward-tab"
                role="tab"
                aria-controls="forward-tabs"
                aria-selected="true">For Forward</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="forwarded-link"
                data-toggle="pill"
                href="#forwarded-tab"
                role="tab"
                aria-controls="forwarded-tabs"
                aria-selected="false">Forwarded</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content">
        <div class="tab-pane fade show active" id="forward-tab"
                role="tabpanel"
                aria-labelledby="forward-tab">

        </div>
        <div class="tab-pane fade"
                id="forwarded-tab"
                role="tabpanel"
                aria-labelledby="forwarded-tabs">

        </div>
      </div>
    </div>
</div>
@include('layouts.script')
<script src="{{ asset('assets/js/dts/receive.js') }}"></script>
<script src="{{ asset('assets/js/dts/forward.js') }}"></script>
@endsection
