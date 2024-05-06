
<div class="modal-content" id="selectProgramModal">
    <div class="modal-header">
        <h4 class="modal-title">Select Program</h4>
        <span class="fa fa-times" data-dismiss="modal"></span>
    </div>
    <div class="modal-body">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Branch:</label>
                        <div id="branch">
                            <select class="form-control select2-info" name="branch" style="width: 100%">
                                @foreach($branch as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <label>Level:</label>
                        <div id="program_level">
                            <select class="form-control select2-info" name="program_level" style="width: 100%">
                                <option value="">Please Select Level</option>
                                @foreach($program_level as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <label>Program:</label>
                        <div id="program">
                            <select class="form-control select2-info" name="program" style="width: 100%">
                                <option value="">Please Select Program</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <label>Curriculum:</label>
                        <div id="curriculum">
                            <select class="form-control select2-info" name="curriculum" style="width: 100%">
                                <option value="">Please Select Curriculum</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <label>Start Year for this Program</label>
                        <input type="text" class="form-control yearpicker" name="year_from" id="year_from">
                    </div>
                    <div class="col-lg-12">
                        <label>Student Status:</label>
                        <div id="student_status">
                            <select class="form-control select2-info" name="student_status" style="width: 100%">
                                @foreach($student_status as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12"><br/>
                        <label>
                            Check to adopt Student Courses with the same Course Code in the selected Curriculum?
                            <input type="checkbox" name="adopt_same_course"/><br/>
                        </label>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="selectProgramSubmit" data-id="{{$student->id}}"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
