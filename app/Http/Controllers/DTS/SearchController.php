<?php

namespace App\Http\Controllers\DTS;

use App\Http\Controllers\Controller;
use App\Models\DTSDocs;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDOException;

class SearchController extends Controller
{
    public function search(Request $request){
        $search = $request->search;

        if($search==''){
            return view('dts/searchNone');
        }

        $docs = DTSDocs::with('office','status','history')
            ->where(function($subQuery) use ($search) {
                $subQuery->where('dts_id', 'like', '%'.$search.'%');
                $subQuery->orWhere('particulars', 'like', '%'.$search.'%');
                $subQuery->orWhere('description', 'like', '%'.$search.'%');
                $subQuery->orWhere('amount', 'like', '%'.$search.'%');
            })->get();

        if($docs->count()<=0){
            return view('dts/searchNone');
        }

        if($docs->count()>1){
            $data = array(
                'doc' => $docs
            );
            return view('dts/searchMultiple',$data);
        }

        $data = array(
            'doc' => $docs->first()
        );
        return view('dts/searchSingle',$data);

    }
}
