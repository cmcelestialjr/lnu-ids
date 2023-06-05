<?php

namespace App\Http\Controllers\SEARCH;
use App\Http\Controllers\Controller;
use App\Models\HRPosition;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function list(Request $request){
        $search = $request->input('search');
        $results = HRPosition::where('name', 'LIKE', "%$search%")
                    ->orWhere('item_no', 'LIKE', "%$search%")
                    ->orWhere('shorten', 'LIKE', "%$search%")
                    ->orderBy('name')
                    ->limit(15)
                    ->get();
        $data = [];
        if($results->count()>0){
            foreach ($results as $result) {
                $data[] = ['id' => $result->id, 'text' => $result->item_no.'-'.$result->name.' ('.$result->shorten.')'];
            }
        }
        return response()->json($data);
    }
}