<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function employee(Request $request){
        $name_services = new NameServices;
        $search = $request->input('search');
        $roleFilter = function ($query) {
            $query->whereIn('role_id', [2,3]);
        };
       // with(['user_role' => $roleFilter])->whereHas('user_role', $roleFilter)
        $results = Users::where('id_no','!=',NULL)
                    ->where(function ($query) use ($search) {
                        $query->where(DB::raw("CONCAT(lastname,', ',firstname)"), 'LIKE', "%$search%");
                        $query->orWhere('id_no', 'LIKE', "%$search%");
                    })                    
                    ->orderBy('lastname')
                    ->orderBy('firstname')
                    ->limit(10)
                    ->get();
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $employee = $name_services->lastname($result->lastname,$result->firstname,$result->middlename,$result->extname);
                $data[] = ['id' => $result->id, 'text' => $employee.'-'.$result->id_no];
            }
        }
        return response()->json($data);
    }
}

