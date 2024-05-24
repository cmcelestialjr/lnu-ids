@extends('layouts.header')
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card card-primary card-tabs">
        <div class="card-body">
            <div class="tab-content">
                <div class="row">
                    <div class="col-lg-2 col-md-1 col-sm-0">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                        <div class="small-box-mini small-box bg-success">
                            <div class="inner">
                              <h3 id="total-docs-count">0</h3>
                              <p>Total</p>
                            </div>
                            <div class="icon">
                              <i class="fa fa-plus-square"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                        <div class="small-box-mini small-box bg-primary">
                            <div class="inner">
                              <h3 id="received-docs-count">0</h3>
                              <p>Received</p>
                            </div>
                            <div class="icon">
                              <i class="fa fa-caret-square-o-down"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                        <div class="small-box-mini small-box bg-info">
                            <div class="inner">
                              <h3 id="forwarded-docs-count">0</h3>
                              <p>Forwarded</p>
                            </div>
                            <div class="icon">
                              <i class="fa fa-forward"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                        <div class="small-box-mini small-box bg-warning">
                            <div class="inner">
                              <h3 id="returned-docs-count">0</h3>
                              <p>Returned</p>
                            </div>
                            <div class="icon">
                              <i class="fa fa-undo"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12"></div>
                    <div class="col-md-10"></div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <input type="text" id="search-pagination" class="form-control" placeholder="Search...">
                            <div class="input-group-append">
                                <span class="input-group-text clear-search-pagination" style="display: none; cursor: pointer;"><i class="fa fa-times"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered table-fixed table-paginate" style="margin-bottom: 5px; margin-top: 5px;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="th-paginate" data-column="1" data-sort="asc">DTS No.
                                        <div style="float:right">
                                            <span class="sort-paginate fa fa-long-arrow-up">
                                            </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                        </div>
                                    </th>
                                    <th class="th-paginate" data-column="2" data-sort="asc">Owner
                                        <div style="float:right">
                                            <span class="sort-paginate fa fa-long-arrow-up">
                                            </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                        </div>
                                    </th>
                                    <th class="th-paginate" data-column="3" data-sort="asc">Document
                                        <div style="float:right">
                                            <span class="sort-paginate fa fa-long-arrow-up">
                                            </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                        </div>
                                    </th>
                                    <th class="th-paginate" data-column="4" data-sort="asc">Particulars
                                        <div style="float:right">
                                            <span class="sort-paginate fa fa-long-arrow-up">
                                            </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                        </div>
                                    </th>
                                    <th class="th-paginate" data-column="5" data-sort="asc">Description
                                        <div style="float:right">
                                            <span class="sort-paginate fa fa-long-arrow-up">
                                            </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                        </div>
                                    </th>
                                    <th class="th-paginate" data-column="6" data-sort="asc">Created At
                                        <div style="float:right">
                                            <span class="sort-paginate fa fa-long-arrow-up">
                                            </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                        </div>
                                    </th>
                                    <th class="th-paginate" data-column="7" data-sort="asc">Duration
                                        <div style="float:right">
                                            <span class="sort-paginate fa fa-long-arrow-up">
                                            </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                        </div>
                                    </th>
                                    <th class="th-paginate" data-column="8" data-sort="asc">Latest Action
                                        <div style="float:right">
                                            <span class="sort-paginate fa fa-long-arrow-up">
                                            </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                        </div>
                                    </th>
                                    <th class="th-paginate" data-column="9" data-sort="asc">Status
                                        <div style="float:right">
                                            <span class="sort-paginate fa fa-long-arrow-up">
                                            </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                        </div>
                                    </th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody id="table-body-pagination">
                                <tr>
                                    <td colspan="11">
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
<script src="{{ asset('assets/js/dts/inboxPagination.js') }}"></script>
@endsection
