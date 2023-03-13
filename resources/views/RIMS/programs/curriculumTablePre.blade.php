<div class="row table-responsive" style="height:400px;">
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
                            <th style="width: 10%">Option</th>
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
                                        <input type="checkbox" data-val="{{$course->code}}" value="{{$course->id}}" class="form-control courses">
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