<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title"> <span class="fa fa-edit"></span> Edit</h4>
        <span class="fa fa-times btn-no-design" data-dismiss="modal">x</span>
    </div>
    <div class="modal-body">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <input type="hidden" id="famID" value="{{$id}}">
                        <label>Relation</label>
                        <select class="form-control select2-info" id="famRelation">
                            @foreach($fam_relations as $row)
                                @if($fam_bg->relation_id==$row->id)
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif                                
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label>Lastname</label>
                        <input type="text" class="form-control" id="famLastname" value="{{$fam_bg->lastname}}">
                    </div>
                    <div class="col-lg-6">
                        <label>Firstname</label>
                        <input type="text" class="form-control" id="famFirstname" value="{{$fam_bg->firstname}}">
                    </div>
                    <div class="col-lg-6">
                        <label>Middlename</label>
                        <input type="text" class="form-control" id="famMiddlename" value="{{$fam_bg->middlename}}">
                    </div>
                    <div class="col-lg-6">
                        <label>Extname</label>
                        <input type="text" class="form-control" id="famExtname" value="{{$fam_bg->extname}}">
                    </div>
                    <div class="col-lg-6">
                        <label>Date of Birth</label>
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control datePicker" id="famDOB" value="{{date('m/d/Y',strtotime($fam_bg->dob))}}">
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
                                <input type="text" class="form-control contact" id="famContact" value="{{$fam_bg->contact_no}}">
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
                                <input type="text" class="form-control" id="famEmail" value="{{$fam_bg->email}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label>Occupation</label>
                        <input type="text" class="form-control" id="famOccupation" value="{{$fam_bg->occupation}}">
                    </div>
                    <div class="col-lg-6">
                        <label>Employer</label>
                        <input type="text" class="form-control" id="famEmployer" value="{{$fam_bg->employer}}">
                    </div>
                    <div class="col-lg-6">
                        <label>Employer Address</label>
                        <div class="form-group">
                            <div class="input-group input-group-sm">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-map"></i></span>
                                </div>
                                <input type="text" class="form-control" id="famEmployerAddress" value="{{$fam_bg->employer_address}}">
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
                                <input type="text" class="form-control contact" id="famEmployerContact" value="{{$fam_bg->employer_contact}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">        
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times"></span> No</button>
        <button type="button" class="btn btn-success btn-success-scan" id="famEditSubmit"><span class="fa fa-check"></span> Yes</button>
    </div>
</div>
<!-- /.modal-content -->
