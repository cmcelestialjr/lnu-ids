@php
    $availability_check = '';
    $year_level = '';
    $course_units = 0;
    $course_add_table = 'hide';
    $disabled = 'disabled';
    $limit_units = 26;
    if($student->program_level_id>=7){
        $disabled = '';
    }
    if(count($course_add)>0){
        $course_add_table = '';
    }
    if($checkStudentPreenroll!=NULL){
        $disabled = 'disabled';
    }
@endphp
<div class="row">
    <div class="col-lg-12">
        @if($checkStudentPreenroll!=NULL)<br>
            <div class="alert alert-success center">You are already Pre-enrolled</div>
        @endif
        <input type="hidden" name="curriculum_id_selected" value="{{$offered_curriculum_id}}" readonly>
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
                @if($row['status']=='1')
                    <td class="center"><input type="checkbox" class="form-control" checked disabled>
                        @if($row['status']==1)
                            (Enrolled)
                        @endif
                    </td>
                    <td class="center">
                        {!!$row['advised_by_name']!!}            
                    </td>
                @else
                <td class="center"><input type="checkbox" class="form-control courseCheck" data-id="{{$row['id']}}" data-u="{{$row['units']}}" data-cid="" checked></td>
                <td class="center">
                    {!!$row['advised_by_name']!!}<br>
                    <button class="btn btn-danger btn-danger-scan btn-xs" name="remove">Remove</button></td>
                @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@php
$course_availability = 0;
$total_units_get = 0;
$unit_x = 0;
@endphp
<div class="row">
@foreach($query as $row)
    <div class="col-lg-6">
        <label>{{$row['year_level']}}</label>
        <div class="card card-primary card-outline">
            <div class="card-body table-responsive">
                <div class="row">
                    @php
                    if($row['year_level']==$student->grade_level->name && $checkStudentPreenroll==NULL){
                        $checked_input = 'checked';
                    }else{
                        $checked_input = '';
                    }
                    @endphp
                    @foreach($row['grade_period'] as $grade)
                        @if(count($grade['courses'])>0)
                            <div class="col-lg-12">
                                {{$grade['grade_period']}}
                                {{-- <div class="card card-info card-outline">
                                    <div class="card-body"> --}}
                                        <div class="table-responsive">
                                        @php
                                            $availability_total = 0;
                                            foreach($grade['courses'] as $courses){
                                                if($courses['availability']==3){
                                                    $option = 'wo';
                                                }else{
                                                    $option = 'w';
                                                }
                                                if($courses['availability']==0){
                                                    $availability_total++;
                                                }                                            
                                            }
                                            if($year_level_id_selected==$row['grade_level_id']){
                                                if($availability_total==count($courses_id_selected)){
                                                    $course_availability = 1;
                                                }
                                            }
                                        @endphp
                                        <table class="table table-bordered" id="programCoursesDiv" style="font-size:11px;">
                                            <thead>
                                                <th>Course Code</th>
                                                <th>Descriptive Title</th>
                                                <th>Units</th>
                                                <th>Pre-req</th>
                                                <th>Status</th>
                                                @if($option=='w')
                                                <th style="width: 30px">
                                                    {{-- <input type="checkbox" class="form-control year_check" value="{{$row['year_level1']}}" {{$checked_input}}
                                                        style="width: 30px;height:30px"> --}}
                                                </th>
                                                @endif
                                            </thead>
                                            <tbody>
                                                @foreach($grade['courses'] as $list)                                                
                                                    <tr>
                                                        <td class="center">{{$list['code']}}
                                                            <span class="blue"></span></td>
                                                        <td>{{$list['name']}}
                                                            <span class="blue"></span></td>
                                                        <td class="center">{{$list['units']}}
                                                            <span class="blue"></span></td>
                                                        <td class="center">{{$list['pre_name']}}
                                                            <span class="blue"></span></td>
                                                        <td class="center">{!!$list['status']!!}</td>
                                                        @if($list['availability']!=3)
                                                            @if($list['availability']==1)
                                                                @if($list['availability_name']=='Full')
                                                                    <td>
                                                                        <input type="checkbox" class="form-control courseCheck hide"
                                                                            value="{{$row['year_level1']}}"
                                                                            data-ci="{{$list['course_id']}}"
                                                                            data-op="{{$course_availability}}"
                                                                            data-u="{{$list['units']}}"
                                                                            data-cid="">
                                                                    </td>
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                            @elseif($list['availability']==2 || $list['availability']==3 || $list['availability']==4)
                                                                <td></td>
                                                            @elseif($list['availability']==5)
                                                                <td>
                                                                    <input type="checkbox" class="form-control" checked disabled>
                                                                </td>
                                                            @else
                                                                @if($list['advised']==1)
                                                                    <td>
                                                                        @if($list['advised_by']!='' || $list['advised_status']=='1')
                                                                        <input type="checkbox" class="form-control"
                                                                                value="{{$row['year_level1']}}" checked disabled>
                                                                            @if($list['advised_status']==1)
                                                                                (Enrolled)
                                                                            @endif
                                                                        @else
                                                                        <input type="checkbox" class="form-control courseCheck course_check{{$row['year_level1']}}"
                                                                                value="{{$row['year_level1']}}"
                                                                                data-ci="{{$list['course_id']}}"
                                                                                data-op="{{$course_availability}}"
                                                                                data-u="{{$list['units']}}"
                                                                                data-cid="{{$list['credit_course_id']}}"
                                                                                checked {{$disabled}}>
                                                                        @endif
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        @if($year_level_id_selected==$row['grade_level_id'])
                                                                            @php
                                                                            $total_units_get += $list['units'];
                                                                            @endphp
                                                                            @if(($type!='add' && ($year_level=='' || $year_level==$row['year_level1'])) || $list['course_conflict']=='')
                                                                                <input type="checkbox" class="form-control courseCheck course_check{{$row['year_level1']}}" 
                                                                                    value="{{$row['year_level1']}}"
                                                                                    data-ci="{{$list['course_id']}}"
                                                                                    data-op="{{$course_availability}}"
                                                                                    data-u="{{$list['units']}}"
                                                                                    data-cid=""
                                                                                    {{$checked_input}} {{$disabled}}>
                                                                            @else
                                                                                <input type="checkbox" class="form-control courseCheck hide course_check{{$row['year_level1']}}"
                                                                                    value="{{$row['year_level1']}}"
                                                                                    data-ci="{{$list['course_id']}}"
                                                                                    data-op="{{$course_availability}}"
                                                                                    data-u="{{$list['units']}}"
                                                                                    data-cid="">
                                                                            @endif
                                                                        @else
                                                                            @if($course_availability==0)
                                                                                @php
                                                                                $total_units_get += $list['units'];
                                                                                @endphp
                                                                                @if($total_units_get<=$limit_units)
                                                                                    @if(($type!='add' && ($year_level=='' || $year_level==$row['year_level1'])) || $list['course_conflict']=='')
                                                                                        <input type="checkbox" class="form-control courseCheck course_check{{$row['year_level1']}}"
                                                                                            value="{{$row['year_level1']}}"
                                                                                            data-ci="{{$list['course_id']}}"
                                                                                            data-op="{{$course_availability}}"
                                                                                            data-u="{{$list['units']}}"
                                                                                            data-cid=""
                                                                                            {{$checked_input}} {{$disabled}}>
                                                                                    @else
                                                                                        <input type="checkbox" class="form-control courseCheck hide course_check{{$row['year_level1']}}"
                                                                                            value="{{$row['year_level1']}}"
                                                                                            data-ci="{{$list['course_id']}}"
                                                                                            data-op="{{$course_availability}}"
                                                                                            data-u="{{$list['units']}}"
                                                                                            data-cid="">
                                                                                    @endif
                                                                                @endif
                                                                            @endif
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
                                {{-- </div>
                            </div> --}}
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endforeach
</div>
@if($checkStudentPreenroll==NULL)
<button class="btn btn-success btn-success-scan" name="submit_advisement" style="width:100%">
    <span class="fa fa-check"></span> Submit Pre-enroll
</button>
@endif