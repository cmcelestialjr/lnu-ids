<?php

namespace App\Http\Controllers\DTS;

use App\Http\Controllers\Controller;
use App\Models\DTSDocs;
use App\Models\DTSDocsHistory;
use App\Models\DTSStatus;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDOException;

class InboxController extends Controller
{
    public function count()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $user = Users::with('employee_default')->where('id',$user_id)->first();
        $office_id = $user->employee_default->office_id;

        $total_docs_count = DTSDocsHistory::where(function($subQuery) use ($office_id) {
                $subQuery->where('office_id',$office_id)
                    ->where('option_id',1);
            })->orWhere(function($subQuery) use ($office_id) {
                $subQuery->where('action_office_id',$office_id)
                    ->where('option_id',2);
            })
            ->get()->count();

        $received_docs_count = DTSDocsHistory::where('office_id',$office_id)
            ->where('option_id',1)
            ->get()->count();

        $forwarded_docs_count = DTSDocsHistory::where('action_office_id',$office_id)
            ->where('option_id',2)
            ->where('is_return','N')
            ->get()->count();

        $returned_docs_count = DTSDocsHistory::where('action_office_id',$office_id)
            ->where('option_id',2)
            ->where('is_return','Y')
            ->get()->count();

        return response()->json([
            'total_docs_count' => $total_docs_count,
            'received_docs_count' => $received_docs_count,
            'forwarded_docs_count' => $forwarded_docs_count,
            'returned_docs_count' => $returned_docs_count
        ]);
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
            'offset' => $offset,
            'office_id' => $query['office_id']
        ]);
    }

    private function paginateQuery($value){
        $user = Auth::user();
        $user_id = $user->id;
        $user = Users::with('employee_default')->where('id',$user_id)->first();
        $office_id = $user->employee_default->office_id;

        $docs = DTSDocs::with('office','status','latest.office','latest.action_office','latest.option','history')
            ->where(function($subQuery) use ($office_id) {
                $subQuery->where('office_id',$office_id);
                $subQuery->orWhereHas('history', function($subQuery) use ($office_id) {
                    $subQuery->where('office_id', $office_id);
                });
            });
        if($value!=''){
            $docs = $docs->where(function($subQuery) use ($value) {
                            $subQuery->where('dts_id', 'like', '%'.$value.'%');
                            $subQuery->orWhere('particulars', 'like', '%'.$value.'%');
                            $subQuery->orWhere('description', 'like', '%'.$value.'%');
                            $subQuery->orWhere('amount', 'like', '%'.$value.'%');
                        })
                        ->orderBy('particulars','ASC');
        }else{
            $docs = $docs->orderBy('updated_history','DESC');
        }
        $docs = $docs->orderBy('id','DESC');

        return ['office_id' => $office_id, 'data' => $docs];
    }

     /**
     * Handle database errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleDatabaseError($e)
    {
        return response()->json(['result' => $e->getMessage()], 400);
    }

    /**
     * Handle other errors during the transaction.
     *
     * @param Exception $e The exception object.
     * @return \Illuminate\Http\JsonResponse The JSON response with error details.
     */
    private function handleOtherError($e)
    {
        return response()->json(['result' => $e->getMessage()], 500);
    }
}
