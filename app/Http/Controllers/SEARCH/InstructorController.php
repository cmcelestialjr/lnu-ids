<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use App\Models\Users;
use App\Models\UsersRole;
use App\Services\NameServices;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function instructor(Request $request){
        $name_services = new NameServices;
        $search = $request->input('search');
        $results = Users::whereHas('user_role', function($query){
                        $query->where('role_id', 3);
                    })
                    ->where('lastname', 'LIKE', "%$search%")
                    ->orWhere('firstname', 'LIKE', "%$search%")
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

