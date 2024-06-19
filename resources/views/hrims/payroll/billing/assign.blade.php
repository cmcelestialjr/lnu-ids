
<div class="modal-content">
    <div class="modal-header">
        <h4><span class="fa fa-info"></span> Assign</h4>
    </div>
    <div class="modal-body">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @php
                        $exp = explode('_',$query->name);
                        $name = $query->name;
                        if(isset($exp[1])){
                            $lastname = $exp[1];
                            $firstname = $exp[2];
                            $middlename = $exp[3];
                            $extname = $exp[4];
                            $loan_type = $exp[6];
                            $name = $lastname.', '.$firstname.' '.$extname.' '.$middlename.' ('.$loan_type.')';
                        }
                        @endphp
                        <label>{{$query->staff_no}} <br> {{$name}}</label>

                    </div>
                    <div class="col-lg-12">
                        <label for="employee">Employee</label>
                        <div id="employeeSearch">
                            <select class="form-control employeeSearch" id="employee">

                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" id="assignSubmit" data-id="{{$query->id}}"><span class="fa fa-plus"></span> Submit</button>
    </div>
</div>
<script src="{{ asset('assets/js/search/employee.js') }}"></script>
<!-- /.modal-content -->
