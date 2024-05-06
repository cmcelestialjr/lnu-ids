<table class="table">
    <tr>
        <td style="width: 30%">Student No:</td>
        <td style="width: 70%"><label>{{$info->stud_id}}</label></td>
    </tr>
    <tr>
        <td>Name:</td>
        <td><label>{{$info->firstname}} {{$info->middlename}} {{$info->lastname}} {{$info->extname}}</label></td>
    </tr>
    <tr>
        <td>Contact:</td>
        <td><label>{{$info->personal_info->contact_no}}</label></td>
    </tr>
    <tr>
        <td>Email:</td>
        <td><label>{{$info->personal_info->email}}</label></td>
    </tr>
    <tr>
        <td>Sex:</td>
        <td><label>{{$info->personal_info->sexs->name}}</label></td>
    </tr>
    <tr>
        <td>Civil Status:</td>
        <td><label>
            @if($info->personal_info->civil_status_id)
                {{$info->personal_info->civil_statuses->name}}
            @endif
            </label></td>
    </tr>
    <tr>
        <td>Birthdate:</td>
        <td><label>
            @if($info->personal_info->dob)
                {{date('F d, Y', strtotime($info->personal_info->dob))}}
            @endif
            </label></td>
    </tr>
    <tr>
        <td>Birthplace:</td>
        <td><label>{{$info->personal_info->place_birth}}</label></td>
    </tr>
    <tr>
        <td>Religion:</td>
        <td><label>
            @if($info->personal_info->religion_id)
                {{$info->personal_info->religion->name}}
            @endif
            </label></td>
    </tr>
    <tr>
        <td>Address:</td>
        <td><label></label></td>
    </tr>
</table>