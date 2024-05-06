
<div class="modal-content" id="coursesOpenModal">
    <div class="modal-header">
        <label>Open a Course</label>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-md-5" id="courseSelect">
                <label>Course Code</label>
                <select class="form-control courseSelect" name="course_code">
                    
                </select>
            </div>
            <div class="col-md-5">
                <label>Branch</label>
                <select class="form-control select2-primary" name="branch">
                    @foreach($branch as $row)
                        <option value="{{$row->id}}">({{$row->code}}) {{$row->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12">
                <table id="coursesListTable" class="table table-bordered table-fixed"
                        data-toggle="table"
                        data-search="true"
                        data-height="400"
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
                      <th data-field="f2" data-sortable="true" data-align="center">Course Code</th>
                      <th data-field="f3" data-sortable="true" data-align="center">Course Description</th>
                      <th data-field="f4" data-sortable="true" data-align="center">Program</th>
                      <th data-field="f5" data-sortable="true" data-align="center">Curriculum</th>
                      <th data-field="f6" data-sortable="true" data-align="center">Term</th>
                      <th data-field="f7" data-sortable="true" data-align="center">Grade/Level</th>
                      <th data-field="f8" data-sortable="true" data-align="center">Option</th>
                    </tr>
                  </thead>
                </table>
                <button type="button" class="btn btn-success btn-success-scan" name="submit_course" style="width: 100%">Open Course</button>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<script src="{{ asset('assets/js/search/courseList.js') }}"></script>
<!-- /.modal-content -->
