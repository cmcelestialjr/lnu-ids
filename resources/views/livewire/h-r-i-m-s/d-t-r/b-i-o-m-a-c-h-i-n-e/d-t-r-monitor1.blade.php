<div class="row">
    <div class="col-lg-8">
        <div class="content-wrapper">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12" id="display" style="height: 1000px;">
                            <table class="table center" style="font-size:30px;vertical-align:middle">
                                <tr>
                                    <td colspan="2" style="vertical-align: middle;font-size:50px;">
                                        {{date('F d, Y')}}<br>{{date('h:i:sa')}}
                                    </td>
                                    <td colspan="2">
                                        <br>
                                        <img src="{{ asset('assets/images/icons/png/user.png') }}" class="image" style="height:450px;width:400px;">
                                        <br><br>
                                    </td>
                                    <td colspan="2" style="vertical-align: middle;text-align:left;">
                                        ID NO.: <label>{!!$id_no!!}</label><br><br>
                                        Name: <label>{!!$name!!}</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="border-table">AM</th>
                                    <th colspan="3" class="border-table">PM</th>
                                </tr>
                                <tr>
                                    <th class="border-table" >In</th>
                                    <th colspan="2" class="border-table">Out</th>
                                    <th colspan="2" class="border-table">In</th>
                                    <th class="border-table"4>Out</th>
                                </tr>
                                <tr>
                                    <td class="border-table">{!!$in_am!!}</td>
                                    <td colspan="2" class="border-table">{!!$out_am!!}</td>
                                    <td colspan="2" class="border-table">{!!$in_pm!!}</td>
                                    <td class="border-table">{!!$out_pm!!}</td>
                                </tr>
                                <tr>
                                    <td style="width: 25%;"></td>
                                    <td style="width: 7%;"></td>
                                    <td style="width: 18%;"></td>
                                    <td style="width: 18%;"></td>
                                    <td style="width: 7%;"></td>
                                    <td style="width: 25%;"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="content-wrapper">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 table-responsive" style="height: 1000px;">
                            <table class="table table-bordered" style="font-size:16px;">
                                <thead>
                                    <th>No.</th>
                                    <th>ID No.</th>
                                    <th>Name</th>
                                    <th>Time</th>
                                </thead>
                                <tbody>
                                    @php
                                    $x = 1;
                                    @endphp
                                    @foreach($list as $row)
                                    <tr>
                                        <td>{{$x}}</td>
                                        <td class="center">{{$row->id_no}}</td>
                                        <td>{{$name_services->lastname($row->user->lastname,$row->user->firstname,$row->user->middlename,$row->user->extname)}}</td>
                                        <td class="center">{{date('h:ia',strtotime($row->dateTime))}}</td>
                                    </tr>
                                    @php
                                    $x++;
                                    @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>