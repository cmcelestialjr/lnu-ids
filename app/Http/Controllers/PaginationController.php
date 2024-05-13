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
    public function index(Request $request)
    {
        $value = $request->value;

        $docs = $this->query($value);

        $docs = $docs->take(10)
                    ->get();

        return response()->json($docs);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $page = $request->page;
        $value = $request->value;

        $docs = $this->query($value);

        $docs = $docs->get();

        $perPage = 10;
        $totalDocs = $docs->count();
        $totalPages = ceil($totalDocs / $perPage);

        // Calculate the current set of pages
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
            'total_pages' => $totalPages
        ]);
    }

    private function query($value){
        $user = Auth::user();
        $user_id = $user->id;
        $user = Users::with('employee_default')->where('id',$user_id)->first();
        $office_id = $user->employee_default->office_id;

        $docs = DTSDocs::with('office','status')
            ->where(function($subQuery) use ($office_id) {
                $subQuery->where('office_id',$office_id);
                $subQuery->orWhereHas('history', function($subQuery) use ($office_id) {
                    $subQuery->where('office_id', $office_id);
                });
            });
        if($value!=''){
            $docs = $docs->where(function($subQuery) use ($value) {
                            $subQuery->where('dts_id', 'like', '%'.$value.'%');
                            $subQuery->orWhere('name', 'like', '%'.$value.'%');
                            $subQuery->orWhere('particulars', 'like', '%'.$value.'%');
                            $subQuery->orWhere('amount', 'like', '%'.$value.'%');
                        })
                        ->orderBy('name','ASC')
                        ->orderBy('particulars','ASC');
        }else{
            $docs = $docs->orderBy('created_by','DESC');
        }


        return $docs;
    }

}
