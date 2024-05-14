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
                                <span class="input-group-text clear-search-pagination" style="display: none; cursor: pointer;"><i class="fa fa-times"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered table-fixed" style="margin-bottom: 5px; margin-top: 5px;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>DTS No.</th>
                                    <th>Owner</th>
                                    <th>Document</th>
                                    <th>Particulars</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                    <th>Duration</th>
                                    <th>Latest Action</th>
                                    <th>Status</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody id="table-body-pagination">
                                <tr>
                                    <td colspan="9">
                                        <div class="main-item">
                                            <div class="animated-background">
                                                <div class="background-masker btn-divide-left"></div>
                                            </div>
                                            <div class="static-background">
                                            <div class="background-masker btn-divide-left"></div>
                                            </div>
                                            <div class="animated-background">
                                            <div class="background-masker btn-divide-left"></div>
                                            </div>
                                            <div class="static-background">
                                                <div class="background-masker btn-divide-left"></div>
                                            </div>
                                            <div class="animated-background">
                                                <div class="background-masker btn-divide-left"></div>
                                            </div>
                                            <div class="static-background">
                                                <div class="background-masker btn-divide-left"></div>
                                            </div>
                                            <div class="animated-background">
                                                <div class="background-masker btn-divide-left"></div>
                                            </div>
                                            <div class="static-background">
                                                <div class="background-masker btn-divide-left"></div>
                                            </div>
                                            <div class="animated-background">
                                                <div class="background-masker btn-divide-left"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="width: 100%">
                            <tr>
                                <td style="width:50%">
                                    <div id="pagination-info" style="float:left">

                                    </div>
                                </td>
                                <td style="width:50%">
                                    <div id="pagination" style="float:right">

                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<!-- /.row -->

@include('layouts.script')
<script src="{{ asset('assets/js/dts/inbox.js') }}"></script>
<script src="{{ asset('assets/js/dts/receive.js') }}"></script>
<script src="{{ asset('assets/js/dts/forward.js') }}"></script>
<script src="{{ asset('assets/js/pagination.js') }}"></script>
@endsection
