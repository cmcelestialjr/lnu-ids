<table style="border:1px solid; width: 100%;border-collapse: collapse;">
    <tbody>
        <tr>
            <td class="center" style="width: 25%;border:1px solid"><label>School Term & <br>Course No.</label></td>
            <td class="center" style="width: 50%;border:1px solid"><label>Descriptive Title</label></td>
            <td class="center" style="width: 12%;border:1px solid"><label>Final Rating</label></td>
            <td class="center" style="width: 13%;border:1px solid"><label>Re-Exam Units</label></td>
        </tr>
        @php
            $school_name_old = '';
            $program_shorten_old = '';
            $grade_period_old = '';
        @endphp         
            @foreach($query as $row)
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
                    <td colspan="4">
                        <label><u>{{$grade_period}} S.Y. {{$row['period']}} {{$school_name}}{{$program_shorten}}</u></label>
                    </td>
                </tr>
                @endif
                <tr>
                    <td class="center">
                        {{$courses->course_code}}
                    </td>
                    <td>
                        {{$courses->course_desc}}
                    </td>
                    <td class="center">
                        {{$courses->final_grade}}
                    </td>
                    <td class="center">
                        @if($courses->grade<='3' && ($courses->grade!=NULL || $courses->grade!=''))
                            {{$courses->course_units}}
                        @else
                            0
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