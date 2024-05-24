<?php

namespace App\Http\Controllers\DTS;

use App\Http\Controllers\Controller;
use App\Models\DTSDocs;
use App\Models\DTSDocsHistory;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDOException;

class ReceiveController extends Controller
{
    public function index(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $user_id = $user->id;
        $user = Users::with('employee_default')->where('id',$user_id)->first();
        $office_id = $user->employee_default->office_id;
        $id = $request->id;

        $check = DTSDocs::with('latest')->where('id',$id)->first();
        if($check==NULL){
            return  response()->json(['result' => 'error']);
        }

        try{
            $date_from = Carbon::parse($check->latest->created_at);
            $date_to = Carbon::now();

            $diff = $date_to->diff($date_from);

            $days = $diff->days;
            $hours = $diff->h;
            $minutes = $diff->i;

            DTSDocsHistory::where('id', $check->latest->id)
                    ->update([
                        'action' => 1
                    ]);

            $insert = new DTSDocsHistory();
            $insert->doc_id = $id;
            $insert->office_id = $office_id;
            $insert->option_id = 1;
            $insert->is_return = 'N';
            $insert->action_office_id = $check->office_id;
            $insert->dhm = $days.'-'.$hours.'-'.$minutes;
            $insert->created_by = $user_id;
            $insert->updated_by = $user_id;
            $insert->save();

            DTSDocs::where('id', $id)
                    ->update([
                        'updated_history' => $insert->created_at
                    ]);

            $doc = DTSDocsHistory::with(
                        'office',
                        'action_office',
                        'option')
                ->where('id',$insert->id)
                ->first();
            $option = ' ('.$doc->option->name.' to) ';
            if($doc->option_id==1){
                $option = ' ('.$doc->option->name.' by) ';
            }
            $dateTime = date('M d, Y h:i a',strtotime($doc->created_at));
            $latest_action = $doc->action_office->shorten.$option.$doc->office->shorten.'<br>'.$dateTime;
            $data_response = array('result' => 'success',
                                   'latest_action' => $latest_action);
            return response()->json($data_response);
        } catch (QueryException $e) {
            // Handle database query exceptions
            return $this->handleDatabaseError($e);
        } catch (PDOException $e) {
            // Handle PDO exceptions
            return $this->handleDatabaseError($e);
        } catch (Exception $e) {
            // Handle other exceptions
            return $this->handleOtherError($e);
        }

    }
    public function receiveTab()
    {
        return view('dts/receivePagination');
    }
    public function receivedTab()
    {
        return view('dts/receivedPagination');
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
            'perPage' => $perPage,
            'total_pages' => $totalPages,
            'total_query' => $totalQuery,
            'list' => $data,
            'offset' => $offset,
            'office_id' => $query['office_id']
        ]);
    }

    private function paginateQuery($value)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $user = Users::with('employee_default')->where('id',$user_id)->first();
        $office_id = $user->employee_default->office_id;

        $docs = DTSDocs::with('office','status','latest.office','latest.action_office','latest.option','history')
            ->where('status_id',1)
            ->whereHas('history', function($subQuery) use ($office_id) {
                $subQuery->where('office_id', $office_id);
                $subQuery->where('option_id', 2);
                $subQuery->where('action', NULL);
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

    public function paginate1(Request $request)
    {
        $page = $request->page;
        $value = $request->value;

        $query = $this->paginateQuery1($value);

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

    private function paginateQuery1($value)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $user = Users::with('employee_default')->where('id',$user_id)->first();
        $office_id = $user->employee_default->office_id;

        $docs = DTSDocs::with('office','status','latest.office','latest.action_office','latest.option','history')
            ->whereHas('history', function($subQuery) use ($office_id) {
                $subQuery->where('office_id', $office_id);
                $subQuery->where('option_id', 1);
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
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function indexValidateRequest($request)
    {
        $rules = [
            'id' => 'required|numeric',
        ];

        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
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
