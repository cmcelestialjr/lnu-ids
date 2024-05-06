<div class="card card-primary card-tabs">
    <div class="card-header p-0 pt-1">
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item informationEditDiv" data-id="personalInfoEdit">
          <a class="nav-link" data-toggle="pill" href="#personalInfo" role="tab" aria-selected="true">Personal Info</a>
        </li>
        <li class="nav-item informationEditDiv" data-id="educationalBgEdit">
          <a class="nav-link" data-toggle="pill" href="#educBg" role="tab" aria-selected="false">Educational Background</a>
        </li>
        <li class="nav-item informationEditDiv" data-id="familyBgEdit">
            <a class="nav-link active" data-toggle="pill" href="#famBg" role="tab" aria-selected="false">Family Background</a>
          </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content">
        <div class="tab-pane fade show active" id="famBg" role="tabpanel">
          <div class="row">
            <div class="col-lg-12">
              <button class="btn btn-primary btn-primary-scan" id="newFam" style="float:right"><span class="fa fa-plus"></span> New Family</button><br><br>
            </div>
            <div class="col-lg-12">
              @foreach($family_bg as $row)
                <div class="card card-info card-outline">
                  <div class="card-body">                    
                    <button class="btn btn-danger btn-danger-scan deleteFam" data-id="{{$row->id}}" style="float:right"><span class="fa fa-trash"></span> Delete</button>
                    <button class="btn btn-info btn-info-scan editFam" data-id="{{$row->id}}" style="float:right"><span class="fa fa-edit"></span> Edit</button>
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
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<script src="{{ asset('assets/js/sims/information/fam_bg.js') }}"></script>