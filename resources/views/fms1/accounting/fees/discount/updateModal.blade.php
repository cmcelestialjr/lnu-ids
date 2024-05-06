
<div class="modal-content" id="discountUpdateModal">
    <div class="modal-header">
        <h4><span class="fa fa-plus"></span> Update Discount/Scholarship</h4>
    </div>
    <div class="modal-body">
        <span class="text-require">*</span> Required fields
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">  
                        <label>Name<span class="text-require">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{$query->name}}">
                        <input type="hidden" name="id" value="{{$query->id}}">
                    </div>
                    <div class="col-lg-6">
                        <label>Percentage % <span class="text-require">*</span></label>
                        <input type="number" class="form-control" name="percent" value="{{$query->percent}}">
                    </div>
                    <div class="col-lg-6"> 
                        <label>Fees to Discount</label>
                        <select class="form-control select2-default" name="fees_type[]" multiple>
                            @foreach($fees_type as $row)
                                @if(in_array($row->id, $fees_type_get))
                                    <option value="{{$row->id}}" selected>{{$row->name}}</option>
                                @else
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6"> 
                        <label>Option</label>
                        <input type="text" class="form-control" name="option_name" value="{{$query->option->name}}" readonly>
                        <input type="hidden" name="option" value="{{$query->option_id}}">
                    </div>
                    <div class="col-lg-12">
                        @if($query->option_id==1)
                            <label>Level</label>
                            <input type="text" class="form-control" name="discountLevelSelect" value="{{$program_level_name}}" readonly>
                            <br>
                            <div class="table-responsive" style="height:400px;" id="discountProgramList">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Program</th>
                                        <th>Shorten</th>
                                        <th><input type="checkbox" class="form-control" name="all"></th>
                                    </thead>
                                    <tbody>
                                        @foreach($programs as $row)
                                            <tr>
                                                <td>{{$row->name}}</td>
                                                <td>{{$row->shorten}}</td>
                                                <td>
                                                    @if(in_array($row->id, $programs_discount))
                                                        <input type="checkbox" class="form-control programs" value="{{$row->id}}" checked>
                                                    @else
                                                        <input type="checkbox" class="form-control programs" value="{{$row->id}}">
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif($query->option_id==2)
                            <div id="studentSearch">
                                <label>Student</label>
                                <select class="form-control select2-div studentSearch" id="discountStudentSelected" name="student">
                                </select>
                                <button class="btn btn-success btn-success-scan" id="discountAddStudent" name="add" style="width: 100%">
                                    <span class="fa fa-check"></span> Add Student
                                </button>
                            </div><br>
                            <table class="table table-bordered">
                                <thead>
                                    <th>ID No.</th>
                                    <th>Name</th>
                                    <th>Program</th>
                                    <th>Option</th>
                                </thead>
                                <tbody id="studentList">
                                    @foreach($query->list as $row)
                                        <tr>
                                            <td class="center">{{$row->student->student_info->id_no}}</td>
                                            <td>{{$row->student->lastname}}, {{$row->student->firstname}} {{$row->student->extname}} {{substr($row->student->middlename,0,1)}}</td>
                                            <td class="center">{{$row->student->student_info->program->shorten}}</td>
                                            <td class="center"><button class="btn btn-danger btn-danger-scan btn-xs discountStudentsList" data-id="{{$row->user_id}}"><span class="fa fa-minus"></span></button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <script src="{{ asset('assets/js/search/student.js') }}"></script>
                        @endif
                    </div>
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
