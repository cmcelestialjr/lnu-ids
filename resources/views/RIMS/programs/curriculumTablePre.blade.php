<div class="row">
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
                                                <th style="width: 11%">Course Code</th>
                                                <th style="width: 50%">Descriptive Title</th>
                                                <th style="width: 7%">Units</th>
                                                <th style="width: 7%">Lab</th>
                                                <th style="width: 15%">Pre-req</th>
                                                <th style="width: 10%">Option</th>
                                                @else
                                                <th style="width: 15%">Course Code</th>
                                                <th style="width: 50%">Descriptive Title</th>
                                                <th style="width: 10%">Units</th>
                                                <th style="width: 15%">Pre-req</th>
                                                <th style="width: 10%">Option</th>
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
                                                            @if(in_array($course->id,$pre_req))
                                                                <input type="checkbox" data-val="{{$course->code}}" value="{{$course->id}}" class="form-control courses" checked>
                                                            @else
                                                                <input type="checkbox" data-val="{{$course->code}}" value="{{$course->id}}" class="form-control courses">
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
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>