<?php

namespace App\Http\Controllers\HRIMS\Employee;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeePaginateController extends Controller
{
    public function paginate(Request $request)
    {
        $name_services = new NameServices;

        $option = $request->option;
        $page = $request->page;
        $column = $request->column;
        $direction = $request->direction;

        $query = $this->paginateQuery($request);

        $x = 1;
        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        $totalQuery = $query['data']->get()->count();

        if($column==0){
            $query['data'] = $query['data']->orderBy('lastname','ASC')->orderBy('firstname','ASC');
        }elseif($column==1){
            $query['data'] = $query['data']->orderBy('id_no',$direction);
        }elseif($column==2){
            $query['data'] = $query['data']->orderBy('lastname',$direction)->orderBy('firstname',$direction);
        }else{
            if($column==3){
                $column_select = 'position_title';
            }elseif($column==4){
                $column_select = 'salary';
            }else{
                $column_select = 'employment_status.name';
            }

            if($option==2){
                $query['data'] = $query['data']->orderBy(function ($query) use ($column_select) {
                    $query->select($column_select)
                        ->from('_work')
                        ->leftjoin('employment_status', '_work.emp_stat_id', '=', 'employment_status.id')
                        ->whereColumn('_work.user_id', 'users.id')
                        ->where('role_id',2)
                        ->orderBy('date_from','asc')
                        ->limit(1);
                }, $direction);
            }elseif($option==3){
                $query['data'] = $query['data']->orderBy(function ($query) use ($column_select) {
                    $query->select($column_select)
                        ->from('_work')
                        ->leftjoin('employment_status', '_work.emp_stat_id', '=', 'employment_status.id')
                        ->whereColumn('_work.user_id', 'users.id')
                        ->where('role_id',3)
                        ->orderBy('date_from','asc')
                        ->limit(1);
                }, $direction);
            }else{
                $query['data'] = $query['data']->orderBy(function ($query) use ($column_select) {
                    $query->select($column_select)
                        ->from('_work')
                        ->leftjoin('employment_status', '_work.emp_stat_id', '=', 'employment_status.id')
                        ->whereColumn('_work.user_id', 'users.id')
                        ->orderBy('status','desc')
                        ->orderBy('emp_stat_id','desc')
                        ->orderBy('date_from','asc')
                        ->limit(1);
                }, $direction);
            }
        }

        $data = $query['data']->skip($offset)->take($perPage)->get();
        $datas = [];
        if($data->count()>0){
            foreach ($data as $row) {
                $row->load('employee_info.emp_stat', 'instructor_info.emp_stat', 'employee_default.emp_stat');
                $name = $name_services->lastname($row->lastname,$row->firstname,$row->middlename,$row->extname);
                $position = '';
                $salary = '';
                $emp_stat = '';
                $fund_service = '';
                if($option==2){
                    if(isset($row->employee_info)){
                        $position = $row->employee_info->position_title;
                        $salary = $row->employee_info->salary;
                        $emp_stat = $row->employee_info->emp_stat->name;
                        if($row->employee_info->fund_services_id){
                            $fund_service = $row->employee_info->fund_service->shorten;
                        }
                    }
                }elseif($option==3){
                    if(isset($row->instructor_info)){
                        $position = $row->instructor_info->position_title;
                        $salary = $row->instructor_info->salary;
                        $emp_stat = $row->instructor_info->emp_stat->name;
                        if($row->instructor_info->fund_services_id){
                            $fund_service = $row->instructor_info->fund_service->shorten;
                        }
                    }
                }else{
                    if(isset($row->employee_default)){
                        $position = $row->employee_default->position_title;
                        $salary = $row->employee_default->salary;
                        $emp_stat = $row->employee_default->emp_stat->name;
                        if($row->employee_default->fund_services_id){
                            $fund_service = $row->employee_default->fund_service->shorten;
                        }
                    }
                }
                if(!$salary){
                    $salary = '-';
                }else{
                    $salary = number_format($salary,2);
                }
                $datas[] = array(
                    'id' => $row->id,
                    'name' => $name,
                    'id_no' => $row->id_no,
                    'position' => $position,
                    'salary' => $salary,
                    'emp_stat' => $emp_stat,
                    'fund_service' => $fund_service
                );
            }
        }

        $totalPages = (int) ceil($totalQuery / $perPage);
        $currentPageSet = ceil($page / 5);
        $startPage = ($currentPageSet - 1) * 5 + 1;
        $endPage = min($startPage + 4, $totalPages);

        $links = [];
        for ($i = $startPage; $i <= $endPage; $i++) {
            $links[] = ['page_number' => $i];
        }

        return response()->json([
            'links' => $links,
            'current_page' => $page,
            'perPage' => $perPage,
            'total_pages' => $totalPages,
            'total_query' => $totalQuery,
            'list' => $datas,
            'offset' => $offset,
            'option' => $option
        ]);
    }

    public function counts(Request $request){
        $option = $request->option;

        $total = 0;
        $total_active = 0;
        $permanent = 0;
        $temporary = 0;
        $casual = 0;
        $job_order = 0;
        $part_time = 0;
        $separated = 0;

        $total = $this->getTotalCount('all');
        $total_active = $this->getTotalCount('active');

        $all = $this->getOptionCount($option,'all');
        $separated = $this->getOptionCount($option,2);

        $permanent = $this->getEmpStatCount($option,1);
        $temporary = $this->getEmpStatCount($option,3);
        $casual = $this->getEmpStatCount($option,2);
        $job_order = $this->getEmpStatCount($option,4);
        $part_time = $this->getEmpStatCount($option,5);

        return response()->json([
            'total' => $total,
            'total_active' => $total_active,
            'all' => $all,
            'permanent' => $permanent,
            'temporary' => $temporary,
            'casual' => $casual,
            'job_order' => $job_order,
            'part_time' => $part_time,
            'separated' => $separated
        ]);
    }

    private function paginateQuery($request){
        $value = $request->value;
        $option = $request->option;
        $status = $request->status;

        $data = Users::whereHas('user_role', function ($query) use ($option,$status) {
                if($option=='all'){
                    $query->where('role_id','>',1);
                }else{
                    $query->where('role_id',$option);
                }
                if($status=='all'){
                    $query->where('emp_status_id','>=',1);
                }elseif($status=='separated'){
                    $query->where('emp_status_id',2);
                }else{
                    $query->where('emp_status_id',1);
                }
            });

        if($status!='all' && $status!='separated' && $status!='active'){
            if($option==2){
                $data = $data->whereHas('employee_info', function ($query) use ($status) {
                        $query->where('emp_stat_id',$status);
                    });
            }elseif($option==3){
                $data = $data->whereHas('instructor_info', function ($query) use ($status) {
                        $query->where('emp_stat_id',$status);
                    });
            }else{
                $data = $data->whereHas('employee_default', function ($query) use ($status) {
                        $query->where('emp_stat_id',$status);
                    });
            }
        }

        if($value!=''){
            $data = $data->where(function($subQuery) use ($value,$option) {
                    $subQuery->where('id_no', 'like', '%'.$value.'%');
                    $subQuery->orWhere('lastname', 'like', '%'.$value.'%');
                    $subQuery->orWhere('firstname', 'like', '%'.$value.'%');
                    if($option==2){
                        $subQuery->orWhereHas('employee_info', function ($query) use ($value) {
                            $query->where('salary', 'like', '%'.$value.'%');
                            $query->orWhereHas('emp_stat', function ($query) use ($value) {
                                $query->where('name', 'like', '%'.$value.'%');
                            });
                        });
                    }elseif($option==3){
                        $subQuery->orWhereHas('instructor_info', function ($query) use ($value) {
                            $query->where('salary', 'like', '%'.$value.'%');
                            $query->orWhereHas('emp_stat', function ($query) use ($value) {
                                $query->where('name', 'like', '%'.$value.'%');
                            });
                        });
                    }else{
                        $subQuery->orWhereHas('employee_default', function ($query) use ($value) {
                            $query->where('salary', 'like', '%'.$value.'%');
                            $query->orWhereHas('emp_stat', function ($query) use ($value) {
                                $query->where('name', 'like', '%'.$value.'%');
                            });
                        });
                    }
                });
        }

        return ['data' => $data];
    }

    private function getTotalCount($option){
        $data = Users::whereHas('user_role', function ($query) use ($option) {
            $query->where('role_id','>',1);
            if($option=='all'){
                $query->where('emp_status_id','>=',1);
            }else{
                $query->where('emp_status_id',1);
            }
        })->get(['id'])->count();
        return $data;
    }

    private function getOptionCount($option,$status){
        $data = Users::whereHas('user_role', function ($query) use ($option,$status) {
            if($option=='all'){
                $query->where('role_id','>',1);
            }else{
                $query->where('role_id',$option);
            }
            if($status=='all'){
                $query->where('emp_status_id','>=',1);
            }else{
                $query->where('emp_status_id',2);
            }
        })->get(['id'])->count();
        return $data;
    }

    private function getEmpStatCount($option,$status){
        $data = Users::whereHas('user_role', function ($query) use ($option) {
            if($option=='all'){
                $query->where('role_id','>',1);
            }else{
                $query->where('role_id',$option);
            }
            $query->where('emp_status_id',1);
        });

        if($option==2){
            $data = $data->whereHas('employee_info', function ($query) use ($status) {
                    $query->where('emp_stat_id',$status);
                });
        }elseif($option==3){
            $data = $data->whereHas('instructor_info', function ($query) use ($status) {
                    $query->where('emp_stat_id',$status);
                });
        }else{
            $data = $data->whereHas('employee_default', function ($query) use ($status) {
                    $query->where('emp_stat_id',$status);
                });
        }
        return $data = $data->get(['id'])->count();
    }


}
