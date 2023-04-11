<br>
<div class="row">    
    <div class="col-lg-4">
        <label>Program</label>
        <select class="form-control select2-student" name="program">
            @foreach($programs as $row)
                <option value="{{$row->id}}" selected>{{$row->name}} ({{$row->shorten}})</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-8">
        <div class="row">
            <div class="col-lg-4" id="programCodeDiv">
                <label>Code</label>
                <select class="form-control select2-student" name="program_code">
                    @foreach($program_codes as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-5" id="programCurriculumDiv">
                
            </div>
            <div class="col-lg-3" id="programSectionDiv">
                
            </div>
        </div>
    </div>
    <div class="col-lg-12" id="programCoursesDiv">
        
    </div>
</div>