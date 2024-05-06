<div class="modal-content" id="minMaxModal">
    <input type="hidden" name="id" value="{{$id}}">
    <div class="modal-header">
        <h4 class="modal-title">
           {{$query->code}} - {{$query->course->name}}
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-info card-outline">
                    <div class="card-body">
                        <label>Student</label><br>
                        <label>Minimum</label>
                        <input type="number" class="form-control" name="min_student" value="{{$query->min_student}}"><br>
                        <label>Maximum</label>
                        <input type="number" class="form-control" name="max_student" value="{{$query->max_student}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-check"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
