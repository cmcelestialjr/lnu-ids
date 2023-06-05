<br><label>Days</label>
@php
$checked[1] = '';
$checked[2] = '';
$checked[3] = '';
$checked[4] = '';
$checked[5] = '';
$checked[6] = '';
$checked[7] = '';
$style[1] = '';
$style[2] = '';
$style[3] = '';
$style[4] = '';
$style[5] = '';
$style[6] = '';
$style[7] = '';
foreach($query->days as $day){
    $checked[$day->day] = 'checked';
}
foreach($time_other as $other){
    foreach($other->days as $day){
        if(($other->time_from>=$time_from &&
            $other->time_to<=$time_from) ||
            ($other->time_from<=$time_from &&
            $other->time_to>$time_from) ||
            ($other->time_from<$time_to &&
            $other->time_to>=$time_to) ||
            ($other->time_from>=$time_from &&
            $other->time_to<=$time_to))
        {
            $checked[$day->day] = 'disabled';
            $style[$day->day] = '';                
        }
    }
}
@endphp
<table class="table table-bordered" style="font-size: 14px">
    <td class="center" style="width: 14.25%;">
        <label><input type="checkbox" class="form-control" name="days[]" value="7" {{$checked[7]}} style="{{$style[7]}}">(SU)Sun</label></td>
    <td class="center" style="width: 14.25%;">
        <label><input type="checkbox" class="form-control" name="days[]" value="1" {{$checked[1]}} style="{{$style[1]}}">(M)Mon</label></td>
    <td class="center" style="width: 14.25%;">
        <label><input type="checkbox" class="form-control" name="days[]" value="2" {{$checked[2]}} style="{{$style[2]}}">(T)Tue</label></td>
    <td class="center" style="width: 14.25%;">
        <label><input type="checkbox" class="form-control" name="days[]" value="3" {{$checked[3]}} style="{{$style[3]}}">(W)Wed</label></td>
    <td class="center" style="width: 14.25%; ">
        <label><input type="checkbox" class="form-control" name="days[]" value="4" {{$checked[4]}} style="{{$style[4]}}">(TH)Thu</label></td>
    <td class="center" style="width: 14.25%;">
        <label><input type="checkbox" class="form-control" name="days[]" value="5" {{$checked[5]}} style="{{$style[5]}}">(F)Fri</label></td>
    <td class="center" style="width: 14.25%;">
        <label><input type="checkbox" class="form-control" name="days[]" value="6" {{$checked[6]}} style="{{$style[6]}}">(S)Sat</label></td>
</table>