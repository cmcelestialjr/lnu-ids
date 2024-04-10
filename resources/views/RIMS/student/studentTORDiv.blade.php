<table style="border:1px solid; width: 100%;border-collapse: collapse;">
    <tr>
        <td class="center" style="width: 25%;border:1px solid"><label>School Term & <br>Course No.</label></td>
        <td class="center" style="width: 50%;border:1px solid"><label>Descriptive Title</label></td>
        <td class="center" style="width: 12%;border:1px solid"><label>Final Rating</label></td>
        <td class="center" style="width: 13%;border:1px solid"><label>Re-Exam Units</label></td>
    </tr>
</table>
<div class="table-responsive" style="border:1px solid; height: 600px;">
    <table style="width: 100%;border-collapse: collapse;">
        <tbody>
            <tr>
                <td style="width: 25%;"></td>
                <td style="width: 50%;"></td>
                <td style="width: 12%;"></td>
                <td style="width: 13%;"></td>
            </tr>
            @php
                $school_name_old = '';
                $program_shorten_old = '';
                $grade_period_old = '';
                $total_units = 0;
                $total_grade = 0;
            @endphp
                @foreach($query as $row)
                    @php
                    $sem_units = 0;
                    $sem_grade = 0;                
                    @endphp
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
                        $final_grade = 0;
                        if (is_numeric($courses->final_grade)) {
                            $final_grade = $courses->final_grade;
                        }
                        $course_units = 0;
                        if($final_grade>0){
                            $course_units = $courses->course_units;
                        }
                        if (strpos($courses->course_code, 'NSTP') === false) {
                            $sem_units += $course_units;
                            $sem_grade += $course_units*$final_grade;
                            $total_units += $course_units;
                            $total_grade += $course_units*$final_grade;
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
                            {{$course_units}}
                        </td>
                    </tr>
                    @php
                        $school_name_old = $row['school_name'].', ';
                        $program_shorten_old = $row['program_shorten'];
                        $grade_period_old = $grade_period;
                    @endphp
                    @endforeach
                    <tr>
                        <td class="center" colspan="2">Sem Ave:</td>
                        <td class="center">{{$sem_grade}}</td>
                        <td class="center">{{$sem_units}}</td>
                    </tr>
                @endforeach
        </tbody>
    </table>
</div>
<table style="border:1px solid; width: 100%;border-collapse: collapse;">
    <tr>
        <td class="center" style="width: 75%;border:1px solid" colspan="2">TOTAL UNITS:</td>
        <td class="center" style="width: 12%;border:1px solid">{{$total_grade}}</td>
        <td class="center" style="width: 13%;border:1px solid">{{$total_units}}</td>
    </tr>
    <tr>
        <td class="center" colspan="2">GWA:</td>
        <td></td>
        <td>
            @if($total_units>0)
            {{round($total_grade/$total_units,2)}}
            @endif
        </td>
    </tr>
</table>