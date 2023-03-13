<div class="row" id="curriculumTable">
    @foreach($year_level as $level)    
    <div class="col-lg-12"> 
        <label>{{$level->name}}</label>
        <div class="row">
        @foreach($period as $per)
        <div class="col-lg-6">  
            <div class="card card-info card-outline">
                <div class="card-body">
                    {{$per->name}}
                    <table class="table table-bordered" style="font-size:11px;">
                        <thead>
                            <th style="width: 15%">Course Code</th>
                            <th style="width: 50%">Descriptive Title</th>
                            <th style="width: 10%">Units</th>
                            <th style="width: 15%">Pre-req</th>
                            <th style="width: 10%">Status</th>
                        </thead>
                        <tbody>
                            @foreach($per->courses as $course)
                                @if($level->id==$course->grade_level_id)
                                <tr>
                                    <td class="center">{{$course->code}}</td>
                                    <td>{{$course->name}}</td>
                                    <td class="center">{{$course->units}}</td>
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
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
        </div>
    </div>
    @endforeach
</div>