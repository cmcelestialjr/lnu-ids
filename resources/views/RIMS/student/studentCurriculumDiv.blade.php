@if(isset($btn_user_this))
@if($btn_user_this=='1' && $system_selected=='RIMS')
<div class="row">
    <div class="col-md-12" style="padding-top:.3rem">
        <button class="btn btn-success btn-success-scan" id="useThisCurriculum" style="width: 100%"><span class="fa fa-check"></span> Use this curriculum</button>
    </div>
</div>
@endif
@endif
@if($specialization->count()>0 && $system_selected=='RIMS')
<div class="row">
    <div class="col-md-4">
        <label>Specialization</label>
        <select class="form-control select2-div" id="specialization_name_select">
            <option value="">Please select</option>
            @foreach($specialization as $row)
                @if($specialization_name==$row->specialization_name)
                    <option value="{{$row->specialization_name}}" selected>{{$row->specialization_name}}</option>
                @else
                    <option value="{{$row->specialization_name}}">{{$row->specialization_name}}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>
@endif
<table class="table">
    <tr>
        <td style="width: 65%">
        @foreach($query as $row)
            <div class="card card-primary card-outline">
                <div class="card-body table-responsive">
                    <label>{{$row['year_level']}}</label>
        
                    <div class="row">
                        @foreach($row['grade_period'] as $grade)
                            @if(count($grade['courses'])>0)
                                <div class="col-lg-6">
                                    {{$grade['grade_period']}}
                                    {{-- <div class="card card-info card-outline">
                                        <div class="card-body"> --}}
                                            <div class="table-responsive">
                                                @php
                                                $lab_total = 0;
                                                foreach($grade['courses'] as $course){
                                                    $lab_total += $course['lab'];
                                                }
                                                @endphp
                                                <table class="table table-bordered" style="font-size:10px">
                                                    <thead>
                                                        <th style="width: 25%">Course Code</th>
                                                        <th style="width: 40%">Descriptive Title</th>
                                                        @if($lab_total>0)
                                                            <th style="width: 5%">Unit</th>
                                                            <th style="width: 5%">Lab</th>
                                                        @else
                                                            <th style="width: 10%">Unit</th>
                                                        @endif
                                                        {{-- <th style="width: 15%">Pre-req</th> --}}
                                                        <th style="width: 20%">Status</th>
                                                        @if($system_selected!='SIMS')
                                                        <th style="width: 10%"></th>
                                                        @endif
                                                    </thead>
                                                    <tbody>
                                                        @foreach($grade['courses'] as $courses)
                                                            <tr>
                                                                <td class="center" style="padding:3px">{{$courses['code']}}
                                                                    @if($courses['course_other']!=NULL)
                                                                    <br>
                                                                    <span class="text-primary">                                                                    
                                                                        {{$courses['course_other']->course_code}}
                                                                        <span class="fa fa-times text-require studentCreditRemove" style="cursor:pointer"
                                                                            data-id="{{$courses['id']}}"></span>
                                                                    </span>
                                                                    @endif
                                                                </td>
                                                                <td style="padding:3px">{{$courses['name']}}
                                                                    @if($courses['course_other']!=NULL)
                                                                    <br>
                                                                    <span class="text-primary" style="font-size:9px">                                                                        
                                                                        {{$courses['course_other']->course_desc}}
                                                                    </span>
                                                                    @endif
                                                                </td>
                                                                <td class="center" style="padding:3px">{{$courses['units']}}
                                                                    @if($courses['course_other']!=NULL)
                                                                    <br>
                                                                    <span class="text-primary" style="font-size:9px">
                                                                        
                                                                        {{$courses['course_other']->course_units}}
                                                                    </span>
                                                                    @endif
                                                                </td>
                                                                @if($lab_total>0)
                                                                <td class="center" style="padding:3px">{{$courses['lab']}}
                                                                    @if($courses['course_other']!=NULL)
                                                                    <br>
                                                                    <span class="text-primary" style="font-size:9px">
                                                                        
                                                                        {{$courses['course_other']->lab_units}}
                                                                    </span>
                                                                    @endif
                                                                </td>
                                                                @endif
                                                                {{-- <td>
                                                                    @if(count($courses['pre_req'])>0)
                                                                    <button class="btn btn-primary btn-primary-scan btn-xs coursePrereq"
                                                                            data-id="{{$courses['id']}}"
                                                                            style="font-size:10px;">{{$courses['pre_name']}}</button>
                                                                    @else
                                                                        {{$courses['pre_name']}}
                                                                    @endif
                                                                </td> --}}
                                                                <td class="center" style="padding:3px">{!!$courses['status']!!}</td>
                                                                @if($system_selected!='SIMS')
                                                                <td class="center" style="padding:3px">
                                                                    @if($courses['student_course_status']==NULL)
                                                                        <input type="checkbox" class="form-control courses_curriculum" 
                                                                        data-id="{{$courses['id']}}"
                                                                        style="width: 25px">
                                                                    @elseif($courses['course_other']!=NULL)
                                                                        {{-- @if($courses['course_other']->school_year_id==NULL) --}}
                                                                        <input type="checkbox" class="form-control courses_curriculum" 
                                                                            data-id="{{$courses['id']}}"
                                                                            style="width: 25px">
                                                                        {{-- @endif --}}
                                                                    @endif
                                                                </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        {{-- </div>
                                    </div> --}}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
        </td>
        <td id="courses_other_td" class="hide" style="width: 35%">
            <div class="card card-primary card-outline">
                <div class="card-body table-responsive">
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered" style="font-size:10px;">
                                <thead>
                                    <td class="center" style="padding:4px"><label>School Term & <br>Course No.</label></td>
                                    <td class="center" style="padding:4px"><label>Descriptive Title</label></td>
                                    <td class="center" style="padding:4px"><label>Final Rating</label></td>
                                    <td class="center" style="padding:4px"><label>Re-Exam Units</label></td>
                                    <td></td>
                                </thead>
                                <tbody>
                                    @php
                                        $school_name_old = '';
                                        $program_shorten_old = '';
                                        $grade_period_old = '';
                                    @endphp         
                                    @foreach($course_other as $row)
                                        @foreach($row['courses'] as $courses)
                                        @php
                                            $school_name = $row['school_name'].', ';
                                            if($school_name_old!=''){
                                                if($school_name==$school_name_old){
                                                    $school_name = '';
                                                }
                                            }              
                                            
                                            $grade_period = $row['grade_period'];
                                            if($courses->option!=NULL){
                                                $grade_period = str_replace('Semester','',$row['grade_period']).' '.$courses->option;
                                            }
                                            $program_shorten = $row['program_shorten'];
                                            if($program_shorten_old!=''){
                                                if(($program_shorten==$program_shorten_old && $school_name==$school_name_old) || $school_name==''){
                                                    $program_shorten = '';
                                                }
                                            }
                                        @endphp
                                        @if($grade_period!=$grade_period_old)
                                        <tr>
                                            <td colspan="5" style="padding:4px">
                                                <label><u>{{$grade_period}} S.Y. {{$row['period']}} {{$school_name}}{{$program_shorten}}</u></label>
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td class="center" style="padding:4px">
                                                {{$courses->course_code}}
                                            </td>
                                            <td style="padding:4px">
                                                {{$courses->course_desc}}
                                            </td>
                                            <td class="center" style="padding:4px">
                                                {{$courses->final_grade}}
                                            </td>
                                            <td class="center" style="padding:4px">
                                                @if($courses->grade<='3' && ($courses->grade!=NULL || $courses->grade!=''))
                                                    {{$courses->course_units}}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                            <td class="center" style="padding:4px">
                                                @if(in_array($courses->student_course_status_id,$passed_statuses))
                                                    <input type="checkbox" class="form-control courses_other" 
                                                        data-id="{{$courses->id}}"
                                                        style="width: 25px">
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $school_name_old = $row['school_name'].', ';
                                            $program_shorten_old = $row['program_shorten'];
                                            $grade_period_old = $grade_period;
                                        @endphp
                                        @endforeach                
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>