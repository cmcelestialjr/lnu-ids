<?php

namespace App\Http\Controllers\HRIMS\Payroll;

use App\Http\Controllers\Controller;
use App\Models\_Work;
use App\Models\EducCoursesNstp;
use App\Models\EducDepartments;
use App\Models\EducGradePeriod;
use App\Models\FundServices;
use App\Models\FundSource;
use App\Models\HRPosition;
use App\Models\HRPT;
use App\Models\HRPTOption;
use App\Models\HRPTSY;
use App\Models\Users;
use App\Services\NameServices;
use DateTime;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class OverLoadController extends Controller
{
    public function index(Request $request)
    {
        $data = array();

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
                $query->where('emp_stat_id', 7);
                if($option){
                    $query->where(function ($query) use ($option) {
                        $query->whereIn('pt_option_id', $option)
                            ->orWhereNull('pt_option_id');
                    });
                }
            }])
            ->with(['payrolls' => function ($query) use ($date_from,$date_to,$option){
                $query->where('emp_stat_id', 7);
                if($option){
                    $query->whereIn('pt_option_id', $option);
                }
                $query->whereHas('payroll', function ($query) use ($date_from, $date_to) {
                    $query->where('payroll_type_id', 1)
                          ->where('year', '>=', $date_from->format('Y'))
                          ->where('year', '<=', $date_to->format('Y'))
                          ->where('month', '>=', $date_from->format('m'))
                          ->where('month', '<=', $date_to->format('m'));
                });
            }])->with(['work' => function ($query) use ($option) {
                $query->where('emp_stat_id',7);
                $query->where('date_to','present');
                if($option){
                    $query->where(function ($query) use ($option) {
                        $query->whereIn('pt_option_id', $option)
                            ->orWhereNull('pt_option_id');
                    });
                }
            }])
            ->whereHas('work', function ($query) use ($option) {
                $query->where('emp_stat_id',7);
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

        $data = array(
            'query' => $query,
            'pt_sy' => $pt_sy,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'current_date' => $current_date,
            'name_services' => $name_services
        );
        return view('hrims/payroll/monitoring/overLoadTable',$data);
    }
    public function syNew(Request $request)
    {
        $grade_periods = EducGradePeriod::get();

        $data = array(
            'grade_periods' => $grade_periods,
        );
        return view('hrims/payroll/monitoring/overLoadSyNew',$data);
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
                $query->where('emp_stat_id',7);
                $query->where('date_to','present');
                if($option){
                    $query->where(function ($query) use ($option) {
                        $query->whereIn('pt_option_id', $option);
                    });
                }
            })->orderBy('lastname','ASC')
            ->orderBy('firstname','ASC')->get();

        $data = array(
            'pt_options' => $pt_options,
            'nstp_options' => $nstp_options,
            'fund_sources' => $fund_sources,
            'departments' => $departments,
            'fund_services' => $fund_services,
            'employees' => $employees
        );
        return view('hrims/payroll/monitoring/overLoadAddEmployee',$data);
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
            ->where('emp_stat_id',7)
            ->orderBy('id','DESC')
            ->first();

        $pt = HRPT::with('pt_option')
            ->where('pt_sy_id',$sy)
            ->where('user_id',$id)
            ->where('pt_option_id',$option_id)
            ->where('emp_stat_id',7)
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

        $data = array(
            'id' => $id,
            'option_id' => $check_work->pt_option_id,
            'work_id' => $work_id,
            'name' => $name,
            'rate' => $rate,
            'units' => $units,
            'total_hours' => $total_hours,
            'pt_option' => $pt_option
        );
        return view('hrims/payroll/monitoring/overLoadRemove',$data);
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
            ->where('emp_stat_id',7)
            ->orderBy('id','DESC')
            ->first();

        $pt = HRPT::where('pt_sy_id',$sy)
            ->where('user_id',$id)
            ->where('pt_option_id',$option_id)
            ->where('emp_stat_id',7)
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

        $data = array(
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
        );
        return view('hrims/payroll/monitoring/overLoadUpdate',$data);
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
            $update->emp_stat_id = 7;
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
                    ->where('emp_stat_id',7)
                    ->delete();
                DB::update("ALTER TABLE `hr_pt` AUTO_INCREMENT = 1;");
            }else{
                $pt = HRPT::where('pt_sy_id',$sy)
                    ->where('user_id',$id)
                    ->where('pt_option_id', $option_id)
                    ->where('emp_stat_id',7)
                    ->first();
                if($pt){
                    $insert = HRPT::find($pt->id);
                }else{
                    $insert = new HRPT();
                    $insert->pt_sy_id = $sy;
                    $insert->user_id = $id;
                    $insert->pt_option_id = $option_id;
                    $insert->emp_stat_id = 7;
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
                    ->where('emp_stat_id',7)->delete();
                DB::update("ALTER TABLE `hr_pt` AUTO_INCREMENT = 1;");
            }else{
                $pt = HRPT::where('pt_sy_id',$sy)
                    ->where('pt_option_id',$option_id)
                    ->where('user_id',$id)
                    ->where('emp_stat_id',7)
                    ->first();
                if($pt){
                    $insert = HRPT::find($pt->id);
                }else{
                    $insert = new HRPT();
                    $insert->pt_option_id = $option_id;
                    $insert->pt_sy_id = $sy;
                    $insert->user_id = $id;
                    $insert->emp_stat_id = 7;
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
    public function removeSubmit(Request $request){
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
                ->where('emp_stat_id', 7)
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
    private function get_position($name,$shorten,$salary,$fund_source_id,$fund_services_id,$office_id,$pt_option_id,$current_user_id,$updated_by){
        HRPosition::where('emp_stat_id', 7)
            ->where('pt_option_id',$pt_option_id)
            ->where('current_user_id',$current_user_id)
            ->update(['type_id' => 2,
                        'current_user_id' => NULL]);

        $get_position = HRPosition::whereNull('current_user_id')
            ->where('pt_option_id',$pt_option_id)
            ->whereYear('created_at',date('Y'))
            ->where('emp_stat_id',7)
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
                ->where('emp_stat_id',7)
                ->first();
            if($check_user_position){
                return $check_user_position->id;
            }else{
                $position = HRPosition::where('emp_stat_id',7)
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
                $insert->emp_stat_id = 7;
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
