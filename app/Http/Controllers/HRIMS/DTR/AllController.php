<?php

namespace App\Http\Controllers\HRIMS\DTR;

use App\Http\Controllers\Controller;
use App\Models\DTRType;
use App\Models\Users;
use App\Models\UsersDTRTrack;
use App\Models\UsersDTRType;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AllController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = array();
        $name_services = new NameServices;
        $year = $request->year;
        $month = $request->month;
        $range = $request->range;
        $option = $request->option;

        $query = Users::with('employee_default.emp_stat')->where('id','>',0);
        if($option=='Submitted'){
            $query = $query->whereHas('dtr_track', function ($query) use ($year,$month,$range) {
                    $query->whereYear('date',$year);
                    $query->whereMonth('date',$month);
                    $query->where('range',$range);
                });
        }elseif($option=='not'){
            if($range==2){
                $query = $query->whereHas('user_role', function ($query)  {
                        $query->where('emp_stat','>',3);
                    });
            }
            $query = $query->WhereDoesntHave('dtr_track', function ($query) use ($year,$month,$range) {
                    $query->whereYear('date',$year);
                    $query->whereMonth('date',$month);
                    $query->where('range',$range);
                });
            $query = $query->whereHas('employee_default', function ($query)  {
                    $query->where('date_separation',NULL);
                });
        }else{
            $query = $query->whereHas('dtr', function ($query) use ($year,$month) {
                $query->whereYear('date',$year);
                $query->whereMonth('date',$month);
            });
        }
        $query = $query->orderBy('lastname','ASC')
            ->orderBy('firstname','ASC')->get()
            ->map(function($query) use ($name_services,$year,$month,$range) {
                $name = $name_services->lastname($query->lastname,$query->firstname,$query->middlename,$query->extname);
                if(isset($query->employee_default)){
                    $position = $query->employee_default->position_title;
                    $salary = $query->employee_default->salary;
                    $emp_stat = $query->employee_default->emp_stat->name;
                }else{
                    $position = '';
                    $salary = '';
                    $emp_stat = '';
                }
                
                $date_submit = UsersDTRTrack::where('id_no',$query->id_no)
                    ->whereYear('date',$year)
                    ->whereMonth('date',$month)
                    ->where('range',$range)
                    ->first();
                if($date_submit!=NULL){
                    $date_submit = date('m', strtotime($date_submit->date_submitted)).'-'.date('F d, Y h:ia', strtotime($date_submit->date_submitted));
                }else{
                    $date_submit = '';
                }

                $received = UsersDTRType::with('updated_by_info')->where('user_id',$query->id)
                    ->whereYear('date',$year)
                    ->whereMonth('date',$month)
                    ->get();

                return [
                    'id' => $query->id,
                    'name' => $name,
                    'id_no' => $query->id_no,
                    'position' => $position,
                    'salary' => $salary,
                    'emp_stat' => $emp_stat,
                    'date_submit' => $date_submit,
                    'received' => $received
                ];
            })->toArray();
        if(count($query)>0){
            $dtrType = DTRType::get()
                ->map(function($query){
                    return [
                        'id' => $query->id,
                        'name' => $query->name,
                        'day_from' => $query->day_from,
                        'day_to' => $query->day_to
                    ];
                })
                ->toArray();
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['id_no'];
                $data_list['f3'] = $r['name'];
                $data_list['f4'] = $r['position'];
                $data_list['f5'] = $r['salary'];
                $data_list['f6'] = $r['emp_stat'];
                $data_list['f8'] = $r['date_submit'];
                $data_list['f9'] = '<button class="btn btn-primary btn-primary-scan btn-sm employeeView"
                                        data-id="'.$r['id_no'].'"
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                if(count($dtrType)>0){
                    foreach($dtrType as $rowType){
                        $checked = '<input type="checkbox" class="form-control receiveDTR" 
                            data-id="'.$r['id'].'"
                            data-type="'.$rowType['id'].'"> <div></div>';
                        foreach($r['received'] as $rowReceived){
                            $received_date = date('Y-m-d',strtotime($rowReceived->updated_at));
                            $received_date_time = date('M d, Y h:ia',strtotime($rowReceived->updated_at));
                            if($rowType['id']==$rowReceived['dtr_type_id'] && $received_date>date('Y-m-d')){
                                $checked = '<span class="fa fa-check"></span> '.$received_date_time.'<br>'.
                                    $rowReceived->updated_by_info->firstname[0].'. '.$rowReceived->updated_by_info->lastname;
                            }elseif($rowType['id']==$rowReceived['dtr_type_id'] && $received_date==date('Y-m-d')){
                                $checked = '<input type="checkbox" class="form-control receiveDTR" 
                                            data-id="'.$r['id'].'"
                                            data-type="'.$rowType['id'].'" checked> <div>'.$received_date_time.'<br>'.
                                                $rowReceived->updated_by_info->firstname[0].'. '.$rowReceived->updated_by_info->lastname.'</div>';
                            }
                        }
                        $data_list['dtr_'.$rowType['id']] = $checked;
                    }
                }
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $id_no = $request->id_no;
        $year = $request->year;
        $month = $request->month;
        $range = $request->range;
        $name_services = new NameServices;
        $user = Users::where('id_no',$id_no)->first();
        $name = mb_strtoupper($name_services->firstname($user->lastname,$user->firstname,$user->middlename,$user->extname));
        $data = array(
            'id_no' => $id_no,
            'name' => $name,
            'year' => $year,
            'month' => $month,
            'range' => $range
        );
        return view('hrims/dtr/allDtrView',$data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {        
        // Validate the incoming request data
        $validator = $this->updateValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $updated_by = $user->id;

        $result = 'error';
        $div = '';
        $id = $request->id;
        $type = $request->type;
        $year = $request->year;
        $month = $request->month;

        $date = date('Y-m-d',strtotime($year.'-'.$month.'-01'));

        $userCheck = Users::find($id);
        $typeCheck = DTRType::find($type);

        if($userCheck==NULL && $typeCheck==NULL){
            return response()->json(['result' => 'error']);
        }

        $dtrCheck = UsersDTRType::where('user_id',$id)
            ->where('dtr_type_id',$type)
            ->where('date',$date)
            ->first();
        
        if($dtrCheck){
            $delete = UsersDTRType::where('user_id',$id)
                ->where('dtr_type_id',$type)
                ->where('date',$date)->delete();
            $auto_increment = DB::update("ALTER TABLE `users_dtr_type` AUTO_INCREMENT = 0;");
            if($delete){
                $result = 'success';
            }
        }else{            
            $day_to = date('t',strtotime($date));
            if($typeCheck->day_to==15){
                $day_to = 15;
            }
            $insert = new UsersDTRType(); 
            $insert->user_id = $id;
            $insert->dtr_type_id = $type;
            $insert->date = $date;
            $insert->day_from = $typeCheck->day_from;
            $insert->day_to = $day_to;
            $insert->updated_by = $updated_by;
            $insert->save();
            $result = 'success';
            $div = date('M d, Y h:ia').'<br>'.$userCheck->firstname[0].'. '.$userCheck->lastname;
        }

        $data = array('result' => $result,
                     'div' => $div);
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function updateValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'type' => 'required|numeric',
            'year' => 'required|numeric',
            'month' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'Id is required.',
            'id.numeric' => 'Id must be a number.',
            'type.required' => 'Type is required.',
            'type.numeric' => 'Type must be a number.',
            'year.required' => 'Year is required.',
            'year.numeric' => 'Year must be a number.',
            'month.required' => 'Month is required.',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Handle database errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleDatabaseError($e)
    {
        DB::rollback();
        return response()->json(['result' => $e->getMessage()], 400);
    }

    /**
     * Handle other errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleOtherError($e)
    {
        DB::rollback();
        return response()->json(['result' => $e->getMessage()], 500);
    }
}
