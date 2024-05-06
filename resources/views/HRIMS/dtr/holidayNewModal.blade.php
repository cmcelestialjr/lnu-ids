
<div class="modal-content" id="holidayNewModal">
    <div class="modal-header">
        <h4 class="modal-title">New Holiday</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <label>Name</label>
                <input type="text" class="form-control" name="name" placeholder="Name of Holiday">
                <label>Date</label>
                <input type="text" class="form-control datePicker" name="date">
                <label>Type</label>
                <select class="form-control select2-default" name="type">
                    <option value="Special">Special</option>
                    <option value="Suspension">Suspension</option>
                    <option value="Regular">Regular</option>
                </select>
                <label>Repeat Yearly?</label>
                <select class="form-control select2-default" name="option">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>   
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
