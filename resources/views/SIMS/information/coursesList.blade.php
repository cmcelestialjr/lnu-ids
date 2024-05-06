<br>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <th>Section Code</th>
            <th>Course Code</th>
            <th>Course Description</th>
            <th>Units</th>
            <th>Grade</th>
            <th>Status</th>
        </thead>
        <tbody>
            @foreach($courses as $row)
                <tr>
                    <td class="center">{{$row->course->section_code}}</td>
                    <td class="center">{{$row->course->code}}</td>
                    <td>{{$row->course_info->name}}</td>
                    <td class="center">{{$row->course_info->units}}</td>
                    <td class="center">{{$row->final_grade}}</td>
                    <td class="center">
                        @if($row->student_course_status_id==NULL)
                            NG
                        @else
                            {{$row->status->shorten}}
                        @endif                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>