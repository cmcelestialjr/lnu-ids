<div class="table-responsive table-container">
<table class="table" id="courses_main_table" style="overflow: auto;">
    <tr>
        <td>
        @foreach($query as $row)
            <div class="card card-primary card-outline">
                <div class="card-body table-responsive">
                    <label>{{$row['year_level']}}</label>

                    <div class="row">
                        @foreach($row['grade_period'] as $grade)
                            @if(count($grade['courses'])>0)
                                <div class="col-lg-12">
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
                                                                        {{$courses['course_other']->student_course->course_code}}
                                                                        @if($system_selected=='RIMS')
                                                                        <span class="fa fa-times text-require studentCreditRemove" style="cursor:pointer"
                                                                            data-id="{{$courses['id']}}" data-crid="{{$courses['course_other']->student_course->id}}"></span>
                                                                        @endif
                                                                    </span>
                                                                    @endif
                                                                </td>
                                                                <td style="padding:3px">{{$courses['name']}}
                                                                    @if($courses['course_other']!=NULL)
                                                                    <br>
                                                                    <span class="text-primary" style="font-size:9px">
                                                                        {{$courses['course_other']->student_course->course_desc}}
                                                                    </span>
                                                                    @endif
                                                                </td>
                                                                <td class="center" style="padding:3px">{{$courses['units']}}
                                                                    @if($courses['course_other']!=NULL)
                                                                    <br>
                                                                    <span class="text-primary" style="font-size:9px">

                                                                        {{$courses['course_other']->student_course->course_units}}
                                                                    </span>
                                                                    @endif
                                                                </td>
                                                                @if($lab_total>0)
                                                                <td class="center" style="padding:3px">{{$courses['lab']}}
                                                                    @if($courses['course_other']!=NULL)
                                                                    <br>
                                                                    <span class="text-primary" style="font-size:9px">

                                                                        {{$courses['course_other']->student_course->lab_units}}
                                                                    </span>
                                                                    @endif
                                                                </td>
                                                                @endif
                                                                <td class="center" style="padding:3px">{!!$courses['status']!!}</td>
                                                                @if($system_selected!='SIMS')
                                                                <td class="center" style="padding:3px">
                                                                    @if($courses['student_course_status']==NULL)
                                                                        <input type="checkbox" class="form-control courseCheck"
                                                                        data-id="{{$courses['id']}}"
                                                                        style="width: 25px">
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
    </tr>
</table>
</div>
