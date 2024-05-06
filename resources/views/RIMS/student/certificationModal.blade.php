
<div class="modal-content" id="certificationModal">
    <div class="modal-header">
        <h4 class="modal-title"></h4>
        <span class="fa fa-times" data-dismiss="modal"></span>
    </div>
    <div class="modal-body">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Certification</label>
                        <select class="form-control select2-primary" name="certification">
                            <option value="scholasticReport">Scholastic Report</option>
                        </select>
                    </div>
                </div>
                <div class="row" id="scholasticReport">
                    <div class="col-lg-12">
                        <label>Level:</label>
                        <div id="program_level">
                            <select class="form-control select2-primary" name="program_level" style="width: 100%">
                                <option value="">Please select Level</option>
                                @foreach($program_level as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>School Year</label>
                        <div id="school_year">
                            <select class="form-control select2-primary" name="school_year">
                                <option value="">Please select School Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Period</label>
                        <div id="period">
                            <select class="form-control select2-primary" name="period">
                                <option value="">Please select Period</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Program</label>
                        <input type="text" class="form-control" name="program">
                    </div>
                    <div class="col-md-12">
                        <label>Year</label>
                        <input type="text" class="form-control" name="year">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="certificationSubmit" data-id="{{$student->id}}"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>