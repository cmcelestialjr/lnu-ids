@extends('layouts.header')
@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="input-group">
            <input type="search" class="form-control form-control-lg" placeholder="Type your keywords here">
            <div class="input-group-append">
                <button type="submit" class="btn btn-lg btn-default">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->

@include('layouts.script')
<script src="{{ asset('assets/js/dts/search.js') }}"></script>
@endsection
