
<div class="modal-content" id="groupCoursesModal">
    <div class="modal-header">
        <h4><span class="fa fa-info"></span> {{$group->name}}</h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5" id="courseSelect">
                        <select class="form-control select2-primary courseSelect" name="course">

                        </select>
                    </div>
                    <div class="col-lg-5">
                        <button class="btn btn-primary btn-primary-scan" name="add">
                            <span class="fa fa-check"></span> Add Course
                        </button>
                    </div>
                    <div class="col-lg-12">
                        <input type="hidden" name="id" value="{{$group->id}}">
                        <table id="labGroupCourseTable" class="table table-bordered table-fixed"
                                data-toggle="table"
                                data-search="true"
                                data-height="600"
                                data-buttons-class="primary"
                                data-show-export="true"
                                data-show-columns-toggle-all="true"
                                data-mobile-responsive="true"
                                data-pagination="false"
                                data-loading-template="loadingTemplate"
                                data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                            <thead>
                                <tr>
                                    <th data-field="f1" data-sortable="true" data-align="center">#</th>
                                    <th data-field="f2" data-sortable="true" data-align="center">Code</th>
                                    <th data-field="f3" data-sortable="true" data-align="center">Description</th>
                                    <th data-field="f4" data-sortable="true" data-align="center">Amount</th>
                                    <th data-field="f5" data-sortable="true" data-align="center">Option</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-plus"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
<script src="{{ asset('assets/js/search/courseList.js') }}"></script>
