
<div class="modal-content" id="schedNewModal">
    <div class="modal-header">

    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-6">
                <label>Option</label>
                <select class="form-control select2-info" name="option">
                    @foreach($sched_option as $row)
                        <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-6">
                <label>Duration</label>
                <input type="text" class="form-control dateRange duration" name="duration">
            </div>
            <div class="col-lg-6">
                <label>Time From</label>
                <input type="time" class="form-control time_input" name="time_from">
            </div>
            <div class="col-lg-6">
                <label>Time To</label>
                <input type="time" class="form-control time_input" name="time_to">
            </div>
            <div class="col-lg-12 table-responsive center" id="daysList"><br>
                
            </div>
            <div class="col-lg-12 center"><br>
                <label>Remarks</label>
                <textarea name="remarks" style="width: 100%"></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">        
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-save"></span> Save</button>
    </div>
</div>
<!-- /.modal-content -->
