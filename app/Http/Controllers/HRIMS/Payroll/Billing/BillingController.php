<?php

namespace App\Http\Controllers\HRIMS\Payroll\Billing;
use App\Http\Controllers\Controller;
use App\Models\HRBilling;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function table(Request $request){
        $data = array();
        $query = HRBilling::get()
            ->map(function($query) {
                return [
                    'id' => $query->id,
                    'group' => $query->group->name,
                    'year' => $query->year,
                    'month' => date('M', strtotime($query->year.'-'.$query->month.'-01')),
                    'by' => $query->updatedBy->lastname.', '.$query->updatedBy->firstname,
                    'date' => date('M d, Y h:i a', strtotime($query->created_at)),
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['group'];
                $data_list['f3'] = $r['year'];
                $data_list['f4'] = $r['month'];
                $data_list['f5'] = $r['by'];
                $data_list['f6'] = $r['date'];
                $data_list['f7'] = '<button class="btn btn-primary btn-primary-scan btn-sm update"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span> 
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
}