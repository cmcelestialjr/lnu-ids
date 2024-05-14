
<div class="modal-content" id="forwardModal">
    <div class="modal-header">
        <h4><span class="fa fa-info"></span> {{$doc->dts_id}}</h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>FOWARD TO:</label><br>
                        <label for="office">Office</label>
                        <div id="office_select">
                            <select class="form-control select2-default" name="office" id="office">
                                @foreach($offices as $row)
                                    <option value="{{$row->id}}">{{$row->shorten}} - {{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label id="remarks">Remarks</label>
                        <textarea name="remarks" class="form-control"></textarea>
                    </div>
                    <div class="col-md-12"><br>
                        <div class="form-group clearfix">
                            <label>Return?</label>
                            <div class="icheck-danger d-inline">
                              <input type="radio" name="is_return" value="Y" id="is_return_yes">
                              <label for="is_return_yes">
                                Yes
                              </label>
                            </div>
                            <div class="icheck-success d-inline">
                              <input type="radio" name="is_return" value="N" id="is_return_no" checked>
                              <label for="is_return_no">
                                No
                              </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button type="button" class="btn btn-success btn-success-scan"
            name="submit"
            data-id="{{$doc->id}}"
            data-n="{{$id_name}}">
            <span class="fa fa-check"></span> Submit</button>
    </div>
</div>

<!-- /.modal-content -->
