<div class="modal-content" id="other-new-modal">
    <div class="modal-header">
        <span class="fa fa-plus-square"> New</span>
    </div>
    <div class="modal-body card card-info card-outline">
        <div class="row">
            <div class="col-lg-12">
                <label for="option">Option:</label>
                <select class="form-control select2-info" name="option" id="option">
                    @foreach ($options as $row)
                        <option value="{{$row}}">{{ucwords($row)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-12">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" id="name">
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" id="submit"><span class="fa fa-save"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
