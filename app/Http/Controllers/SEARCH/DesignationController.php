<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\HRDesignation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function list(Request $request){
        $search = $request->input('search');
        $results = HRDesignation::where('name', 'LIKE', "%$search%")
                    ->orderBy('name')
                    ->limit(15)
                    ->get();
        $data = [];
        if($results->count()>0){
            $data[] = ['id' => 'none', 'text' => 'None'];
            foreach ($results as $result) {
                $data[] = ['id' => $result->id, 'text' => $result->name];
            }
        }
        return response()->json($data);
    }
}