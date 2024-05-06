
<div class="modal-content" id="studentGradeModal">
    <input type="hidden" name="id" value="{{$id}}">
    <input type="hidden" name="sid" value="{{$sid}}">
    <div class="modal-header">
        <h4 class="modal-title">
            <label>{{$name}}</label>
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>Grade</label>
                                <input type="text" class="form-control" name="grade" value="{{$query->grade}}" placeholder="Input here"><br>
                                <label>Final Grade</label>
                                <input type="text" class="form-control" name="final_grade" value="{{$query->final_grade}}" placeholder="Input here">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit">Submit</button>
    </div>
</div>
<!-- /.modal-content -->
