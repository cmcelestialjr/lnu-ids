
<div class="modal-content" id="studentTORModal">
    <div class="modal-header">
        <h4 class="modal-title"> Transcript of Record (TOR)</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-lg-4">
                <select class="form-control select2-primary" name="level">
                    @foreach($query as $row)
                      <option value="{{$row->id}}">{{$row->name}}</option>
                    @endforeach
                  </select>
            </div>
            <div class="col-lg-12">
                <br>
                <div class="card card-primary card-outline">
                    <div class="card-body table-responsive" id="studentTORDiv">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
<!-- /.modal-content -->
