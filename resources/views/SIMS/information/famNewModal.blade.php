<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title"> <span class="fa fa-plus"></span> New</h4>
        <span class="fa fa-times btn-no-design" data-dismiss="modal">x</span>
    </div>
    <div class="modal-body">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Relation</label>
                        <select class="form-control select2-info" id="famRelation">
                            @foreach($fam_relations as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label>Lastname</label>
                        <input type="text" class="form-control" id="famLastname">
                    </div>
                    <div class="col-lg-6">
                        <label>Firstname</label>
                        <input type="text" class="form-control" id="famFirstname">
                    </div>
                    <div class="col-lg-6">
                        <label>Middlename</label>
                        <input type="text" class="form-control" id="famMiddlename">
                    </div>
                    <div class="col-lg-6">
                        <label>Extname</label>
                        <input type="text" class="form-control" id="famExtname">
                    </div>
                    <div class="col-lg-6">
                        <label>Date of Birth</label>
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control datePicker" id="famDOB">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label>Contact No</label>
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <div class="input-group-append">
                                    <span class="input-group-text">(+63)</span>
                                </div>
                                <input type="text" class="form-control contact" id="famContact">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <label>Email</label>
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                </div>
                                <input type="text" class="form-control" id="famEmail">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label>Occupation</label>
                        <input type="text" class="form-control" id="famOccupation">
                    </div>
                    <div class="col-lg-6">
                        <label>Employer</label>
                        <input type="text" class="form-control" id="famEmployer">
                    </div>
                    <div class="col-lg-6">
                        <label>Employer Address</label>
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-map"></i></span>
                                </div>
                                <input type="text" class="form-control" id="famEmployerAddress">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label>Employer Contact</label>
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <div class="input-group-append">
                                    <span class="input-group-text">(+63)</span>
                                </div>
                                <input type="text" class="form-control contact" id="famEmployerContact">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">        
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> No</button>
        <button type="button" class="btn btn-success btn-success-scan" id="famNewSubmit"><span class="fa fa-check"></span> Yes</button>
    </div>
</div>
<!-- /.modal-content -->
