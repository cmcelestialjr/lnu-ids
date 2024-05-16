
<div class="modal-content" id="statusModal">
    <div class="modal-header">
        <h4><span class="fa fa-info"></span> {{$doc->dts_id}}</h4>
    </div>
    <div class="modal-body table-responsive">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <label for="status">Status:</label>
                        <div id="status_select">
                            <select class="form-control select2-default" name="status" id="status">
                                @foreach($statuses as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
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
            data-id="{{$doc->dts_id}}">
            <span class="fa fa-check"></span> Submit</button>
    </div>
</div>

<!-- /.modal-content -->
