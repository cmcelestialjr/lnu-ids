<div id="table-responsive"><br>
    <h5>{{$program->program_info->shorten}} - {{$program->program_info->name}} ({{$program->program_level->name}})</h5>
    <label>
        Section: 
        @php
        foreach($program->courses as $row){
            $section_code = $row->course->section_code;
        }
        @endphp
        {{$section_code}}
    </label><br>
    @if(count($coursesWoSched)>0)
        <label>Courses without Schedule</label>
        <table class="table table-bordered center">
            <thead>
                <th>Course Code</th>
                <th>Room</th>
                <th>Instructor</th>
                <th>Level</th>
            </thead>
            <tbody>
            @foreach($coursesWoSched as $row)
                <tr>
                    <td>{{$row['course_code']}}</td>
                    <td>{!!$row['room']!!}</td>
                    <td>{{$row['instructor']}}</td>
                    <td>{{$row['grade_level']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    <table class="table table-bordered center">
        <thead>
            <th style="width: 12.5%">Start Time</th>
            <th style="width: 12.5%">(SU)Sunday</th>
            <th style="width: 12.5%">(M)Monday</th>
            <th style="width: 12.5%">(T)Tuesday</th>
            <th style="width: 12.5%">(W)Wednesday</th>
            <th style="width: 12.5%">(TH)Thursday</th>
            <th style="width: 12.5%">(F)Friday</th>
            <th style="width: 12.5%">(S)Saturday</th>
        </thead>
        <tbody>
            @foreach($schedules as $row)
            <tr>
                <td>{{$row['time']}}</td>
                <td>{!!$row['0']!!}</td>
                <td>{!!$row['1']!!}</td>
                <td>{!!$row['2']!!}</td>
                <td>{!!$row['3']!!}</td>
                <td>{!!$row['4']!!}</td>
                <td>{!!$row['5']!!}</td>
                <td>{!!$row['6']!!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>