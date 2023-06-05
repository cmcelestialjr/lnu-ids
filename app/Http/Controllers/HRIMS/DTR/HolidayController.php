<?php

namespace App\Http\Controllers\HRIMS\DTR;
use App\Http\Controllers\Controller;
use App\Models\Holidays;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HolidayController extends Controller
{   
    public function table(Request $request){
        $data = array();
        $year = $request->year;
        $query = Holidays::where('option','Yes')
            ->orWhereYear('date',$year)
            ->orderBy('date','ASC')->get()
            ->map(function($query) use ($year) {
                return [
                    'id' => $query->id,
                    'name' => $query->name,
                    'date' => date('F d, Y', strtotime($year.'-'.date('m-d',strtotime($query->date)))),
                    'type' => $query->type
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['date'];
                $data_list['f4'] = $r['type'];
                $data_list['f5'] = '<button class="btn btn-primary btn-primary-scan btn-sm holidayView"
                                        data-id="'.$r['id'].'"
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }

    public function newModal(Request $request){
        $data = array(
            'modal' => ''
        );
        return view('hrims/dtr/holidayNewModal',$data);
    }
    public function newSubmit(Request $request){
        $result = 'error';
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $updated_by = $user->id;
        if($user_access_level==1 || $user_access_level==2){
            $name = $request->name;
            $date = date('Y-m-d', strtotime($request->date));
            $type = $request->type;
            $option = $request->option;
            if($type=='Regular'){
                $option = 'Yes';
            }
            $month = date('m',strtotime($date));
            $day = date('d',strtotime($date));
            $check = Holidays::where(function($query) use ($option,$month,$day){
                    $query->whereMonth('date',$month)
                    ->whereDay('date',$day)
                    ->where('option','Yes');
                })->orWhere('date',$date)
                ->first();
            if($check==NULL){
                $insert = new Holidays();
                $insert->name = $name;
                $insert->date = $date;
                $insert->type = $type;
                $insert->option = $option;
                $insert->updated_by = $updated_by;
                $insert->save();
                $result = 'success';
            }else{
                $result = 'exists';
            }
        }
        $response = array('result' => $result
                        );
        return $response; 
    }
}