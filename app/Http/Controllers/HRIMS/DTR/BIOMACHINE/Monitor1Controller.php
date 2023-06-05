<?php

namespace App\Http\Controllers\HRIMS\DTR\BIOMACHINE;
use App\Http\Controllers\Controller;
use App\Models\UsersDTR;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Monitor1Controller extends Controller
{
    public function monitor1(Request $request){
        return view('hrims/dtr/biomachine/monitor1');
    }
    public function display(Request $request){
        $currentDateTime = Carbon::now();
        $ipaddress = '10.5.201.72';
        $query = UsersDTR::where('ipaddress','10.5.201.72')->orderBy('updated_at','DESC')->first();
        $diffInSeconds = 0;
        if($query!=NULL){
            $toDateTime = Carbon::parse($query->updated_at);
            $diffInSeconds = $currentDateTime->diffInSeconds($toDateTime);
        }
        $display = 'none';
        if($diffInSeconds>0 && $diffInSeconds<=5){
            $display = 'view';
        }
        $data = array(
            'query' => $query,
            'display' => $display,
            'diffInSeconds' => $diffInSeconds
        );
        return view('hrims/dtr/biomachine/monitor1_display',$data);
    }
}