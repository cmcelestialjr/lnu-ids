<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstructorController extends Controller
{
    public function instructor(Request $request){
        $name_services = new NameServices;
        $search = $request->input('search');
        $results = Users::where('id_no','!=',NULL)
                    ->where(function ($query) use ($search) {
                        $query->where(DB::raw("CONCAT(lastname,', ',firstname)"), 'LIKE', "%$search%");
                        $query->orWhere('id_no', 'LIKE', "%$search%");
                    }) 
                    ->whereHas('user_role', function($query){
                        $query->where('role_id', 3);
                    })
                    ->orderBy('lastname')
                    ->orderBy('firstname')
                    ->limit(15)
                    ->get();
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $instructor = $name_services->lastname($result->lastname,$result->firstname,$result->middlename,$result->extname);
                $data[] = ['id' => $result->id, 'text' => $instructor];
            }
        }
        return response()->json($data);
    }
}

