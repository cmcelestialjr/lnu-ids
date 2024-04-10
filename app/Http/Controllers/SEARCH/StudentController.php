<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function student(Request $request){
        $search = $request->input('search');
        $name_services = new NameServices;
        $results = Users::where(function($query) use ($search) {
                        $query->where(DB::raw('CONCAT(lastname,", ",firstname)'), 'LIKE', "%".$search."%");
                        $query->orWhere('stud_id', 'LIKE', "%$search%");
                    })
                    ->where('stud_id','!=',NULL)
                    // ->whereHas('student_info', function ($query) {
                        
                    // })              
                    ->limit(10)
                    ->get();

        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $name = $name_services->lastname($result->lastname,$result->firstname,$result->middlename,$result->extname).'-'.$result->stud_id;
                $data[] = ['id' => $result->id, 'text' => $name];
            }
        }
        return response()->json($data);
    }
}