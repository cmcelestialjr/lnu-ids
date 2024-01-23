
<div class="modal-content" id="studentCurriculumModal">
    <div class="modal-header">
        <h4 class="modal-title"> Curriculum</h4>
        <span class="fa fa-times btn-no-design" data-dismiss="modal">x</span>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-lg-12">
                <label>{{$student->lastname}}, {{$student->firstname}} {{$student->extname}} {{$student->middlename}}</label>
            </div>
            <div class="col-lg-3">
                <label>Level</label>
                <select class="form-control select2-primary" name="level">
                    @foreach($query as $row)
                      <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <label>Program</label>
                <select class="form-control select2-primary" name="program" disabled>
                    
                </select>
            </div>
            <div class="col-lg-3">
                <label>Curriculum</label>
                <select class="form-control select2-primary" name="curriculum">
                    
                </select>
            </div>
            <div class="col-lg-3">
                <label>Branch</label>
                <select class="form-control select2-primary" name="branch">
                    
                </select>
            </div>
            <div class="col-lg-12" id="studentCurriculumDiv">
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<!-- /.modal-content -->
<script src="{{ asset('assets/js/rims/student/curriculum.js') }}"></script>
