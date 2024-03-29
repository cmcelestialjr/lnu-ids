<?php

namespace App\Http\Controllers\HRIMS\DTR;
use App\Http\Controllers\Controller;
use App\Models\DTRType;
use App\Models\Users;
use App\Models\UsersDTRTrack;
use App\Models\UsersDTRType;
use App\Services\NameServices;
use Illuminate\Http\Request;

class AllController extends Controller
{   
    public function table(Request $request){
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

                $received = UsersDTRType::where('user_id',$query->id)
                    ->whereYear('date',$year)
                    ->whereMonth('date',$month)
                    ->get()->toArray();

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
                            data-type="'.$rowType['id'].'">';
                        foreach($r['received'] as $rowReceived){
                            if($rowType['id']==$rowReceived['dtr_type_id'] && date('Y-m-d',strtotime($rowReceived['received_at']))>date('Y-m-d')){
                                $checked = '<span class="fa fa-check"></span> '.date('M d, Y h:ia',strtotime($rowReceived['received_at']));
                            }elseif($rowType['id']==$rowReceived['dtr_type_id'] && date('Y-m-d',strtotime($rowReceived['received_at']))==date('Y-m-d')){
                                $checked = '<input type="checkbox" class="form-control receiveDTR" 
                                            data-id="'.$r['id'].'"
                                            data-type="'.$rowType['id'].'" checked>'.date('M d, Y h:ia',strtotime($rowReceived['received_at']));
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

    public function dtrView(Request $request){
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

    public function receiveDTR(Request $request){
        $result = 'error';
        
        

        $data = array('result' => $result);
        return response()->json($data);
    }
}