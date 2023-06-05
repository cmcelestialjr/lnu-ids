@php
    $availability_check = '';
    $year_level = '';
    $course_units = 0;
    $course_add_table = 'hide';
    if(count($course_add)>0){
        $course_add_table = '';
    }
@endphp
<table class="table table-bordered {{$course_add_table}}" style="font-size:11px;" id="courseAddedDiv">
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
    <tbody>
      @foreach($course_add as $row)
        <tr>
          <td class="center">{{$row['program']}}</td>
          <td class="center">{{$row['section']}}</td>
          <td class="center">{{$row['code']}}</td>
          <td class="center"><span class="courseUnits">{{$row['units']}}</span></td>
          <td class="center">{!!$row['schedule']!!}</td>
          <td class="center">{!!$row['room']!!}</td>
          <td class="center">{{$row['instructor']}}</td>
          @if(($row['advised_by']!=$instructor_id) || $row['status']=='1')
            <td class="center"><input type="checkbox" class="form-control" checked disabled>
                @if($row['status']==1)
                    (Enrolled)
                @endif
            </td>
            <td class="center">
                Advised by<br>{{$row['advised_by_name']}}            
            </td>
          @else
          <td class="center"><input type="checkbox" class="form-control courseCheck" data-id="{{$row['id']}}" data-u="{{$row['units']}}" data-cid="" checked></td>
          <td class="center">
            Advised by<br>{{$row['advised_by_name']}}<br>
            <button class="btn btn-danger btn-danger-scan btn-xs" name="remove">Remove</button></td>
          @endif
        </tr>
      @endforeach
    </tbody>
  </table>
@foreach($query as $row)
    <label>{{$row['year_level']}}</label>
    <div class="card card-primary card-outline">
        <div class="card-body table-responsive">
            <div class="row">
                @php
                if($row['year_level']==$student->grade_level->name){
                    $checked_input = 'checked';
                }else{
                    $checked_input = '';
                }    
                @endphp
                @foreach($row['grade_period'] as $grade)                    
                    @if($query_school_year->grade_period->name==$grade['grade_period'])
                    <div class="col-lg-8" style="height:350px">
                    @else
                    <div class="col-lg-4" style="height:350px">
                    @endif
                        {{$grade['grade_period']}}
                        <div class="card card-info card-outline">
                            <div class="card-body">
                                <div class="table-responsive" style="height:300px">
                                @foreach($grade['courses'] as $courses)
                                    @php
                                    if($courses['availability']==3){
                                        $option = 'wo';
                                    }else{
                                        $option = 'w';
                                    }
                                    @endphp
                                @endforeach
                                <table class="table table-bordered" id="programCoursesDiv" style="font-size:11px;">
                                    <thead>
                                        <th>Course Code</th>
                                        <th>Descriptive Title</th>
                                        <th>Units</th>
                                        <th>Pre-req</th>
                                        <th>Status</th>
                                        @if($option=='w')
                                        <th>Schedule</th>
                                        <th>Room</th>
                                        <th>Instructor</th>
                                        <th>Availability</th>
                                        <th>
                                            <input type="checkbox" class="form-control year_check" value="{{$row['year_level1']}}" {{$checked_input}}
                                                style="width: 30px;height:30px">
                                        </th>
                                        <th>Choose<br>another</th>
                                        @endif
                                    </thead>
                                    <tbody>
                                        @foreach($grade['courses'] as $list)
                                            <tr>
                                                <td class="center">{{$list['code']}} {{$list['advised']}}
                                                    <span class="blue" id="course_code{{$list['offered_course_id']}}"></span></td>
                                                <td>{{$list['name']}}
                                                    <span class="blue" id="course_name{{$list['offered_course_id']}}"></span></td>
                                                <td class="center">{{$list['units']}}
                                                    <span class="blue" id="course_units{{$list['offered_course_id']}}"></span></td>
                                                <td class="center">{{$list['pre_name']}}
                                                    <span class="blue" id="course_pre_name{{$list['offered_course_id']}}"></span></td>
                                                <td>{!!$list['status']!!}</td>
                                                @if($list['availability']!=3)
                                                    <td class="center">{!!$list['schedule']!!}
                                                        <span class="blue" id="course_schedule{{$list['offered_course_id']}}"></span></td>
                                                    <td class="center">{!!$list['room']!!}
                                                        <span class="blue" id="course_room{{$list['offered_course_id']}}"></span></td>
                                                    <td class="center">{{$list['instructor']}}
                                                        <span class="blue" id="course_instructor{{$list['offered_course_id']}}"></span></td>
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
                                                            {{$list['availability_name']}}</button>
                                                            <br>(Enrolled)
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    @elseif($list['availability']==3)
                                                        <td class="center"><button class="btn btn-danger btn-danger-scan btn-xs">
                                                                {{$list['availability_name']}}</button></td>
                                                        <td></td>
                                                        <td></td>
                                                    @else
                                                        @if($list['advised']==1)
                                                            <td class="center">
                                                                <button class="btn btn-primary btn-primary-scan btn-xs">
                                                                    Advised by<br>{{$list['advised_by_name']}}
                                                                </button></td>
                                                            <td>
                                                                @if(($list['advised_by']!='' && $list['advised_by']!=$instructor_id) || $list['advised_status']=='1')
                                                                <input type="checkbox" class="form-control"
                                                                        value="{{$row['year_level1']}}" checked disabled>
                                                                    @if($list['advised_status']==1)
                                                                        (Enrolled)
                                                                    @endif
                                                                @else
                                                                <input type="checkbox" class="form-control courseCheck course_check{{$row['year_level1']}}" 
                                                                        id="course_checked{{$list['offered_course_id']}}"
                                                                        value="{{$row['year_level1']}}"
                                                                        data-id="{{$list['offered_course_id']}}"
                                                                        data-u="{{$list['units']}}"
                                                                        data-cid="{{$list['credit_course_id']}}"
                                                                        checked>
                                                                @endif
                                                            </td>
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
                                                                    <input type="checkbox" class="form-control courseCheck course_check{{$row['year_level1']}}" 
                                                                        id="course_checked{{$list['offered_course_id']}}"
                                                                        value="{{$row['year_level1']}}"
                                                                        data-id="{{$list['offered_course_id']}}"
                                                                        data-u="{{$list['units']}}"
                                                                        data-cid=""
                                                                        {{$checked_input}}>
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
                                                    @endif
                                                    @php
                                                        if(($list['course_conflict']!='' && $list['availability']==0 && $availability_check=='') || $list['availability_name']=='Ongoing'){
                                                            $year_level = $row['year_level1'];
                                                            $availability_check = 1;
                                                        }
                                                    @endphp
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endforeach
<button class="btn btn-success btn-success-scan" name="submit_advisement" style="width:100%">
    <span class="fa fa-check"></span> Submit Advisement
</button>