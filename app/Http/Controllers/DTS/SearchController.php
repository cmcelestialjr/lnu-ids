<?php

namespace App\Http\Controllers\DTS;

use App\Http\Controllers\Controller;
use App\Models\DTSDocs;
use App\Models\Users;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDOException;

class SearchController extends Controller
{
    public function index(Request $request){

        $search = $request->search;

        $docs = DTSDocs::with(
                    'type',
                    'office',
                    'status',
                    'change_by_info',
                    'created_by_info',
                    'history.office',
                    'history.option',
                    'latest')
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

        $user = Auth::user();
        $user_id = $user->id;
        $user = Users::with('employee_default')->where('id',$user_id)->first();
        $user_office_id = $user->employee_default->office_id;

        $data = array(
            'doc' => $docs->first(),
            'user_office_id' => $user_office_id
        );
        return view('dts/searchSingle',$data);
    }

    public function paginate(Request $request)
    {
        $page = $request->page;
        $value = $request->value;

        $query = $this->paginateQuery($value);

        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        $totalQuery = $query['data']->get()->count();
        $data = $query['data']->skip($offset)->take($perPage)->get();
        $totalPages = (int) ceil($totalQuery / $perPage);
        $currentPageSet = ceil($page / 5);
        $startPage = ($currentPageSet - 1) * 5 + 1;
        $endPage = min($startPage + 4, $totalPages);

        $links = [];
        for ($i = $startPage; $i <= $endPage; $i++) {
            $links[] = ['page_number' => $i];
        }

        return response()->json([
            'links' => $links,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_query' => $totalQuery,
            'list' => $data,
            'offset' => $offset
        ]);
    }

    private function paginateQuery($value){

        $docs = DTSDocs::with('office','status','latest.office','latest.action_office','latest.option','history')
            ->where(function($subQuery) use ($value) {
                $subQuery->where('dts_id', 'like', '%'.$value.'%');
                $subQuery->orWhere('particulars', 'like', '%'.$value.'%');
                $subQuery->orWhere('description', 'like', '%'.$value.'%');
                $subQuery->orWhere('amount', 'like', '%'.$value.'%');
            })
            ->orderBy('particulars','ASC');
        $docs = $docs->orderBy('id','DESC');

        return ['data' => $docs];
    }
}
