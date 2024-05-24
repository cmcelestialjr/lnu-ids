@extends('layouts.header')
@section('content')
<div class="row" id="employeeDiv">
  <div class="col-lg-12">
      <div class="card card-primary card-outline">
          <div class="card-header" id="selectionDiv">
            <div class="alert alert-info" style="padding-top: 20px;padding-bottom: 20px;">
              <div class="row">
                <div class="col-md-6">
                  Total: <b>
                    <button class="btn btn-primary btn-primary-scan btn-status active-btn" id="btn-all" data-id="all">
                      0
                    </button></b>
                  &nbsp; / &nbsp; Total Active: <b>
                    <button class="btn btn-success btn-success-scan btn-status" id="btn-active" data-id="active">
                      0
                    </button></b>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2">
                <select class="form-control select2" name="option">
                  <option value="all">All</option>
                  <option value="2">Personnel</option>
                  <option value="3">Faculty</option>
                </select>
              </div>
              <div class="col-lg-9 table-responsive">
                <div class="btn-group btn-group-md">
                  <button class="btn btn-info btn-info-scan btn-status" data-id="all">ALL <span class="alert-blue-g" id="num_all"></span></button>
                  <button class="btn btn-info btn-info-scan btn-status" data-id="1">Permanent <span class="alert-green-g" id="num_1"></span></button>
                  <button class="btn btn-info btn-info-scan btn-status" data-id="3">Temporary <span class="alert-green-g" id="num_3"></span></button>
                  <button class="btn btn-info btn-info-scan btn-status" data-id="2">Casual <span class="alert-green-g" id="num_2"></span></button>
                  <button class="btn btn-info btn-info-scan btn-status" data-id="4">Job Order <span class="alert-green-g" id="num_4"></span></button>
                  <button class="btn btn-info btn-info-scan btn-status" data-id="5">Part Time <span class="alert-green-g" id="num_5"></span></button>
                  <button class="btn btn-info btn-info-scan btn-status" data-id="separated">Separated <span class="alert-danger-g" id="num_sep"></span></button>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <div class="input-group" style="float:right">
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
                                <th class="th-paginate" data-column="1" data-sort="asc">Employee ID
                                    <div style="float:right">
                                        <span class="sort-paginate fa fa-long-arrow-up">
                                        </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                    </div>
                                </th>
                                <th class="th-paginate" data-column="2" data-sort="asc">Name
                                    <div style="float:right">
                                        <span class="sort-paginate fa fa-long-arrow-up">
                                        </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                    </div>
                                </th>
                                <th class="th-paginate" data-column="3" data-sort="asc">Position
                                    <div style="float:right">
                                        <span class="sort-paginate fa fa-long-arrow-up">
                                        </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                    </div>
                                </th>
                                <th class="th-paginate" data-column="4" data-sort="asc">Salary
                                    <div style="float:right">
                                        <span class="sort-paginate fa fa-long-arrow-up">
                                        </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                    </div>
                                </th>
                                <th class="th-paginate" data-column="5" data-sort="asc">Status
                                    <div style="float:right">
                                        <span class="sort-paginate fa fa-long-arrow-up">
                                        </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                    </div>
                                </th>
                                <th class="th-paginate" data-column="6" data-sort="asc">Type
                                    <div style="float:right">
                                        <span class="sort-paginate fa fa-long-arrow-up">
                                        </span><span class="sort-paginate fa fa-long-arrow-down"></span>
                                    </div>
                                </th>
                                <th>View
                                </th>
                                <th>Deduc
                                </th>
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
          <div class="card-footer">

          </div>
      </div>
  </div>
<!-- /.col-md-6 -->
</div>
<!-- /.row -->
@include('layouts.script')
<script src="{{ asset('assets/js/hrims/employee/employee.js') }}"></script>
<script src="{{ asset('assets/js/hrims/employee/employeePagination.js') }}"></script>
<script src="{{ asset('assets/js/hrims/employee/employee_view.js') }}"></script>
<script src="{{ asset('assets/js/hrims/employee/information/_information.js') }}"></script>
<script src="{{ asset('assets/js/hrims/employee/deduction/deduction.js') }}"></script>
<script src="{{ asset('assets/js/search/position.js') }}"></script>
<script src="{{ asset('assets/js/search/designation.js') }}"></script>
@endsection
