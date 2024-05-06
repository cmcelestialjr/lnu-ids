@foreach($fam_bg as $row)
    <div class="card card-info card-outline">
        <div class="card-body">
            {{$row->fam_relation->name}}: {{$row->firstname}} {{$row->middlename}} {{$row->lastname}} {{$row->extname}}<br>
            Birthdate: {{date('F d, Y',strtotime($row->dob))}}<br>
            Contact: {{$row->contact_no}}
            @if($row->email)
            <br>Email: {{$row->email}}
            @endif
            @if($row->occupation)
            <br>Occupation: {{$row->occupation}}
            @endif
            @if($row->employer)
            <br>Employer: {{$row->employer}}
            @endif
            @if($row->employer_address)
            <br>Employer Address: {{$row->employer_address}}
            @endif
        </div>
    </div>
@endforeach