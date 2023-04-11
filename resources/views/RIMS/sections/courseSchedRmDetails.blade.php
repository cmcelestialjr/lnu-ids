<table class="table">
    <tr>
        <td>Units:</td>
        <td><label>{{$query->course->units}}</label></td>
        @if($query->course->lab>0)
            <td>Lab:</td>
            <td><label>{{$query->course->lab}}</label></td>
        @endif
        <td>Schedule:</td>
        <td><label>{!!$schedule!!}</label></td>
        <td>Room:</td>
        <td><label>{!!$room!!}</label></td>
        <td>Instructor:</td>
        <td><label>{!!$instructor!!}</label></td>
        <td>No. of Student:</td>
        <td><label>{{$no_students}}</label></td>
    </tr>
</table>