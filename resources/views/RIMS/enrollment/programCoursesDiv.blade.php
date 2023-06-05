<br>
<div class="card card-primary card-tabs">
    <div class="card-header p-0 pt-1">
      <ul class="nav nav-tabs" role="tablist">
        @if($type!='add')
        <li class="nav-item">
          <a class="nav-link active" data-toggle="pill" href="#advisement" role="tab" aria-selected="true">Advisement</a>
        </li>
        @endif
        <li class="nav-item">
          <a class="nav-link" data-toggle="pill" href="#curriculum" role="tab" aria-selected="false">Curriculum</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            @if($type!='add')
            <div class="tab-pane fade show active" id="advisement" role="tabpanel">
                <div class="row">
                    <div class="col-lg-12">
                        <table id="advisedTable" class="table table-bordered table-fixed"
                                      data-toggle="table"
                                      data-search="true"
                                      data-height="550"
                                      data-buttons-class="primary"
                                      data-show-export="true"
                                      data-show-columns-toggle-all="true"
                                      data-mobile-responsive="true"
                                      data-pagination="false"
                                      data-loading-template="loadingTemplate"
                                      data-export-types="['csv', 'txt', 'doc', 'excel', 'json', 'sql']">
                              <thead>
                                  <tr>
                                      <th data-sortable="true" data-align="center">#</th>
                                      <th data-sortable="true" data-align="center">Program</th>
                                      <th data-sortable="true" data-align="center">Section Code</th>
                                      <th data-sortable="true" data-align="center">Course Code</th>
                                      <th data-sortable="true" data-align="center">Units</th>
                                      <th data-sortable="true" data-align="center">Schedule</th>
                                      <th data-sortable="true" data-align="center">Room</th>
                                      <th data-sortable="true" data-align="center">Instructor</th>
                                      <th data-sortable="true" data-align="center">Advised by</th>
                                      <th data-sortable="true" data-align="center">DateTime</th>
                                      <th data-align="center">
                                        &nbsp; &nbsp;
                                        <input type="checkbox" class="form-control advised_check_all" checked>
                                        &nbsp; &nbsp;
                                      </th>
                                  </tr>
                              </thead>
                              <tbody>
                                @if(count($advised)>0)
                                    @php
                                    $x = 1;
                                    $total_units = 0;
                                    @endphp
                                    @foreach($advised as $row)
                                        <tr>
                                            <td class="center">{{$x}}</td>
                                            <td class="center">{{$row['program']}}</td>
                                            <td class="center">{{$row['section_code']}}</td>
                                            <td class="center">{{$row['course_code']}}</td>
                                            <td class="center">{{$row['units']}}</td>
                                            <td class="center">{!!$row['schedule']!!}</td>
                                            <td class="center">{!!$row['room']!!}</td>
                                            <td>{{$row['instructor']}}</td>
                                            <td>{{$row['advised_by']}}</td>
                                            <td class="center">{{$row['date_time']}}</td>
                                            <td class="center">
                                                @if($row['status']==NULL)
                                                    <input type="checkbox" class="form-control advisedCourseCheck" checked
                                                    data-id="{{$row['id']}}"
                                                    data-cid="{{$row['credit_course_id']}}">
                                                @else
                                                    <button class="btn btn-success btn-success-scan btn-xs">
                                                       Enrolled
                                                    </button>
                                                @endif
                                                
                                            </td>
                                        </tr>
                                        @php
                                        $x++;
                                        $total_units += $row['units'];
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <th colspan="4" class="center"><label>Total Unit:</label></th>
                                        <th class="center"><label id="total_unit_advised">{{$total_units}}</label></th>
                                        <th colspan="6"></th>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="11" class="center">No Advisement records found</td>
                                    </tr>
                                @endif
                              </tbody>
                              @if(count($advised)>0)
                                <tfoot>
                                        
                                </tfoot>
                              @endif
                        </table>
                        @if(count($advised)>0)
                            <button class="btn btn-success btn-success-scan" name="submit_advisement" style="width: 100%">
                                <span class="fa fa-check"></span> Enroll Student
                            </button>                            
                        @else
                            <button class="btn btn-success btn-success-scan" style="width: 100%" disabled>
                                <span class="fa fa-check"></span> Enroll Student
                            </button>
                        @endif
                    </div>
                </div>                
            </div>
            @endif
            @if($type!='add')
            <div class="tab-pane" id="curriculum" role="tabpanel">
            @else
            <div class="tab-pane fade show active" id="curriculum" role="tabpanel">
            @endif
                @php
                    $availability_check = '';
                    $year_level = '';
                    $course_units = 0;
                @endphp
                <label>Courses</label> 
                @if($type!='add') &nbsp;
                <button class="btn btn-info btn-info-scan btn-sm" id="courseAddModal">
                    <span class="fa fa-plus-square"></span> Add Course
                </button>
                    <label class="text-primary" style="float:right">Total Unit: 
                        <span id="courseTotalUnits"></span></label>
                @endif
                <table class="table table-bordered hide" style="font-size:11px;" id="courseAddedDiv">
                    <thead>
                        <th style="width: 10%">Program</th>
                        <th style="width: 10%">Section</th>
                        <th style="width: 10%">Course</th>
                        <th style="width: 5%">Units</th>
                        <th style="width: 25%">Schedule</th>
                        <th style="width: 10%">Room</th>
                        <th style="width: 20%">Instructor</th>
                        <th style="width: 5%"></th>
                        <th style="width: 5%"></th>
                    </thead>
                </table>
                @foreach($program_courses as $row)
                    @php
                    if($row['year_level']==$student->grade_level->name){
                        $checked_input = 'checked';
                    }else{
                        $checked_input = '';
                    }    
                    @endphp
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <label>{{$row['year_level']}}</label>
                        <div class="table-responsive" style="height:300px;">
                            <table class="table table-bordered" style="font-size:11px;">
                                <thead>
                                    <th style="width: 10%">Course Code</th>
                                    <th style="width: 18%">Descriptive Title</th>
                                    <th style="width: 3%">Units</th>
                                    <th style="width: 8%">Pre-req</th>
                                    <th style="width: 10%">Schedule</th>
                                    <th style="width: 7%">Room</th>
                                    <th style="width: 14%">Instructor</th>
                                    <th style="width: 5%">Status</th>
                                    <th style="width: 10%">Availability</th>
                                    <th style="width: 5%">                        
                                        <input type="checkbox" class="form-control year_check" value="{{$row['year_level1']}}" {{$checked_input}}>
                                    </th>
                                    <th style="width: 10%">Choose<br>another</th>
                                </thead>
                                <tbody>                    
                                    @foreach($row['courses'] as $list)
                                        @php
                                            $course_units+=$list['units'];
                                        @endphp
                                        <tr>
                                            <td class="center">{{$list['code']}}
                                                <span class="blue" id="course_code{{$list['offered_course_id']}}"></span></td>
                                            <td>{{$list['course']}}
                                                <span class="blue" id="course_name{{$list['offered_course_id']}}"></span></td>
                                            <td class="center">{{$list['units']}}
                                                <span class="blue" id="course_units{{$list['offered_course_id']}}"></span></td>
                                            <td class="center">{{$list['pre_name']}}
                                                <span class="blue" id="course_pre_name{{$list['offered_course_id']}}"></span></td>
                                            <td class="center">{!!$list['schedule']!!}
                                                <span class="blue" id="course_schedule{{$list['offered_course_id']}}"></span></td>
                                            <td class="center">{!!$list['room']!!}
                                                <span class="blue" id="course_room{{$list['offered_course_id']}}"></span></td>
                                            <td class="center">{{$list['instructor']}}
                                                <span class="blue" id="course_instructor{{$list['offered_course_id']}}"></span></td>
                                            <td class="center">{{$list['status']}}
                                                <span class="blue" id="course_status{{$list['offered_course_id']}}"></span></td>
                                            @if($list['availability']==1)
                                                <td class="center"><button class="btn btn-danger btn-danger-scan btn-xs">
                                                        {{$list['availability_name']}}</button></td>
                                                @if($list['availability_name']=='Full')
                                                    <td>
                                                        <input type="checkbox" class="form-control courseCheck hide" 
                                                            id="course_checked{{$list['offered_course_id']}}"
                                                            value="{{$row['year_level1']}}"
                                                            data-id="{{$list['offered_course_id']}}"
                                                            data-u="{{$list['units']}}"
                                                            data-cid="">
                                                    </td>
                                                    <td><button class="btn btn-info btn-info-scan btn-xs courseAnotherModal"
                                                        data-id="{{$list['offered_course_id']}}">
                                                        <span class="fa fa-refresh"></span> Another</button></td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                            @elseif($list['availability']==2)
                                                <td class="center"><button class="btn btn-primary btn-primary-scan btn-xs">
                                                        {{$list['availability_name']}}</button></td>
                                                <td></td>
                                                <td></td>
                                            @elseif($list['availability']==3)
                                                <td class="center"><button class="btn btn-danger btn-danger-scan btn-xs">
                                                        {{$list['availability_name']}}</button></td>
                                                <td></td>
                                                <td></td>
                                            @else
                                                <td class="center"><button class="btn btn-success btn-success-scan btn-xs">
                                                        {{$list['availability_name']}}</button><br>
                                                    @if($list['course_conflict']!='')
                                                    <button class="btn btn-danger btn-danger-scan btn-xs"
                                                        id="course_conflict{{$list['offered_course_id']}}">
                                                        Conflict with<br>{{$list['course_conflict']}}</button>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(($type!='add' && ($year_level=='' || $year_level==$row['year_level1'])) || $list['course_conflict']=='')
                                                        @if($course_units>$row['unit_limit'])
                                                            <input type="checkbox" class="form-control courseCheck course_check{{$row['year_level1']}}" 
                                                                id="course_checked{{$list['offered_course_id']}}"
                                                                value="{{$row['year_level1']}}"
                                                                data-id="{{$list['offered_course_id']}}"
                                                                data-u="{{$list['units']}}"
                                                                data-cid=""
                                                                {{$checked_input}}>
                                                        @else
                                                            <input type="checkbox" class="form-control courseCheck course_check{{$row['year_level1']}}" 
                                                                id="course_checked{{$list['offered_course_id']}}"
                                                                value="{{$row['year_level1']}}"
                                                                data-id="{{$list['offered_course_id']}}"
                                                                data-u="{{$list['units']}}"
                                                                data-cid="">
                                                        @endif
                                                    @else
                                                        <input type="checkbox" class="form-control courseCheck hide course_check{{$row['year_level1']}}" 
                                                            id="course_checked{{$list['offered_course_id']}}"
                                                            value="{{$row['year_level1']}}"
                                                            data-id="{{$list['offered_course_id']}}"
                                                            data-u="{{$list['units']}}"
                                                            data-cid="">
                                                    @endif
                                                </td>
                                                <td>@if($year_level!='' && $year_level!=$row['year_level1'] && $list['course_conflict']!='')
                                                        <button class="btn btn-info btn-info-scan btn-xs courseAnotherModal"
                                                            data-id="{{$list['offered_course_id']}}">
                                                            <span class="fa fa-refresh"></span> Another</button>
                                                    @endif
                                                </td>                                
                                            @endif
                                            @php
                                                if(($list['course_conflict']!='' && $list['availability']==0 && $availability_check=='') || $list['availability_name']=='Ongoing'){
                                                    $year_level = $row['year_level1'];
                                                    $availability_check = 1;
                                                }
                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
                @if($type!='add')
                <button class="btn btn-success btn-success-scan" name="submit_curriculum" style="width: 100%"><span class="fa fa-check"></span> Enroll Student</button>
                @endif
            </div>
        </div>
    </div>
</div>