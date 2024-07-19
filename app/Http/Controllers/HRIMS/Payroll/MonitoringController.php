<?php

namespace App\Http\Controllers\HRIMS\Payroll;

use App\Http\Controllers\Controller;
use App\Models\HRPTOption;
use App\Models\HRPTSY;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $options = array('partTime','overLoad');
        $option = $request->option;

        // Check if exists in array
        if (!in_array($option, $options)){
            return view('layouts/error/404');
        }

        return $this->$option($option);
    }
    private function partTime($option){
        $school_years = HRPTSY::with('grade_period')->get();
        $options = HRPTOption::get();
        $data = array(
            'school_years' => $school_years,
            'options' => $options
        );
        return view('hrims/payroll/monitoring/partTime',$data);
    }
    private function overLoad($option){
        $school_years = HRPTSY::with('grade_period')->get();
        $options = HRPTOption::get();
        $data = array(
            'school_years' => $school_years,
            'options' => $options
        );
        return view('hrims/payroll/monitoring/overLoad',$data);
    }
}
