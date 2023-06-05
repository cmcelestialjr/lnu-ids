@foreach($query as $row)
    <label>{{$row['year_level']}}</label>
    <div class="card card-primary card-outline">
        <div class="card-body table-responsive">
            <div class="row">
                @foreach($row['grade_period'] as $grade)                    
                    <div class="col-lg-6" style="height:350px">
                        {{$grade['grade_period']}}
                        <div class="card card-info card-outline">
                            <div class="card-body">
                                <div class="table-responsive" style="height:300px">
                                    @php
                                    $lab_total = 0;
                                    foreach($grade['courses'] as $course){
                                        $lab_total += $course['lab'];
                                    }
                                    @endphp
                                    <table class="table table-bordered" style="font-size:11px;">
                                        <thead>
                                            <th style="width: 15%">Course Code</th>
                                            <th style="width: 40%">Descriptive Title</th>
                                            @if($lab_total>0)
                                                <th style="width: 5%">Units</th>
                                                <th style="width: 5%">Lab</th>
                                            @else
                                                <th style="width: 10%">Units</th>
                                            @endif                                            
                                            <th style="width: 15%">Pre-req</th>
                                            <th style="width: 20%">Status</th>
                                        </thead>
                                        <tbody>
                                            @foreach($grade['courses'] as $courses)
                                                <tr>
                                                    <td>{{$courses['code']}}</td>
                                                    <td>{{$courses['name']}}</td>
                                                    <td class="center">{{$courses['units']}}</td>
                                                    @if($lab_total>0)
                                                    <td class="center">{{$courses['lab']}}</td>
                                                    @endif
                                                    <td>{{$courses['pre_name']}}</td>
                                                    <td>{!!$courses['status']!!}</td>
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