@extends('layouts.header')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card card-primary card-tabs">
        <div class="card-body">
            <div class="tab-content">
                <div class="row">
                    <div class="col-md-9"></div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" id="search-pagination" class="form-control" placeholder="Search...">
                            <div class="input-group-append">
                                <span class="input-group-text clear-search-pagination" style="display: none;"><i class="fa fa-times"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered table-fixed">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Document</th>
                                    <th>Particulars</th>
                                    <th>Office</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="table-body-pagination">

                            </tbody>
                        </table>
                        <div id="pagination">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<!-- /.row -->

@include('layouts.script')
<script src="{{ asset('assets/js/pagination.js') }}"></script>
@endsection
