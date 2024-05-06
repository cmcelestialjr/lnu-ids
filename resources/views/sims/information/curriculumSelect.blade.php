<div class="row">
  <div class="col-lg-3">
    <label>Program</label>
    <select class="form-control select2-curriculum" name="programs">
        @foreach($programs as $row)
          <option value="{{$row->program_id}}">{{$row->program_info->shorten}}-{{$row->program_info->name}}</option>
        @endforeach
    </select>
  </div>
  <div class="col-lg-3">
    <label>Curriculum</label>
    <select class="form-control select2-curriculum" name="curriculums">
        @foreach($curriculums as $row)
          <option value="{{$row->id}}">{{$row->year_from}}-{{$row->year_to}} ({{$row->code}})</option>
        @endforeach
    </select>
  </div>
  <div class="col-lg-3">
    <label>Grade/Year</label>
    <select class="form-control select2-curriculum" name="year_level[]" multiple>
        @foreach($year_level as $row)
          <option value="{{$row->id}}">{{$row->name}}</option>
        @endforeach
    </select>
  </div>
  <div class="col-lg-12" id="curriculumList">
      
  </div>
</div>