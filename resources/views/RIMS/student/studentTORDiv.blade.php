<table style="border:1px solid; width: 100%;border-collapse: collapse;">
    <tbody>
        <tr>
            <td class="center" style="width: 15%;border:1px solid"><label>COURSE CODE</label></td>
            <td class="center" style="width: 40%;border:1px solid"><label>COURSE TITLE</label></td>
            <td class="center" style="width: 15%;border:1px solid"><label>GRADE</label></td>
            <td class="center" style="width: 15%;border:1px solid"><label>FINAL GRADE</label></td>
            <td class="center" style="width: 15%;border:1px solid"><label>CREDITS</label></td>
        </tr>
        @foreach($query as $row)
            <tr>
                <td style="border-right:1px solid;border-left:1px solid"></td>
                <td class="center" style="border-right:1px solid;border-left:1px solid">
                    <label><u>{{$row['from_school']}}</u></label>
                </td>
                <td style="border-right:1px solid;border-left:1px solid"></td>
                <td style="border-right:1px solid;border-left:1px solid"></td>
                <td style="border-right:1px solid;border-left:1px solid"></td>
            </tr>
            @foreach($row['year_period'] as $period)
            <tr>
                <td style="border-right:1px solid;border-left:1px solid"></td>
                <td class="center" style="border-right:1px solid;border-left:1px solid">
                    <label><u>{{$period['grade_period']}} {{$period['period']}}</u></label>
                </td>
                <td style="border-right:1px solid;border-left:1px solid"></td>
                <td style="border-right:1px solid;border-left:1px solid"></td>
                <td style="border-right:1px solid;border-left:1px solid"></td>
            </tr>
                @foreach($period['courses'] as $courses)
                <tr>
                    <td class="center" style="border-right:1px solid;border-left:1px solid">
                        {{$courses->course_code}}
                    </td>
                    <td style="border-right:1px solid;border-left:1px solid">
                        {{$courses->course_desc}}
                    </td>
                    <td class="center" style="border-right:1px solid;border-left:1px solid">
                        {{$courses->grade}}
                    </td>
                    <td class="center" style="border-right:1px solid;border-left:1px solid">
                        {{$courses->final_grade}}
                    </td>
                    <td class="center" style="border-right:1px solid;border-left:1px solid">
                        @if($courses->grade<='3' && ($courses->grade!=NULL || $courses->grade!=''))
                            {{$courses->course_units}}
                        @else
                            0
                        @endif
                    </td>
                </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>