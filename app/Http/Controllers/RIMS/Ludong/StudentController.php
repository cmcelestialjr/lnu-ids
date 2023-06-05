<?php

namespace App\Http\Controllers\RIMS\Ludong;
use App\Http\Controllers\Controller;
use App\Models\FundCluster;
use App\Models\Ludong\LudongGradeLog;
use App\Models\Ludong\LudongStudents;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDO;

class StudentController extends Controller
{
    public function table(Request $request){
        return $this->_table($request);
    }
    private function _table($request){
        $name_services = new NameServices;
        $data = array();
        $id = $request->id;
        $query = LudongGradeLog::
            where('stud_id', $id)
            ->orderBy('stud_id','ASC')
            ->orderBy('catalog_no','ASC')
            ->get()
            ->map(function($query) use ($name_services) {
                $name = $name_services->lastname($query->info->surname,$query->info->first_name,$query->info->middle_name,NULL);
                $pay_unit = '';
                $load_unit = '';
                if(isset($query->subject)){
                    $pay_unit = $query->subject->pay_units;
                }                
                if(isset($query->subject)){
                    $load_unit = $query->subject->load_units;
                }
                return [
                    'id' => $query->stud_id,
                    'name' => $name,
                    'subjects' => $query->catalog_no,
                    'pay_unit' => $pay_unit,
                    'load_unit' => $load_unit,
                    'grade' => $query->grade,
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            $old_subject = '';
            $old_grade = '';
            $old_load_unit = '';
            foreach($query as $r){
                if($r['subjects']!=$old_subject || $r['grade']!=$old_grade || $r['load_unit']!=$old_load_unit){
                    $data_list['f1'] = $x;
                    $data_list['f2'] = $r['id'];
                    $data_list['f3'] = $r['name'];
                    $data_list['f4'] = $r['subjects'];
                    $data_list['f5'] = $r['pay_unit'];
                    $data_list['f6'] = $r['load_unit'];
                    $data_list['f7'] = $r['grade'];
                    array_push($data,$data_list);
                    $x++;
                }
                $old_subject = $r['subjects'];
                $old_grade = $r['grade'];
                $old_load_unit = $r['load_unit'];
                
            }
        }
        return  response()->json($data);
    }
}