<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\Ludong\LudongStudents;
use Illuminate\Http\Request;

class LudongController extends Controller
{
    public function student(Request $request){
        $search = $request->input('search');
        $results = LudongStudents::where('stud_id', 'LIKE', "%$search%")
            ->orWhere('surname', 'LIKE', "%$search%")
            ->orWhere('first_name', 'LIKE', "%$search%")
            ->orderBy('stud_id')
            ->orderBy('surname')
            ->orderBy('first_name')
            ->limit(10)
            ->get();
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $data[] = ['id' => $result->stud_id, 'text' => $result->stud_id.'-'.$result->surname.', '.$result->first_name.' '.$result->middle_name];
            }
        }
        return response()->json($data);
    }
}