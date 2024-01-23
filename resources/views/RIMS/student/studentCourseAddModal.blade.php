
<div class="modal-content" id="studentCourseAddModal">
    <div class="modal-header">
        <h4 class="modal-title"><span class="fa fa-plus"></span> Add Course</h4>
        <span class="fa fa-times btn-no-design" data-dismiss="modal">x</span>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-info card-outline">
                    <div class="card-body">
                        <label>Programs</label> (Not in List?
                            <label>
                                <input type="radio" name="in_list" value="Yes">
                                Yes                        
                            </label>
                            <label>
                                <input type="radio" name="in_list" value="No" checked>
                                No                        
                            </label>)
                        <div id="inList">
                            <select class="form-control select2-info" name="student_program">
                                @foreach($query as $row)
                                    @php
                                        $program_shorten = $row->program_shorten;
                                        if($row->program_id!=NULL){
                                            $program_shorten = $row->program_info->shorten;
                                        }
                                    @endphp
                                <option value="{{$row->id}}">{{$program_shorten}}, {{$row->school_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="hide" id="notInList">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label>School Name</label>
                                    <input type="text" class="form-control" name="from_school">
                                </div>
                                <div class="col-lg-4">
                                    <label>Program Name</label>
                                    <input type="text" class="form-control" name="program_name">
                                </div>
                                <div class="col-lg-4">
                                    <label>Program Shorten</label>
                                    <input type="text" class="form-control" name="program_shorten">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3">
                                <label>School Year From</label>
                                <input type="text" class="form-control yearpicker" name="year_from">
                            </div>
                            <div class="col-lg-3">
                                <label>School Year To</label>
                                <input type="text" class="form-control" name="year_to" readonly>
                            </div>
                            <div class="col-lg-3">
                                <label>Period</label>
                                <select class="form-control select2-info" name="period">
                                    @foreach($periods as $row)
                                    <option value="{{$row->id}}">{{str_replace(' Semester','',$row->name)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($period=='sem')
                                <div class="col-lg-3">
                                    <label>Option</label>
                                    <select class="form-control select2-info" name="option">
                                        <option value="Semester">Semester</option>
                                        <option value="Trimester">Trimester</option>
                                    </select>
                                </div>
                            @else
                                <select class="form-control hide" name="option">
                                    <option value=""></option>
                                </select>
                            @endif
                            <div class="col-lg-12 table-responsive"><br>
                                <button class="btn btn-primary btn-primary-scan add" style="float:right"><span class="fa fa-plus"></span></button>
                                <table class="table table-bordered" id="courseInfoTable">
                                    <thead>
                                        <th style="width: 15%">Course Code</th>
                                        <th style="width: 37%">Course Description</th>
                                        <th style="width: 10%">Unit</th>
                                        <th style="width: 10%">Lab</th>
                                        <th style="width: 15%">Option</th>
                                        <th style="width: 10%">Rating</th>
                                        <th style="width: 3%"></th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control course_code"></td>
                                            <td><input type="text" class="form-control course_desc"></td>
                                            <td><input type="number" class="form-control unit"></td>
                                            <td><input type="number" class="form-control lab" value="0"></td>
                                            <td>
                                                <select class="form-control select2-info statuses" data-n="1" id="course_statuses1">
                                                    @foreach($statuses as $row)
                                                    <option value="{{$row->id}}" data-option="{{$row->option}}">{{$row->shorten}} - {{$row->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="number" class="form-control rating" id="rating_1"></td>
                                            <td>
                                                
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-primary-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
