<div class="row" id="curriculumTable">
    @foreach($year_level as $level)
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <label>{{$level->name}}</label>
                <div class="row">
                    @foreach($period as $per)
                        @php
                        $course_count = 0;
                        foreach($per->courses as $course){
                            if($level->id==$course->grade_level_id){
                                $course_count++;
                            }
                        }
                        @endphp
                        @if($course_count>0)
                            @if($per->name=='Year')
                            <div class="col-lg-12">
                            @else
                            <div class="col-lg-6">
                            @endif
                                @php
                                $lab_total = 0;
                                $unit_total = 0;
                                foreach($per->courses as $course){
                                    if($level->id==$course->grade_level_id){
                                        $lab_total += $course->lab;
                                    }
                                }
                                @endphp
                                <div class="card card-info card-outline">
                                    <div class="card-body table-responsive">
                                        {{$per->name}}
                                        <table class="table table-bordered" style="font-size:11px;">
                                            <thead>
                                                @if($lab_total>0)
                                                <th style="width: 11%;padding:7px">Course Code</th>
                                                <th style="width: 50%;padding:7px">Descriptive Title</th>
                                                <th style="width: 7%;padding:7px">Units</th>
                                                <th style="width: 7%;padding:7px">Lab</th>
                                                <th style="width: 15%;padding:7px">Pre-req</th>
                                                <th style="width: 10%;padding:7px">Status</th>
                                                @else
                                                <th style="width: 15%;padding:7px">Course Code</th>
                                                <th style="width: 50%;padding:7px">Descriptive Title</th>
                                                <th style="width: 10%;padding:7px">Units</th>
                                                <th style="width: 15%;padding:7px">Pre-req</th>
                                                <th style="width: 10%;padding:7px">Status</th>
                                                @endif
                                            </thead>
                                            <tbody>
                                                @foreach($per->courses as $course)
                                                    @if($level->id==$course->grade_level_id)
                                                    <tr>
                                                        <td style="padding:7px" class="center">
                                                            @if($user_access_level==1 || $user_access_level==2)
                                                                <button class="btn btn-primary btn-primary-scan btn-xs courseUpdate"
                                                                        data-id="{{$course->id}}">{{$course->code}}</button>
                                                            @else
                                                                {{$course->code}}
                                                            @endif
                                                        </td>
                                                        <td style="padding:7px">{{$course->name}}</td>
                                                        <td style="padding:7px" class="center">{{$course->units}}</td>
                                                        @if($lab_total>0)
                                                        <td style="padding:7px" class="center">{{$course->lab}}</td>
                                                        @endif
                                                        <td style="padding:7px" class="center">
                                                            {{-- @if(count($course->pre_req)>0)
                                                             <button class="btn btn-info btn-info-scan btn-xs coursePrereq"
                                                                    data-id="{{$course->id}}">{{$course->pre_name}}</button>
                                                            @else
                                                                {{$course->pre_name}}
                                                            @endif --}}
                                                            @if($course->pre_name=='')None @else{{$course->pre_name}}@endif
                                                        </td>
                                                        <td style="padding:7px" class="center">
                                                            @php
                                                                if($user_access_level==1 || $user_access_level==2){
                                                                    $courseStatus = 'courseStatus';
                                                                }else{
                                                                    $courseStatus = '';
                                                                }
                                                            @endphp
                                                            @if($course->status_id==1)
                                                                <button class="btn btn-success btn-success-scan btn-xs {{$courseStatus}}"
                                                                    data-id="{{$course->id}}">{{$course->status->name}}</button>
                                                            @else
                                                                <button class="btn btn-danger btn-danger-scan btn-xs {{$courseStatus}}"
                                                                    data-id="{{$course->id}}">{{$course->status->name}}</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $unit_total += $course->units;
                                                    @endphp
                                                    @endif
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <td style="padding:7px" colspan="2" class="center">Total</td>
                                                <td style="padding:7px" class="center">{{$unit_total}}</td>
                                                @if($lab_total>0)
                                                    <td style="padding:7px" class="center">{{$lab_total}}</td>
                                                @endif
                                                <td style="padding:7px" colspan="2"></td>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
