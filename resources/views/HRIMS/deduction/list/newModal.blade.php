
<div class="modal-content" id="listNewModal">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> New Deduction</h4>
    </div>
    <div class="modal-body table-responsive">
        <span class="text-require">*</span> Required field
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Name<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="name">
                        <label>Group</label>
                        <select class="form-control select2-default" name="group">
                            <option value="None">None</option>
                            @foreach($group as $row)
                                <option value="{{$row->id}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                        {{-- <label>Computation</label>
                        <select class="form-control select2-default" name="computation">
                            <option value="None">None</option>
                            <option value="Percent">Percent</option>
                            <option value="Add">Add</option>
                            <option value="Subtract">Subtract</option>
                            <option value="Multiply">Multiply</option>
                            <option value="Divide">Divide</option>
                        </select> --}}
                    </div>
                    {{-- <div class="col-lg-12 hide" id="Percent">
                        <label>Percent</label>
                        <input type="number" class="form-control" name="percent">
                        <label>Percent of</label>
                        <select class="form-control select2-default" name="percent_of">
                            <option value="Salary">Salary</option>
                            <option value="Amount">Amount</option>
                            <option value="Result">Result</option>
                        </select>
                        <div class="hide" id="Percent_Amount_div">
                            <label>Amount</label>
                            <input type="number" class="form-control" name="percent_amount">
                        </div>
                        <label>Ceiling?</label>
                        <input type="number" class="form-control" name="percent_ceiling_amount">
                    </div>
                    <div class="col-lg-12 hide" id="Divide">
                        <label>Divide to</label>
                        <input type="number" class="form-control" name="divide">
                        <label>Divide of</label>
                        <select class="form-control select2-default" name="divide_of">
                            <option value="Salary">Salary</option>
                            <option value="Amount">Amount</option>
                            <option value="Result">Result</option>
                        </select>
                        <div class="hide" id="Divide_Amount_div">
                            <label>Amount</label>
                            <input type="number" class="form-control" name="divide_amount">
                        </div>
                    </div>
                    <div class="col-lg-12 hide" id="Multiply">
                        <label>Multiply to</label>
                        <input type="number" class="form-control" name="multiply">
                        <label>Multiply of</label>
                        <select class="form-control select2-default" name="multiply_of">
                            <option value="Salary">Salary</option>
                            <option value="Amount">Amount</option>
                            <option value="Result">Result</option>
                        </select>
                        <div class="hide" id="Multiply_Amount_div">
                            <label>Amount</label>
                            <input type="number" class="form-control" name="multiply_amount">
                        </div>
                    </div>
                    <div class="col-lg-12 hide" id="Add">
                        <label>Add of</label>
                        <select class="form-control select2-default" name="add_of">
                            <option value="Salary">Salary</option>
                            <option value="Amount">Amount</option>
                            <option value="Result">Result</option>
                        </select>
                        <div class="hide" id="Add_Amount_div">
                            <label>Amount</label>
                                <input type="number" class="form-control" name="add_amount">
                        </div>
                    </div>
                    <div class="col-lg-12 hide" id="Subtract">
                        <label>Subtract of</label>
                        <select class="form-control select2-default" name="subtract_of">
                            <option value="Salary">Salary</option>
                            <option value="Amount">Amount</option>
                            <option value="Result">Result</option>
                        </select>
                        <div class="hide" id="Subtract_Amount_div">
                            <label>Amount</label>
                            <input type="number" class="form-control" name="subtract_amount">
                        </div>
                    </div>
                    <div class="col-lg-12"><br>
                        <button class="btn btn-success btn-success-scan hide" name="add_computation" style="width: 100%">
                            <span class="fa fa-check"></span> Add Computation
                        </button>
                    </div>
                    <div class="col-lg-12">
                        <table class="table">
                            <tbody id="computation_list">

                            </tbody>
                        </table>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success btn-success-scan" name="submit"><span class="fa fa-plus"></span> Submit</button>
    </div>
</div>
<!-- /.modal-content -->
