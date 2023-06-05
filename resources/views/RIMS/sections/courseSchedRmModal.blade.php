<div class="modal-content" id="courseSchedRmModal">
    <div class="modal-header">
        <h4 class="modal-title">
           {{$query->course->name}} ({{$query->course->code}})
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="col-lg-12 table-responsive" id="details">
                
            </div>
            <div class="col-lg-12">
                <div class="card card-info card-outline">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-10" id="schedule">
                                
                            </div>
                            <div class="col-lg-12">
                                <div class="row" id="rm_instructor">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="row" style="font-size:12px;">
                            <div class="col-lg-12"><label>Legend:<br></label></div>
                            <div class="col-lg-1">
                                <div class="input-group">
                                    <div  class="bg-success legend-box">
                                    </div>
                                    <div class="input-group-append">
                                        &nbsp;Itself
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <div  class="legend-box" style="background-color:#3CB371">
                                    </div>
                                    <div class="input-group-append">
                                        &nbsp;Other within course
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <div  class="bg-info legend-box">
                                    </div>
                                    <div class="input-group-append">
                                        &nbsp;Courses within Section
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <div  class="bg-primary legend-box">
                                    </div>
                                    <div class="input-group-append">
                                        &nbsp;Instructor outside Section
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <div  class="bg-warning legend-box">
                                    </div>
                                    <div class="input-group-append">
                                        &nbsp;Room outside Section
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="input-group">
                                    <div  class="bg-danger legend-box">
                                    </div>
                                    <div class="input-group-append">
                                        &nbsp;Conflict
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12"><br></div>
                        </div>
                        <div id="courseSchedRmTable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> Close</button>
    </div>
</div>
<!-- /.modal-content -->
