@extends('layouts.header')
@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2" id="searchDiv">
        <div class="input-group">
            <input type="search" class="form-control form-control-lg" name="search" placeholder="Type your keywords here" value="{{$search_value}}" style="height:40px">
            <div class="input-group-append">
                <button type="submit" name="submit" class="btn btn-lg btn-default">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-12" id="searchResult">

    </div>
</div>
<!-- /.row -->

@include('layouts.script')

<script src="{{ asset('assets/js/dts/search.js') }}"></script>

@endsection
