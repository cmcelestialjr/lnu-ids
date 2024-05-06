<div class="modal-content" id="scheduleModal">
    <div class="modal-header">
        <h4 class="modal-title">
           {{$course->course->name}} ({{$course->course->code}})
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">            
            <div class="col-lg-12 table-responsive">
                <input type="hidden" name="id" value="{{$id}}">
                <input type="hidden" name="start" value="0">
                <input type="hidden" name="start_sched" value="0">
                <table class="table">
                    <tr>
                        <td>Units:</td>
                        <td><label>{{$course->course->units}}</label></td>
                        @if($course->course->lab>0)
                            <td>Lab:</td>
                            <td><label>{{$course->course->lab}}</label></td>
                        @endif
                        <td>Schedule:</td>
                        <td><label id="scheduleLabel">{!!$schedule!!}</label></td>
                        <td>Room:</td>
                        <td><label id="roomLabel">{!!$room!!}</label></td>
                        <td>Instructor:</td>
                        <td><label id="instructorLabel">{{$instructor_name}}</label></td>
                        <td>No. of Student:</td>
                        <td><label>{{$no_students}}</label></td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-12">                
                <div class="card card-info card-outline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-10">
                                <label>Schedule</label>
                                <select class="select2-info" name="schedule" style="width:100%">
                                    <option value="New">New</option>
                                </select>
                            </div>
                            <div class="col-lg-4" id="selectRoom">
                                <label>Room <button class="btn btn-info btn-info-scan btn-xs" data-id="{{$id}}" id="roomView"><span class="fa fa-search"></span></button></label>
                                <div id="roomDiv">
                                    <select class="select2-info" name="room" id="roomSelect" style="width:100%">
                                        <option value="TBA">TBA</option>
                                        @if($room_id!='TBA' && $room_id!='')
                                            <option value="{{$room_id}}" selected>{{$room_name}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label>Instructor <button class="btn btn-info btn-info-scan btn-xs" data-id="{{$id}}" id="instructorView"><span class="fa fa-search"></span></button></button></label>
                                <div id="instructorDiv">
                                    <select class="select2-info" name="instructor" id="instructorSelect" style="width:100%">
                                        <option value="TBA">TBA</option>
                                        @if($instructor_id!='TBA')
                                            <option value="{{$instructor_id}}" selected>{{$instructor_name}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-1">
                                        <label>Hrs</label>
                                        <select class="select2-info" name="hours" style="width:100%">
                                            @foreach($hours_list as $row)
                                                @if($row==$course->hours)
                                                    <option value="{{$row}}" selected>{{$row}}</option>
                                                @else
                                                    <option value="{{$row}}">{{$row}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-1">
                                        <label>Mins</label>
                                        <select class="select2-info" name="minutes" style="width:100%">
                                            @foreach($minutes_list as $row)
                                                @if($row==$course->minutes)
                                                    <option value="{{$row}}" selected>{{$row}}</option>
                                                @else
                                                    <option value="{{$row}}">{{$row}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label>Days</label>
                                        <div id="daysDiv">
                                            <select class="select2-info" name="days[]" multiple id="daysSelect" style="width:100%">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Time</label>
                                        <div id="timeDiv">
                                            <select class="select2-info" name="time" style="width:100%">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 center">            
                                        <input type="radio" class="form-control" name="type" id="lecture" value="Lec" checked>
                                        <label for="lecture">Lec</label>
                                    </div>
                                    <div class="col-sm-1 center">            
                                        <input type="radio" class="form-control" name="type" id="laboratory" value="Lab">
                                        <label for="laboratory">Lab</label>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col-lg-4 center" style="padding-top:5px;">
                                <button class="btn btn-success btn-success-scan" name="save" style="width: 100%;">
                                    <span class="fa fa-save"></span> Save this Schedule
                                </button>
                            </div>
                            <div class="col-lg-1">
                            </div>
                            <div class="col-lg-4 center" style="padding-top:5px;">
                                <div id="scheduleRemoveDiv"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="row" style="font-size:10px;">
                            <div class="col-lg-12"><label>Legend:<br></label></div>
                            <div class="col-lg-1">
                                <div class="input-group">
                                    <div  class="bg-success legend-box">
                                    </div>
                                    <div class="input-group-append">
                                        &nbsp;Itself
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <div  class="legend-box" style="background-color:#3CB371">
                                    </div>
                                    <div class="input-group-append">
                                        &nbsp;Other within course
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <div  class="bg-info legend-box">
                                    </div>
                                    <div class="input-group-append">
                                        &nbsp;Courses within Section
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <div  class="bg-primary legend-box">
                                    </div>
                                    <div class="input-group-append">
                                        &nbsp;Instructor outside Section
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <div  class="bg-warning legend-box">
                                    </div>
                                    <div class="input-group-append">
                                        &nbsp;Room outside Section
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="input-group">
                                    <div  class="bg-danger legend-box">
                                    </div>
                                    <div class="input-group-append">
                                        &nbsp;Conflict
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12"><br></div>
                        </div>
                        <div id="scheduleCourseTable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
    </div>
</div>
<!-- /.modal-content -->
