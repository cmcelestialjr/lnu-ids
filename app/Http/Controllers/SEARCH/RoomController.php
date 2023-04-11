<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\EducRoom;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function room(Request $request){
        $search = $request->input('search');
        $results = EducRoom::where('name', 'LIKE', "%$search%")
                    ->orderBy('name')
                    ->limit(15)
                    ->get();
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $data[] = ['id' => $result->id, 'text' => $result->name];
            }
        }
        return response()->json($data);
    }
}