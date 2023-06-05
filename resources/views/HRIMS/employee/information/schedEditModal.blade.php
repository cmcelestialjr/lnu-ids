
<div class="modal-content" id="schedEditModal">
    <div class="modal-header">

    </div>
    <div class="modal-body">
        <input type="hidden" name="time_id" value="{{$query->id}}">
        <div class="row">
            <div class="col-lg-6">
                <label>Time From</label>
                <input type="time" class="form-control time_input" name="time_from" value="{{$query->time_from}}">
            </div>
            <div class="col-lg-6">
                <label>Time To</label>
                <input type="time" class="form-control time_input" name="time_to" value="{{$query->time_to}}">
            </div>
            <div class="col-lg-12 table-responsive center" id="daysList"><br>
                
            </div>
            <div class="col-lg-12 center"><br>
                <label>Remarks</label>
                <textarea name="remarks" style="width: 100%">{{$query->remarks}}</textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-save"></span> Save</button>
    </div>
</div>
<!-- /.modal-content -->
