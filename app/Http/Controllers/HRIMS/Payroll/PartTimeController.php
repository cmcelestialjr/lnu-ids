<?php

namespace App\Http\Controllers\HRIMS\Payroll;

use App\Http\Controllers\Controller;
use App\Models\_Work;
use App\Models\EducCoursesNstp;
use App\Models\EducDepartments;
use App\Models\EducGradePeriod;
use App\Models\FundServices;
use App\Models\FundSource;
use App\Models\Holidays;
use App\Models\HRPayrollMonths;
use App\Models\HRPosition;
use App\Models\HRPT;
use App\Models\HRPTMonths;
use App\Models\HRPTOption;
use App\Models\HRPTSY;
use App\Models\Users;
use App\Models\UsersDTR;
use App\Models\UsersDTRInfo;
use App\Models\UsersDTRInfoTotal;
use App\Models\UsersSchedDays;
use App\Services\DTRInfoServices;
use App\Services\NameServices;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class PartTimeController extends Controller
{
    public function index(Request $request)
    {
        $data = [];

        $validator = Validator::make($request->all(), [
            'sy' => 'required|numeric',
            //'option' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $name_services = new NameServices;
        $sy = $request->sy;
        $option = $request->option;
        $check_option = 1;
        $pt_sy = HRPTSY::find($sy);

        if($option){
            $check_option = HRPTOption::whereIn('id',$option)->get();
        }

        if ($pt_sy==NULL || $check_option==NULL) {
            return view('layouts/error/404');
        }

        $date_from = new DateTime($pt_sy->date_from);
        $date_to = new DateTime($pt_sy->date_to);

        $query = Users::with(['part_time' => function ($query) use ($sy,$option) {
                $query->where('pt_sy_id', $sy);
                $query->where('emp_stat_id',5);
                if($option){
                    $query->where(function ($query) use ($option) {
                        $query->whereIn('pt_option_id', $option)
                            ->orWhereNull('pt_option_id');
                    });
                }
            }])
            ->with(['payrolls' => function ($query) use ($date_from,$date_to,$option){
                $query->where('emp_stat_id', 5);
                if($option){
                    $query->whereIn('pt_option_id', $option);
                }
                $query->whereHas('months', function ($query) use ($date_from, $date_to) {
                    $query->where('year', '>=', $date_from->format('Y'))
                          ->where('year', '<=', $date_to->format('Y'))
                          ->where('month', '>=', $date_from->format('m'))
                          ->where('month', '<=', $date_to->format('m'));
                });
                $query->whereHas('payroll', function ($query) {
                    $query->where('payroll_type_id', 1);
                });
            }])->with(['work' => function ($query) use ($option) {
                $query->where('emp_stat_id',5);
                $query->where('date_to','present');
                if($option){
                    $query->where(function ($query) use ($option) {
                        $query->whereIn('pt_option_id', $option)
                            ->orWhereNull('pt_option_id');
                    });
                }
            }])->with(['pt_months' => function ($query) use ($sy,$option) {
                $query->where('pt_sy_id',$sy);
                $query->where('emp_stat_id',5);
                if($option){
                    $query->where(function ($query) use ($option) {
                        $query->whereIn('pt_option_id', $option)
                            ->orWhereNull('pt_option_id');
                    });
                }
            }])
            ->whereHas('work', function ($query) use ($option) {
                $query->where('emp_stat_id',5);
                $query->where('date_to','present');
                if($option){
                    $query->where(function ($query) use ($option) {
                        $query->whereIn('pt_option_id', $option)
                            ->orWhereNull('pt_option_id');
                    });
                }
            })
            ->get();

        $current_date = clone $date_from;

        $data = [
            'query' => $query,
            'pt_sy' => $pt_sy,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'current_date' => $current_date,
            'name_services' => $name_services
        ];
        return view('hrims/payroll/monitoring/partTimeTable',$data);
    }
    public function syNew(Request $request)
    {
        $grade_periods = EducGradePeriod::get();

        $data = [
            'grade_periods' => $grade_periods,
        ];
        return view('hrims/payroll/monitoring/partTimeSyNew',$data);
    }
    public function add(Request $request)
    {
        $option = $request->option;

        $pt_options = HRPTOption::get();
        $nstp_options = EducCoursesNstp::get();
        $fund_sources = FundSource::get();
        $departments = EducDepartments::get();
        $fund_services = FundServices::get();
        $employees = Users::where('emp_status_id',1)
            ->whereDoesntHave('work', function ($query) use ($option) {
                $query->where('emp_stat_id',5);
                $query->where('date_to','present');
                if($option){
                    $query->where(function ($query) use ($option) {
                        $query->whereIn('pt_option_id', $option);
                    });
                }
            })->orderBy('lastname','ASC')
            ->orderBy('firstname','ASC')->get();

        $data = [
            'pt_options' => $pt_options,
            'nstp_options' => $nstp_options,
            'fund_sources' => $fund_sources,
            'departments' => $departments,
            'fund_services' => $fund_services,
            'employees' => $employees
        ];
        return view('hrims/payroll/monitoring/partTimeAddEmployee',$data);
    }
    public function remove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'option_id' => 'nullable|numeric',
            'work_id' => 'required|numeric',
            'sy' => 'required|numeric',

        ]);

        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $sy = $request->sy;
        $id = $request->id;
        $option_id = $request->option_id;
        $work_id = $request->work_id;
        $check_option = 1;

        $check_sy = HRPTSY::find($sy);
        $check_user = Users::find($id);
        $check_work = _Work::where('id',$work_id)
            ->where('user_id',$id)
            ->first();
        if($option_id){
            $check_option = HRPTOption::where('id',$option_id)->get();
        }

        if (!$check_sy || !$check_user || !$check_option || !$check_work) {
            return view('layouts/error/404');
        }

        $name_services = new NameServices;
        $name = $name_services->lastname($check_user->lastname,$check_user->firstname,$check_user->middlename,$check_user->extname);
        $rate = 0;
        $units = 0;
        $total_hours = 0;
        $pt_option = '';

        $pt_default = HRPT::with('pt_option')
            ->where('user_id',$id)
            ->where('pt_option_id',$option_id)
            ->where('emp_stat_id',5)
            ->orderBy('id','DESC')
            ->first();

        $pt = HRPT::with('pt_option')
            ->where('pt_sy_id',$sy)
            ->where('user_id',$id)
            ->where('pt_option_id',$option_id)
            ->where('emp_stat_id',5)
            ->first();

        if($pt_default){
            $rate = $pt_default->rate;
            $units = $pt_default->units;
            $total_hours = $pt_default->total_hours;
            $pt_option = '('.$pt_default->pt_option->name.')';
        }

        if($pt){
            $rate = $pt->rate;
            $units = $pt->units;
            $total_hours = $pt->total_hours;
            $pt_option = '('.$pt->pt_option->name.')';
        }

        $data = [
            'id' => $id,
            'option_id' => $check_work->pt_option_id,
            'work_id' => $work_id,
            'name' => $name,
            'rate' => $rate,
            'units' => $units,
            'total_hours' => $total_hours,
            'pt_option' => $pt_option
        ];
        return view('hrims/payroll/monitoring/partTimeRemove',$data);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'option_id' => 'nullable|numeric',
            'work_id' => 'required|numeric',
            'sy' => 'required|numeric',

        ]);

        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $sy = $request->sy;
        $id = $request->id;
        $option_id = $request->option_id;
        $work_id = $request->work_id;
        $check_option = 1;

        $check_sy = HRPTSY::find($sy);
        $check_user = Users::find($id);
        $check_work = _Work::where('id',$work_id)
            ->where('user_id',$id)
            ->first();
        if($option_id){
            $check_option = HRPTOption::where('id',$option_id)->get();
        }

        if (!$check_sy || !$check_user || !$check_option || !$check_work) {
            return view('layouts/error/404');
        }

        $name_services = new NameServices;
        $name = $name_services->lastname($check_user->lastname,$check_user->firstname,$check_user->middlename,$check_user->extname);
        $rate = 0;
        $units = 0;
        $total_hours = 0;

        $pt_default = HRPT::where('user_id',$id)
            ->where('pt_option_id',$option_id)
            ->where('emp_stat_id',5)
            ->orderBy('id','DESC')
            ->first();

        $pt = HRPT::where('pt_sy_id',$sy)
            ->where('user_id',$id)
            ->where('pt_option_id',$option_id)
            ->where('emp_stat_id',5)
            ->first();

        $pt_options = HRPTOption::get();
        $nstp_options = EducCoursesNstp::get();
        $fund_sources = FundSource::get();
        $departments = EducDepartments::get();
        $fund_services = FundServices::get();

        if($pt_default){
            $rate = $pt_default->rate;
            $units = $pt_default->units;
            $total_hours = $pt_default->total_hours;
        }

        if($pt){
            $rate = $pt->rate;
            $units = $pt->units;
            $total_hours = $pt->total_hours;
        }

        $data = [
            'id' => $id,
            'option_id' => $check_work->pt_option_id,
            'work_id' => $work_id,
            'nstp_id' => $check_work->nstp_id,
            'fund_source_id' => $check_work->fund_source_id,
            'fund_services_id' => $check_work->fund_services_id,
            'office_id' => $check_work->office_id,
            'name' => $name,
            'rate' => $rate,
            'units' => $units,
            'total_hours' => $total_hours,
            'pt_options' => $pt_options,
            'nstp_options' => $nstp_options,
            'fund_sources' => $fund_sources,
            'fund_services' => $fund_services,
            'departments' => $departments
        ];
        return view('hrims/payroll/monitoring/partTimeUpdate',$data);
    }
    public function viewOptions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'option_id' => 'nullable|numeric',
            'work_id' => 'required|numeric',
            'sy' => 'required|numeric',
            'year' => 'required|numeric',
            'month' => 'required|string',
        ]);

        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $sy = $request->sy;
        $id = $request->id;
        $option_id = $request->option_id;
        $work_id = $request->work_id;
        $year = $request->year;
        $month = $request->month;

        $check_sy = HRPTSY::find($sy);
        $check_user = Users::find($id);
        $check_work = _Work::where('id',$work_id)
            ->where('user_id',$id)
            ->first();
        if($option_id){
            $check_option = HRPTOption::where('id',$option_id)->get();
        }

        if (!$check_sy || !$check_user || !$check_option || !$check_work) {
            return view('layouts/error/404');
        }

        $hour = 0;

        $pt_month = HRPTMonths::where('user_id',$id)
            ->where('pt_sy_id',$sy)
            ->where('pt_option_id',$option_id)
            ->where('emp_stat_id',5)
            ->where('year',$year)
            ->where('month',$month)
            ->first();

        $payroll_month = HRPayrollMonths::where('user_id',$id)
            ->where('pt_option_id',$option_id)
            ->where('year',$year)
            ->where('month',$month)
            ->whereHas('list', function ($query) {
                $query->where('emp_stat_id', 5);
            })
            ->first();

        $hour = $pt_month ? $pt_month->hour : 0;
        $hour = $payroll_month ? $payroll_month->amount : $hour;

        $data = [
            'id' => $id,
            'payroll_month' => $payroll_month,
            'year' => $year,
            'month' => $month,
            'hour' => $hour,
            'option_id' => $option_id
        ];
        return view('hrims/payroll/monitoring/partTimeViewOptions',$data);
    }
    public function viewDtr(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'year' => 'required|numeric',
            'month' => 'required|string',
        ]);

        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;

        $check_user = Users::find($id);

        if(!$check_user){
            return view('layouts/error/404');
        }

        $dtr_info_service = new DTRInfoServices;
        $year = $request->year;
        $month = $request->month;
        $id_no = $check_user->id_no;
        $option_id = 2;
        $last_date = date('Y-m-t',strtotime($year.'-'.$month.'-01'));
        $holidays = 0;
        $lastDay = date('t',strtotime($year.'-'.$month.'-01'));
        $dtr = [];
        $included_days = [];
        $defaultValues = [
            'day' => null,
            'check' => '',
            'holiday' => '',
            'in_am' => '',
            'out_am' => '',
            'in_pm' => '',
            'out_pm' => '',
            'time_type' => '',
            'time_type_name' => '',
            'time_in_am_type' => 0,
            'time_out_am_type' => 0,
            'time_in_pm_type' => 0,
            'time_out_pm_type' => 0,
            'hours' => 0,
            'minutes' => 0,
            'tardy_hr' => 0,
            'tardy_min' => 0,
            'tardy_no' => 0,
            'ud_hr' => 0,
            'ud_min' => 0,
            'ud_no' => 0,
            'hd_hr' => 0,
            'hd_min' => 0,
            'hd_no' => 0,
            'abs_hr' => 0,
            'abs_min' => 0,
            'abs_no' => 0,
            'sched_time' => []
        ];
        $data_info = [
            'id_no' => $id_no,
            'year' => $year,
            'month' => $month
        ];
        $dtr_info_service->removeDuplicate($data_info);


        $getDtr = UsersDTR::with('time_type_')
            ->whereHas('user', function ($query) use ($id) {
                $query->where('id', $id);
            })->whereYear('date',$year)
            ->whereMonth('date',$month)
            ->orderBy('date','ASC')
            ->get();
        $getDtrNext = UsersDTR::with('time_type_')
            ->whereHas('user', function ($query) use ($id) {
                $query->where('id', $id);
            })->whereDate('date',date('Y-m-d',strtotime($last_date . ' +1 day')))
            ->orderBy('date','ASC')
            ->first();
        $getDtrInfo = UsersDTRInfo::where('user_id',$id)
            ->whereYear('date',$year)
            ->whereMonth('date',$month)
            ->where('option_id',$option_id)
            ->orderBy('date','ASC')
            ->get();
        $getDtrSched = UsersSchedDays::with(['time' => function ($query) use ($id,$year,$month,$option_id) {
                $query->where('user_id',$id)
                ->where('option_id',$option_id)
                ->where('date_to','>=',date('Y-m-d',strtotime($year.'-'.$month.'-01')))
                ->where('date_from','<=',date('Y-m-t',strtotime($year.'-'.$month.'-01')))
                ->orderBy('time_from', 'DESC');
            }])
            ->whereHas('time', function ($query) use ($id,$year,$month,$option_id) {
                $query->where('user_id',$id)
                ->where('option_id',$option_id)
                ->where('date_to','>=',date('Y-m-d',strtotime($year.'-'.$month.'-01')))
                ->where('date_from','<=',date('Y-m-t',strtotime($year.'-'.$month.'-01')));
            })->get();
        $getHolidays = Holidays::where(function ($query) use ($month) {
                $query->whereMonth('date', $month)
                      ->where('option', 'Yes');
            })->orWhere(function ($query) use ($year,$month) {
                $query->whereYear('date', $year)
                      ->whereMonth('date', $month);
            })->get();

        for ($i = 1; $i <= $lastDay; $i++){
            $weekDay = date('w', strtotime($year.'-'.$month.'-'.$i));
            if($weekDay==0){
                $weekDay = 7;
            }
            $dtr[$i] = $defaultValues;
            $dtr[$i]['day'] = $i;

            foreach ($getDtrSched as $row){
                if($weekDay==$row->day){
                    if($row->time->date_from<=date('Y-m-d', strtotime($year.'-'.$month.'-'.$i)) &&
                        $row->time->date_to>=date('Y-m-d', strtotime($year.'-'.$month.'-'.$i))
                    ){
                        $dtr[$i]['check'] = 'included';
                        $dtr[$i]['sched_time'][] = [
                            'in' => $row->time->time_from,
                            'out' => $row->time->time_to,
                            'is_rotation_duty' => $row->time->is_rotation_duty
                        ];
                    }
                }
            }
            if($dtr[$i]['check'] == 'included'){
                $included_days[] = $i;
            }
        }

        foreach($getHolidays as $row){
            $day = date('j',strtotime($row->date));
            $dtr[$day]['check'] = '';
            $dtr[$day]['holiday'] = $row->name;

            $index = array_search($day, $included_days);
            if ($index !== false) {
                unset($included_days[$index]);
            }else{
                $holidays++;
            }
        }


        $data_info = [
            'user_id' => $id,
            'id_no' => $id_no,
            'dtr' => $dtr,
            'getDtr' => $getDtr,
            'getDtrNext' => $getDtrNext,
            'included_days' => $included_days,
            'year' => $year,
            'month' => $month,
            'option_id' => $option_id,
            'holidays' => $holidays
        ];
        $dtr_info_service->index($data_info);

        for ($k = 0; $k < $getDtr->count(); $k++){
            $row = $getDtr[$k];
            $day = date('j', strtotime($row->date));

            $index = array_search($day, $included_days);
            if ($index !== false) {
                unset($included_days[$index]);
            }

            $in_am = (strtotime($row->time_in_am)) ? date('h:ia',strtotime($row->time_in_am)) : NULL;
            $out_am = (strtotime($row->time_out_am)) ? date('h:ia',strtotime($row->time_out_am)) : NULL;
            $in_pm = (strtotime($row->time_in_pm)) ? date('h:ia',strtotime($row->time_in_pm)) : NULL;
            $out_pm = (strtotime($row->time_out_pm)) ? date('h:ia',strtotime($row->time_out_pm)) : NULL;

            $time_in_am_type = $row->time_in_am_type;
            $time_out_am_type = $row->time_out_am_type;
            $time_in_pm_type = $row->time_in_pm_type;
            $time_out_pm_type = $row->time_out_pm_type;
            $time_type = $row->time_type;

            $dtrEntry = &$dtr[$day];

            $dtrEntry['check'] = 'time';
            $dtrEntry['in_am'] = $in_am;
            $dtrEntry['out_am'] = $out_am;
            $dtrEntry['in_pm'] = $in_pm;
            $dtrEntry['out_pm'] = $out_pm;
            $dtrEntry['time_type'] = $time_type;
            $dtrEntry['time_in_am_type'] = $time_in_am_type;
            $dtrEntry['time_out_am_type'] = $time_out_am_type;
            $dtrEntry['time_in_pm_type'] = $time_in_pm_type;
            $dtrEntry['time_out_pm_type'] = $time_out_pm_type;

            if($row->time_type_){
                $dtr[$day]['time_type_name'] = $row->time_type_->name;
            }

            foreach($dtr[$day]['sched_time'] as $sched){
                if(strtotime($sched['in']) && strtotime($sched['out'])){
                    $in_from = date('H:i',strtotime($sched['in']));
                    $out_to = date('H:i',strtotime($sched['out']));
                    if(!$time_type){
                        if($in_from<'12:00' && $out_to>='14:01'){
                            if(!$in_am){
                                $dtrEntry['time_in_am_type'] = 0;
                            }
                            if(!$out_am){
                                $dtrEntry['time_out_am_type'] = 0;
                            }
                            if(!$in_pm){
                                $dtrEntry['time_in_pm_type'] = 0;
                            }
                            if(!$out_pm){
                                $dtrEntry['time_out_pm_type'] = 0;
                            }
                        }elseif($in_from<'12:00' && $out_to<='14:00'){
                            if(!$in_am){
                                $dtrEntry['time_in_am_type'] = 0;
                            }
                            if(!$out_am){
                                $dtrEntry['time_out_am_type'] = 0;
                            }
                        }elseif($in_from>='12:00' && $out_to>'12:00'){
                            if(!$in_pm){
                                $dtrEntry['time_in_pm_type'] = 0;
                            }
                            if(!$out_pm){
                                $dtrEntry['time_out_pm_type'] = 0;
                            }
                        }elseif($in_from>='12:00' && $out_to<'12:00'){
                            if(!$out_am){
                                $dtrEntry['time_out_am_type'] = 0;
                            }
                            if(!$out_pm){
                                $dtrEntry['time_out_pm_type'] = 0;
                            }
                        }
                    }
                }
            }
        }

        foreach ($getDtrInfo as $row){
            $day = date('j',strtotime($row->date));
            $dtr[$day]['hours'] = $row->hours;
            $dtr[$day]['minutes'] = $row->minutes;
            $dtr[$day]['tardy_hr'] = $row->tardy_hr;
            $dtr[$day]['tardy_min'] = $row->tardy_min;
            $dtr[$day]['tardy_no'] = $row->tardy_no;
            $dtr[$day]['ud_hr'] = $row->ud_hr;
            $dtr[$day]['ud_min'] = $row->ud_min;
            $dtr[$day]['ud_no'] = $row->ud_no;
            $dtr[$day]['hd_hr'] = $row->hd_hr;
            $dtr[$day]['hd_min'] = $row->hd_min;
            $dtr[$day]['hd_no'] = $row->hd_no;
            $dtr[$day]['abs_hr'] = $row->abs_hr;
            $dtr[$day]['abs_min'] = $row->abs_min;
            $dtr[$day]['abs_no'] = $row->abs_no;
            $dtr[$day]['earned_hours'] = $row->earned_hours;
            $dtr[$day]['earned_minutes'] = $row->earned_minutes;
        }

        $getDtrInfoTotal = UsersDTRInfoTotal::where('user_id',$id)
            ->whereYear('date',$year)
            ->whereMonth('date',$month)
            ->where('option_id',$option_id)
            ->first();

        $data = [
            'dtr' => $dtr,
            'dtrTotal' => $getDtrInfoTotal,
            'year' => $year,
            'month' => $month,
            'lastDay' => $lastDay,
            'current_url' => 'monitoring'

        ];
        return view('hrims/payroll/monitoring/partTimeViewDtr',$data);
    }
    public function syNewSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_year' => 'required|string|regex:/^\d{4}-\d{4}$/',
            'period' => 'required|numeric',
            'month_from' => 'required|string',
            'month_to' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $school_year = $request->input('school_year');
        $period = $request->input('period');
        $month_from = $request->input('month_from');
        $month_to = $request->input('month_to');

        $exp_sy = explode('-',$school_year);
        $year_from = $exp_sy[0];
        $year_to = $exp_sy[1];

        $check_period = EducGradePeriod::find($period);

        if(!$check_period){
            return response()->json(['result' => 'error']);
        }

        if($period==1){
            $date_from = date('Y-m-d',strtotime($year_from.'-'.$month_from.'-01'));
            $date_to = date('Y-m-t',strtotime($year_from.'-'.$month_to.'-01'));
        }elseif($period==2 || $period==4){
            $date_from = date('Y-m-d',strtotime($year_to.'-'.$month_from.'-01'));
            $date_to = date('Y-m-t',strtotime($year_to.'-'.$month_to.'-01'));
        }else{
            $date_from = date('Y-m-d',strtotime($year_from.'-'.$month_from.'-01'));
            $date_to = date('Y-m-t',strtotime($year_to.'-'.$month_to.'-01'));
        }

        try{
            $user = Auth::user();
            $updated_by = $user->id;

            $check_sy = HRPTSY::where('year_from',$year_from)
                ->where('year_to',$year_to)
                ->where('grade_period_id',$period)
                ->first(['id']);

            if(!$check_sy){
                $insert = new HRPTSY();
                $insert->year_from = $year_from;
                $insert->year_to = $year_to;
                $insert->grade_period_id = $period;
                $insert->date_from = $date_from;
                $insert->date_to = $date_to;
                $insert->updated_by = $updated_by;
                $insert->save();
            }

            return response()->json(['result' => 'success']);
        } catch (QueryException $e) {
            // Handle database query exceptions
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            // Handle PDO exceptions
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            // Handle other exceptions
            return $this->handleOtherError($e);
        }
    }
    public function addSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee' => 'required|numeric',
            'sy' => 'required|numeric',
            'rate' => 'required|numeric',
            'units' => 'required|numeric',
            'total_hours' => 'required|numeric',
            'type' => 'required|numeric',
            'nstp' => 'nullable|numeric',
            'fund_source' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $id = $request->employee;
        $sy = $request->sy;
        $option_id = $request->type;
        $nstp_id = $request->nstp;
        $fund_source = $request->fund_source;
        $fund_service = $request->fund_service;
        $department = $request->department;
        $check_nstp = 1;
        $check_service = 1;

        $check_sy = HRPTSY::find($sy);
        $check_user = Users::find($id);
        $check_option = HRPTOption::find($option_id);
        $check_fund_source = FundSource::find($fund_source);
        $check_department = EducDepartments::find($department);

        if($nstp_id && $option_id==4){
            $check_nstp = EducCoursesNstp::find($nstp_id);
        }else{
            $nstp_id = NULL;
        }

        if($fund_service!=''){
            $check_service = FundServices::find($fund_service);
        }else{
            $fund_service = NULL;
        }

        if (!$check_sy || !$check_user || !$check_option || !$check_nstp || !$check_fund_source || !$check_service || !$check_department) {
            return response()->json(['result' => 'error']);
        }

        $rate = $request->input('rate');
        $units = $request->input('units');
        $total_hours = $request->input('total_hours');
        $office_id = $check_department->office_id;

        try{
            $user = Auth::user();
            $updated_by = $user->id;

            $position_id = $this->get_position($check_option->position,$check_option->shorten,$rate,$fund_source,$fund_service,$office_id,$option_id,$id,$updated_by);

            $update = new _Work();
            $update->user_id = $id;
            $update->position_id = $position_id;
            $update->role_id = 3;
            $update->emp_stat_id = 5;
            $update->fund_source_id = $fund_source;
            $update->fund_services_id = $fund_service;
            $update->pt_option_id = $option_id;
            $update->nstp_id = $nstp_id;
            $update->office_id = $office_id;
            $update->date_from = $check_sy->date_from;
            $update->date_to = 'present';
            $update->office = 'LNU';
            $update->sg = 0;
            $update->step = 0;
            $update->status = 1;
            $update->gov_service = 'N';
            $update->type_id = 8;
            $update->lnu = 1;
            $update->updated_by = $updated_by;

            if($rate==0 || $units==0 || $total_hours==0){
                HRPT::where('pt_sy_id', $sy)
                    ->where('user_id', $id)
                    ->where('pt_option_id', $option_id)
                    ->where('emp_stat_id',5)
                    ->delete();
                DB::update("ALTER TABLE `hr_pt` AUTO_INCREMENT = 1;");
            }else{
                $pt = HRPT::where('pt_sy_id',$sy)
                    ->where('user_id',$id)
                    ->where('pt_option_id', $option_id)
                    ->where('emp_stat_id',5)
                    ->first();
                if($pt){
                    $insert = HRPT::find($pt->id);
                }else{
                    $insert = new HRPT();
                    $insert->pt_sy_id = $sy;
                    $insert->user_id = $id;
                    $insert->pt_option_id = $option_id;
                    $insert->emp_stat_id = 5;
                }
                $insert->rate = $rate;
                $insert->units = $units;
                $insert->total_hours = $total_hours;
                $insert->updated_by = $updated_by;
                $insert->save();
                $update->salary = $rate;
            }

            $update->save();

            return response()->json(['result' => 'success']);
        } catch (QueryException $e) {
            // Handle database query exceptions
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            // Handle PDO exceptions
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            // Handle other exceptions
            return $this->handleOtherError($e);
        }
    }
    public function updateSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'sy' => 'required|numeric',
            'rate' => 'required|numeric',
            'units' => 'required|numeric',
            'total_hours' => 'required|numeric',
            'type' => 'required|numeric',
            'nstp' => 'nullable|numeric',
            'fund_source' => 'required|numeric',
            'fund_service' => 'nullable|numeric',
            'department' => 'required|numeric',
            'work_id' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $sy = $request->sy;
        $id = $request->id;
        $option_id = $request->type;
        $nstp_id = $request->nstp;
        $work_id = $request->work_id;
        $fund_source = $request->fund_source;
        $fund_service = $request->fund_service;
        $department = $request->department;
        $check_service = 1;

        $check_sy = HRPTSY::find($sy);
        $check_user = Users::find($id);
        $check_pt_option = HRPTOption::find($option_id);
        $check_fund_source = FundSource::find($fund_source);
        $check_department = EducDepartments::find($department);
        $check_work = _Work::where('user_id',$id)
            ->where('id',$work_id)
            ->first();

        if($fund_service!=''){
            $check_service = FundServices::find($fund_service);
        }else{
            $fund_service = NULL;
        }

        if (!$check_sy || !$check_user || !$check_work || !$check_service || !$check_fund_source || !$check_department || !$check_pt_option) {
            return response()->json(['result' => 'error']);
        }

        $rate = $request->input('rate');
        $units = $request->input('units');
        $total_hours = $request->input('total_hours');
        $office_id = $check_department->office_id;

        if(!$nstp_id){
            $nstp_id = NULL;
        }

        try{
            $user = Auth::user();
            $updated_by = $user->id;

            $update = _Work::find($work_id);
            $update->pt_option_id = $option_id;
            $update->nstp_id = $nstp_id;
            $update->fund_source_id = $fund_source;
            $update->fund_services_id = $fund_service;
            $update->office_id = $office_id;
            $update->updated_by = $updated_by;

            if($rate==0 || $units==0 || $total_hours==0){
                HRPT::where('pt_sy_id', $sy)
                    ->where('pt_option_id', $option_id)
                    ->where('user_id', $id)
                    ->where('emp_stat_id',5)->delete();
                DB::update("ALTER TABLE `hr_pt` AUTO_INCREMENT = 1;");
            }else{
                $pt = HRPT::where('pt_sy_id',$sy)
                    ->where('pt_option_id',$option_id)
                    ->where('user_id',$id)
                    ->where('emp_stat_id',5)
                    ->first();
                if($pt){
                    $insert = HRPT::find($pt->id);
                }else{
                    $insert = new HRPT();
                    $insert->pt_option_id = $option_id;
                    $insert->pt_sy_id = $sy;
                    $insert->user_id = $id;
                    $insert->emp_stat_id = 5;
                }
                $insert->rate = $rate;
                $insert->units = $units;
                $insert->total_hours = $total_hours;
                $insert->updated_by = $updated_by;
                $insert->save();
                $update->salary = $rate;
            }

            $update->save();

            return response()->json(['result' => 'success']);
        } catch (QueryException $e) {
            // Handle database query exceptions
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            // Handle PDO exceptions
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            // Handle other exceptions
            return $this->handleOtherError($e);
        }
    }
    public function hoursAccumulated(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sy' => 'required|numeric',
            'id' => 'required|numeric',
            'year' => 'required|numeric',
            'month' => 'required|string',
            'option_id' => 'required|numeric',
            'hour' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $sy = $request->sy;
        $id = $request->id;
        $option_id = $request->option_id;
        $year = $request->year;
        $month = $request->month;
        $hour = $request->hour;

        $check_sy = HRPTSY::find($sy);
        $check_user = Users::find($id);
        $check_pt_option = HRPTOption::find($option_id);
        $check_pt_user = HRPT::where('user_id',$id)
            ->where('pt_sy_id',$sy)
            ->where('pt_option_id',$option_id)
            ->where('emp_stat_id',5)
            ->first();

        if (!$check_sy || !$check_user || !$check_pt_option || !$check_pt_user) {
            return response()->json(['result' => 'error']);
        }

        try{
            $user = Auth::user();
            $updated_by = $user->id;

            $check_pt_month = HRPTMonths::where('user_id',$id)
                ->where('pt_sy_id',$sy)
                ->where('pt_option_id',$option_id)
                ->where('emp_stat_id', 5)
                ->where('year',$year)
                ->where('month',$month)
                ->first();

            if($check_pt_month){
                $update = HRPTMonths::find($check_pt_month->id);
            }else{
                $update = new HRPTMonths();
                $update->user_id = $id;
                $update->pt_id = $check_pt_user->id;
                $update->pt_sy_id = $sy;
                $update->pt_option_id = $option_id;
                $update->emp_stat_id = 5;
                $update->year = $year;
                $update->month = $month;
            }
            $update->updated_by = $updated_by;
            $update->hour = $hour;
            $update->save();

            return response()->json(['result' => 'success',
                                    'year' => $year,
                                    'month' => $month,
                                    'hour' => 'hrs: '.number_format($hour,2)]);
        } catch (QueryException $e) {
            // Handle database query exceptions
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            // Handle PDO exceptions
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            // Handle other exceptions
            return $this->handleOtherError($e);
        }

    }
    public function removeSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sy' => 'required|numeric',
            'id' => 'required|numeric',
            'w' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'error']);
        }

        $sy = $request->sy;
        $id = $request->id;
        $work_id = $request->w;

        $check_sy = HRPTSY::find($sy);
        $check_user = Users::find($id);
        $check_work = _Work::where('user_id',$id)
            ->where('id',$work_id)
            ->first();

        if (!$check_sy || !$check_user || !$check_work) {
            return response()->json(['result' => 'error']);
        }

        $pt_option_id = $check_work->pt_option_id;
        $position_id = $check_work->position_id;

        try{
            $user = Auth::user();
            $updated_by = $user->id;

            $update = HRPosition::find($position_id);
            $update->current_user_id = NULL;
            $update->type_id = 2;
            $update->updated_by = $updated_by;
            $update->save();

            HRPT::where('pt_sy_id', $sy)
                ->where('pt_option_id', $pt_option_id)
                ->where('user_id', $id)
                ->where('emp_stat_id', 5)
                ->delete();
            DB::update("ALTER TABLE `hr_pt` AUTO_INCREMENT = 1;");

            _Work::where('id', $work_id)
                ->delete();
            DB::update("ALTER TABLE `_work` AUTO_INCREMENT = 1;");

            return response()->json(['result' => 'success']);
        } catch (QueryException $e) {
            // Handle database query exceptions
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            // Handle PDO exceptions
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            // Handle other exceptions
            return $this->handleOtherError($e);
        }

    }
    private function get_position($name,$shorten,$salary,$fund_source_id,$fund_services_id,$office_id,$pt_option_id,$current_user_id,$updated_by)
    {
        HRPosition::where('emp_stat_id', 5)
            ->where('pt_option_id',$pt_option_id)
            ->where('current_user_id',$current_user_id)
            ->update(['type_id' => 2,
                        'current_user_id' => NULL]);

        $get_position = HRPosition::whereNull('current_user_id')
            ->where('pt_option_id',$pt_option_id)
            ->whereYear('created_at',date('Y'))
            ->where('emp_stat_id',5)
            ->first();

        if($get_position){
            $insert = HRPosition::find($get_position->id);
            $insert->salary = $salary;
            $insert->fund_source_id = $fund_source_id;
            $insert->fund_services_id = $fund_services_id;
            $insert->type_id = 1;
            $insert->status_id = 4;
            $insert->office_id = $office_id;
            $insert->current_user_id = $current_user_id;
            $insert->updated_by = $updated_by;
            $insert->save();
            return $get_position->id;
        }else{
            $check_user_position = HRPosition::where('current_user_id',$current_user_id)
                ->where('pt_option_id',$pt_option_id)
                ->where('emp_stat_id',5)
                ->first();
            if($check_user_position){
                return $check_user_position->id;
            }else{
                $position = HRPosition::where('emp_stat_id',5)
                    ->where('pt_option_id',$pt_option_id)
                    ->whereYear('created_at',date('Y'))
                    ->orderBy('item_no','DESC')
                    ->first();
                if($position){
                    $exp = explode('-',$position->item_no);
                    $count = str_pad($exp[3]+1, 3, '0', STR_PAD_LEFT);
                    $item_no = 'PT-'.$shorten.'-'.date('Y').'-'.$count;
                }else{
                    $item_no = 'PT-'.$shorten.'-'.date('Y').'-001';
                }
                $insert = new HRPosition();
                $insert->item_no = $item_no;
                $insert->name = $name;
                $insert->shorten = $shorten;
                $insert->salary = $salary;
                $insert->sg = 0;
                $insert->step = 0;
                $insert->gov_service = 'N';
                $insert->emp_stat_id = 5;
                $insert->fund_source_id = $fund_source_id;
                $insert->fund_services_id = $fund_services_id;
                $insert->role_id = 3;
                $insert->type_id = 1;
                $insert->status_id = 4;
                $insert->sched_id = 2;
                $insert->pt_option_id = $pt_option_id;
                $insert->office_id = $office_id;
                $insert->current_user_id = $current_user_id;
                $insert->updated_by = $updated_by;
                $insert->save();
                return $insert->id;
            }
        }
    }
     /**
     * Handle database errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleDatabaseError($e)
    {
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
        return response()->json(['result' => $e->getMessage()], 500);
    }
}
