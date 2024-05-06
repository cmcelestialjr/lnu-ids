
<div class="modal-content" id="studentPrintModal">
    <div class="modal-header">
        <h4 class="modal-title"> Print Transcript of Record (TOR)</h4>
        <span class="fa fa-times btn-no-design" data-dismiss="modal">x</span>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <label>{{$student->lastname}}, {{$student->firstname}} {{$student->extname}} {{$student->middlename}}</label>
            </div>
            <div class="col-lg-12">
                <select class="form-control select2-info" name="level">
                      <option value="{{$query->id}}">{{$query->name}}</option>
                </select>
            </div>
            <div class="col-lg-12">
                <label>Purpose</label>
                <select class="form-control select2-info" name="purpose">
                    @foreach($purpose as $row)
                      <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-12">
                <label>Remarks</label><br>
                <textarea name="remarks" style="width: 100%"></textarea><br>
                <button type="button" class="btn btn-success btn-success-scan" name="submit" style="width:100%">Submit</button>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<!-- /.modal-content -->
