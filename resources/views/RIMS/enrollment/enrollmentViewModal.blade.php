
<div class="modal-content" id="enrollmentViewModal">
    <div class="modal-header">
        <h4 class="modal-title">
           <span class="fa fa-graduation-cap"></span>
           {{$query->name}} - {{$query->program->name}} ({{$query->program->shorten}})
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$query->id}}">
           <div class="col-lg-4">
                <label>Curriculum</label>
                <select class="form-control select2-default" name="curriculum">
                    @foreach($curriculum as $row)
                        <option value="{{$row->id}}">{{$row->curriculum->year_from}}-{{$row->curriculum->year_to}} ({{$row->code}})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <label>Section</label>
                <select class="form-control select2-default" name="section">
                    @foreach($section as $row)
                        <option value="{{$row->section}}">{{$row->section}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-12">
                <br>
                <table id="enrollmentViewTable" class="table table-bordered table-fixed"
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
                            <th data-field="f2" data-sortable="true" data-align="center">Student No.</th>
                            <th data-field="f3" data-sortable="true" data-align="center">Name</th>
                            <th data-field="f4" data-sortable="true" data-align="center">Level</th>
                            <th data-field="f5" data-sortable="true" data-align="center">Status</th>
                            <th data-field="f6" data-sortable="true" data-align="center">Courses</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
    </div>
</div>
<!-- /.modal-content -->
