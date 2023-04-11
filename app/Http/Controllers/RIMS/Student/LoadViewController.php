<?php

namespace App\Http\Controllers\RIMS\Student;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;

class LoadViewController extends Controller
{
    public function searchStudent(Request $request){
        $search = $request->input('search');
        $name_services = new NameServices;
        $results = Users::where(function($query) use ($search) {
                        $query->where('lastname', 'LIKE', "%$search%")
                            ->orWhere('firstname', 'LIKE', "%$search%")
                            ->orWhere('middlename', 'LIKE', "%$search%");
                    })
                    ->limit(10)
                    ->get();

        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $name = $name_services->lastname($result->lastname,$result->firstname,$result->middlename,$result->extname);
                $data[] = ['id' => $result->id, 'text' => $name];
            }
        }
        return response()->json($data);
    }
}