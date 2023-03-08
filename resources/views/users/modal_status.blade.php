<div class="modal-content" id="statusDiv">
    <div class="modal-header">
        <h4 class="modal-title">Status</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-md-12">
                <label>Status</label>
                <select class="form-control select2-default" name="status">
                    @foreach($statuses as $row)
                        @if($user->status_id==$row->id)
                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                        @else
                            <option value="{{$row->id}}">{{$row->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">

            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->