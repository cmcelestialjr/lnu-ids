
<div class="modal-content" id="discountNewModal">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> New Discount/Scholarship</h4>
    </div>
    <div class="modal-body">
        <span class="text-require">*</span> Required fields
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">  
                        <label>Name<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="col-lg-6">
                        <label>Percentage % <span class="text-require">*</span></label>
                        <input type="number" class="form-control" name="percent">
                    </div>
                    <div class="col-lg-6"> 
                        <label>Fees to Discount</label>
                        <select class="form-control select2-default" name="fees_type[]" multiple>
                            @foreach($fees_type as $row)
                                <option value="{{$row->id}}" selected>{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6"> 
                        <label>Option</label>
                        <select class="form-control select2-default" name="option">
                            @foreach($option as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-12" id="discountOptionDiv">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-plus"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
