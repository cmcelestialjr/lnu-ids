
<div class="modal-content" id="courseViewModal">
    <div class="modal-header">
        <h4 class="modal-title"><span class="fa fa-info"></span> {{$teacher_name}}</h4>
        <span class="fa fa-times btn-no-design" data-dismiss="modal">x</span>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-3">
                <label>Program Level</label>
                <select class="form-control select2-default" name="program_level">
                    <option value="0">All</option>
                    @foreach($program_level as $row)
                        @if($program_level_id==$row->id)
                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                        @else
                            <option value="{{$row->id}}">{{$row->name}}</option>
                        @endif                        
                    @endforeach
                </select>
                <input type="hidden" name="id" value="{{$id}}">
            </div>
            <div class="col-lg-3">
                <label>School Year</label>
                <select class="form-control select2-default" name="school_year">
                    <option value="0">All</option>
                    @foreach($school_year as $row)
                        @if($school_year_id==$row['id'])
                            <option value="{{$row['id']}}" selected>{{$row['name']}}</option>
                        @else
                            <option value="{{$row['id']}}">{{$row['name']}}</option>
                        @endif                        
                    @endforeach
                </select>
            </div>
            <div class="col-lg-12">           
                <table id="courseViewTable" class="table table-bordered table-fixed"
                        data-toggle="table"
                        data-search="true"
                        data-height="460"
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
                            <th data-field="f2" data-sortable="true" data-align="center">Section Code</th>
                            <th data-field="f3" data-sortable="true" data-align="center">Course Code</th>
                            <th data-field="f4" data-sortable="true" data-align="center">Course Description</th>
                            <th data-field="f5" data-sortable="true" data-align="center">Units</th>
                            <th data-field="f6" data-sortable="true" data-align="center">Grade</th>
                            <th data-field="f7" data-sortable="true" data-align="center">Status</th>
                            <th data-field="f8" data-sortable="true" data-align="center">Level</th>
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