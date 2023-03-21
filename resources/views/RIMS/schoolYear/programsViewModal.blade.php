
<div class="modal-content" id="programsViewModal">
    <div class="modal-header">
        <h4 class="modal-title">{{$query->year_from}} - {{$query->year_to}} ({{$query->grade_period->name}}) 
            <br> School Year</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-lg-12">
                @if($user_access_level==1 || $user_access_level==2)
                    <button class="btn btn-primary btn-primary-scan coursesOpenModal" style="float:right">
                        <span class="fa fa-plus-square"></span> Open a Course
                    </button>
                    <br><br>
                @endif
                <table id="programsViewTable" class="table table-bordered table-fixed"
                            data-toggle="table"
                            data-search="true"
                            data-height="600"
                            data-buttons-class="primary"
                            data-show-export="true"
                            data-show-columns-toggle-all="true"
                            data-mobile-responsive="true"
                            data-pagination="true"
                            data-page-size="10"
                            data-page-list="[10, 50, 100, All]"
                            data-loading-template="loadingTemplate"
                            data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                    <thead>
                        <tr>
                            <th data-field="f1" data-sortable="true" data-align="center">#</th>
                            <th data-field="f2" data-sortable="true" data-align="center">Level</th>
                            <th data-field="f3" data-sortable="true" data-align="center">Department</th>
                            <th data-field="f4" data-sortable="true" data-align="center">Programs</th>
                            <th data-field="f5" data-sortable="true" data-align="center">Code</th>
                            <th data-field="f6" data-sortable="true" data-align="center">Courses</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>   
    </div>
</div>
<!-- /.modal-content -->
