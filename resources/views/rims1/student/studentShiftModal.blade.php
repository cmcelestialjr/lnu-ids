
<div class="modal-content" id="studentShiftModal">
    <div class="modal-header">
        <h4 class="modal-title"><span class="fa fa-rotate-right"></span> Shift Program</h4>
        <span class="fa fa-times btn-no-design" data-dismiss="modal">x</span>
    </div>
    <div class="modal-body">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 alert alert-primary">
                        <label>Current Program: {{$query->program->name}} ({{$query->program->shorten}})</label> 
                    </div>
                    <div class="col-lg-12 alert alert-info">
                        <label>Shift Program To:</label>
                        <select class="form-control select2-info" name="shift_to" style="width: 100%">
                            <option value="">Please Select Program</option>
                            @foreach($programs as $row)
                                <option value="{{$row->id}}">{{$row->name}} ({{$row->shorten}})</option>
                            @endforeach
                        </select>
                        @if($query->program_level_id==6)
                            <label>Branch</label>
                            <select class="form-control select2-info" name="branch" style="width: 100%">                                
                                @foreach($branch as $row)
                                    <option value="{{$row->id}}">{{$row->name}} {{$row->code}}</option>
                                @endforeach
                            </select>
                        @endif
                        <label>Curriculum</label>
                        <div id="shiftCurriculumDiv">
                            <select class="form-control select2-info" name="curriculum" style="width: 100%">

                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-primary-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
