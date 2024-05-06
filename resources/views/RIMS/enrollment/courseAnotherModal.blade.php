
<div class="modal-content" id="courseAnotherModal">
    <div class="modal-header">
        <h4 class="modal-title">
           <span class="fa fa-refresh"></span> Choose another course from another section/curriculum/program
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12 table-responsive">
                <input type="hidden" name="course_id" value="{{$id}}">
                <table class="table">
                    <tr>
                        <td style="width:8%">Code:</td>
                        <td style="width:8%"><label>{{$query->curriculum->offered_program->name}}</label></td>
                        <td style="width:10%">Program:</td>
                        <td style="width:28%"><label>{{$query->curriculum->offered_program->program->name}}
                                ({{$query->curriculum->offered_program->program->shorten}})
                            </label></td>                        
                        <td style="width:10%">Curriculum:</td>
                        <td style="width:18%"><label>{{$query->curriculum->curriculum->year_from}}-{{$query->curriculum->curriculum->year_to}} 
                            ({{$query->curriculum->curriculum->code}})</label></td>
                        <td style="width:8%">Section:</td>
                        <td style="width:10%"><label>{{$query->section}}</label></td>
                    </tr>
                    <tr>
                        <td>Course Code:</td>
                        <td><label>{{$query->code}}</label></td>
                        <td>Title:</td>
                        <td><label>{{$query->course->name}}</label></td>
                        <td>Units:</td>
                        <td><label>{{$query->course->units}}</label></td>
                        <td>Pre-req:</td>
                        <td><label>
                            {{$query->course->pre_name}}
                        </label></td>
                    </tr>
                    <tr>
                        @php
                            $schedule = 'TBA';
                            $room = 'TBA';
                            if(count($query->schedule)>0){
                                foreach($query->schedule as $row){    
                                    $days = array();
                                    if($row->room_id==NULL){
                                        $rm = 'TBA';
                                    }else{
                                        $rm = $row->room->name;
                                    }
                                    foreach($row->days as $day){
                                        $days[] = $day->day;
                                    }
                                    $days1 = implode('',$days);
                                    $schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
                                                    date('h:ia',strtotime($row->time_to)).' '.$days1;
                                    $rooms[] = $rm;

                                }
                                $schedule = implode('<br>',$schedules);
                                $room = implode('<br>',$rooms);
                            }
                            $instructor = 'TBA';
                            if($query->instructor_id!=NULL){
                                $instructor = $name_services->firstname($query->instructor->lastname,$query->instructor->firstname,$query->instructor->middlename,$query->instructor->extname);
                            }
                            @endphp
                        <td>Status:</td>
                        <td><label>{{$query->status->name}}</label></td>
                        <td>Instructor:</td>
                        <td>
                            {{$instructor}}
                        </td>
                        <td>Schedule:</td>
                        <td>                            
                            <label>{{$schedule}}</label>
                        </td>
                        <td>Room:</td>
                        <td>
                            <label>{{$room}}</label>
                        </td>
                    </tr>
                </table>
           </div>
           <div class="col-lg-12">
                <table id="courseAnotherTable" class="table table-bordered table-fixed"
                        data-toggle="table"
                        data-search="true"
                        data-height="500"
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
                            <th data-field="f2" data-sortable="true" data-align="center">Program</th>
                            <th data-field="f3" data-sortable="true" data-align="center">Section Code</th>
                            <th data-field="f4" data-sortable="true" data-align="center">Course Code</th>
                            <th data-field="f5" data-sortable="true" data-align="center">Descriptive Title</th>
                            <th data-field="f6" data-sortable="true" data-align="center">Units</th>
                            <th data-field="f7" data-sortable="true" data-align="center">Schedule</th>
                            <th data-field="f8" data-sortable="true" data-align="center">Room</th>
                            <th data-field="f9" data-sortable="true" data-align="center">Instructor</th>
                            <th data-field="f10" data-sortable="true" data-align="center">Option</th>
                        </tr>
                    </thead>
                </table>
           </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
