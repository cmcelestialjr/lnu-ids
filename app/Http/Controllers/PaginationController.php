<?php

namespace App\Http\Controllers;

use App\Models\DTSDocs;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaginationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $page = $request->page;
    //     $value = $request->value;

    //     $query = $this->query($value);

    //     $perPage = 15;
    //     $offset = ($page - 1) * $perPage;
    //     $totalQuery = $query['data']->get()->count();
    //     $data = $query['data']->skip($offset)->take($perPage)->get();
    //     $totalPages = (int) ceil($totalQuery / $perPage);
    //     $currentPageSet = ceil($page / 5);
    //     $startPage = ($currentPageSet - 1) * 5 + 1;
    //     $endPage = min($startPage + 4, $totalPages);

    //     $links = [];
    //     for ($i = $startPage; $i <= $endPage; $i++) {
    //         $links[] = ['page_number' => $i];
    //     }

    //     return response()->json([
    //         'links' => $links,
    //         'current_page' => $page,
    //         'total_pages' => $totalPages,
    //         'total_query' => $totalQuery,
    //         'list' => $data,
    //         'offset' => $offset,
    //         'office_id' => $query['office_id']
    //     ]);
    // }

    // private function query($value){
    //     $user = Auth::user();
    //     $user_id = $user->id;
    //     $user = Users::with('employee_default')->where('id',$user_id)->first();
    //     $office_id = $user->employee_default->office_id;

    //     $docs = DTSDocs::with('office','status','latest.office','latest.action_office','latest.option','history')
    //         ->where(function($subQuery) use ($office_id) {
    //             $subQuery->where('office_id',$office_id);
    //             $subQuery->orWhereHas('history', function($subQuery) use ($office_id) {
    //                 $subQuery->where('office_id', $office_id);
    //             });
    //         });
    //     if($value!=''){
    //         $docs = $docs->where(function($subQuery) use ($value) {
    //                         $subQuery->where('dts_id', 'like', '%'.$value.'%');
    //                         $subQuery->orWhere('particulars', 'like', '%'.$value.'%');
    //                         $subQuery->orWhere('description', 'like', '%'.$value.'%');
    //                         $subQuery->orWhere('amount', 'like', '%'.$value.'%');
    //                     })
    //                     ->orderBy('particulars','ASC');
    //     }else{
    //         $docs = $docs->orderBy('updated_history','DESC');
    //     }
    //     $docs = $docs->orderBy('id','DESC');

    //     return ['office_id' => $office_id, 'data' => $docs];
    // }

}
