<br>
<div class="row">
    @foreach($year_level as $level)    
    <div class="col-lg-12"> 
        <div class="card card-primary card-outline">
            <div class="card-body">
        <label>{{$level->name}}</label>
        <div class="table-responsive" style="height: 250px;">
            <div class="row">
                @foreach($period as $per)
                    <div class="col-lg-12"> 
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
                                    <th style="width: 11%">Course Code</th>
                                    <th style="width: 50%">Descriptive Title</th>
                                    <th style="width: 7%">Units</th>
                                    <th style="width: 7%">Lab</th>
                                    <th style="width: 15%">Pre-req</th>
                                    <th style="width: 10%">Status</th>
                                    @else
                                    <th style="width: 15%">Course Code</th>
                                    <th style="width: 50%">Descriptive Title</th>
                                    <th style="width: 10%">Units</th>
                                    <th style="width: 15%">Pre-req</th>
                                    <th style="width: 10%">Status</th>
                                    @endif                                    
                                </thead>
                                <tbody>
                                    @foreach($per->courses as $course)
                                        @if($level->id==$course->grade_level_id)
                                        <tr>
                                            <td class="center">
                                                {{$course->code}}
                                            </td>
                                            <td>{{$course->name}}</td>
                                            <td class="center">{{$course->units}}</td>
                                            @if($lab_total>0)
                                            <td class="center">{{$course->lab}}</td>
                                            @endif
                                            <td class="center">{{$course->pre_name}}</td>
                                            <td class="center">
                                                @php
                                                    if($user_access_level==1 || $user_access_level==2){
                                                        $courseStatus = 'courseStatus';
                                                    }else{
                                                        $courseStatus = '';
                                                    }
                                                @endphp
                                                @if($course->status_id==1)
                                                    <button class="btn btn-success btn-success-scan btn-sm {{$courseStatus}}"
                                                            data-id="{{$course->id}}">{{$course->status->name}}</button>
                                                @else
                                                    <button class="btn btn-danger btn-danger-scan btn-sm {{$courseStatus}}"
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
                                    <td colspan="2" class="center">Total</td>
                                    <td class="center">{{$unit_total}}</td>
                                    @if($lab_total>0)
                                        <td class="center">{{$lab_total}}</td>
                                    @endif
                                    <td colspan="2"></td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
            </div>
        </div>
    </div>
    @endforeach
</div>