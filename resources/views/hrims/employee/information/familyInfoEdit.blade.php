
<div class="modal-content" id="fam-edit-modal">
    <div class="modal-header">
        <span class="fa fa-edit"> Edit</span>
    </div>
    <div class="modal-body card card-info card-outline">
        <div class="row">
            <div class="col-lg-4">
                <label for="relation">Relationship:</label>
                <select class="form-control select2-info" id="relation">
                    @foreach($relations as $row)
                        @if($query->relation_id==$row->id)
                            <option value="{{$row->id}}" selected>{{$row->name}}</option>
                        @else
                            <option value="{{$row->id}}">{{$row->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <label for="lastname">Lastname:</label>
                <input type="text" class="form-control" name="lastname" id="lastname" value="{{$query->lastname}}">
            </div>
            <div class="col-lg-4">
                <label for="firstname">Firstname:</label>
                <input type="text" class="form-control" name="firstname" id="firstname" value="{{$query->firstname}}">
            </div>
            <div class="col-lg-4">
                <label for="middlename">Middlename:</label>
                <input type="text" class="form-control" name="middlename" id="middlename" value="{{$query->middlename}}">
            </div>
            <div class="col-lg-4">
                <label for="extname">Extname:</label>
                <input type="text" class="form-control" name="extname" id="extname" value="{{$query->extname}}">
            </div>
            <div class="col-lg-4">
                <label for="dob">Birthdate:</label>
                <input type="text" class="form-control datepicker" name="dob" id="dob" value="{{$query->dob}}">
            </div>
            <div class="col-lg-4">
                <label for="contact_no">Contact No.:</label>
                <div class="input-group">
                    <div class="input-group-append">
                        <button type="submit" name="submit" class="btn btn-default">
                            <label>+63</label>
                        </button>
                    </div>
                    <input type="text" class="form-control contact" name="contact_no" id="contact_no" value="{{$query->contact_no}}">
                </div>
            </div>
            <div class="col-lg-4">
                <label for="email">Email:</label>
                <div class="input-group">
                    <div class="input-group-append">
                        <button type="submit" name="submit" class="btn btn-default">
                            <label>@</label>
                        </button>
                    </div>
                    <input type="email" class="form-control" name="email" id="email" value="{{$query->email}}">
                </div>
            </div>
            <div class="col-lg-4">
                <label for="occupation">Occupation:</label>
                <input type="text" class="form-control datepicker" name="occupation" id="occupation" value="{{$query->occupation}}">
            </div>
            <div class="col-lg-12 center">
                <label>Employer Details:</label>
            </div>
            <div class="col-lg-4">
                <label for="employer">Employer:</label>
                <input type="text" class="form-control" name="employer" id="employer" value="{{$query->employer}}">
            </div>
            <div class="col-lg-4">
                <label for="employer_contact">Contact:</label>
                <input type="text" class="form-control" name="employer_contact" id="employer_contact" value="{{$query->employer_contact}}">
            </div>
            <div class="col-lg-4">
                <label for="employer_address">Address:</label>
                <input type="text" class="form-control" name="employer_address" id="employer_address" value="{{$query->employer_address}}">
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" id="submit" data-id="{{$query->id}}"><span class="fa fa-save"></span> Save</button>
    </div>
</div>
<!-- /.modal-content -->
