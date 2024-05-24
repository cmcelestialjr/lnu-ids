<?php

namespace App\Http\Controllers\DTS;

use App\Http\Controllers\Controller;
use App\Models\DTSDocs;
use App\Models\DTSDocsHistory;
use App\Models\Office;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDOException;

class ForwardController extends Controller
{
    public function index(Request $request){
        // Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $id = $request->id;
        $id_name = $request->id_name;

        $check = DTSDocs::with('latest')->where('id',$id)->first();
        if($check==NULL){
            return view('layouts/error/404');
        }

        $user = Auth::user();
        $user_id = $user->id;
        $user = Users::with('employee_default')->where('id',$user_id)->first();
        $office_id = $user->employee_default->office_id;

        $offices = Office::whereNotIn('id',[$office_id])->get();

        $data = array(
            'doc' => $check,
            'offices' => $offices,
            'id_name' => $id_name
        );
        return view('dts/forwardModal',$data);

    }

    public function submit(Request $request){
        $data_response = array('result' => 'error');

        // Validate the incoming request data
        $validator = $this->submitValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($data_response);
        }

        $user = Auth::user();
        $user_id = $user->id;
        $user = Users::with('employee_default')->where('id',$user_id)->first();
        $user_office_id = $user->employee_default->office_id;

        $id = $request->id;
        $office_id = $request->office_id;
        $remarks = $request->remarks;
        $is_return = $request->is_return;

        $check = DTSDocs::with('latest')->where('id',$id)->first();
        if($check==NULL){
            return  response()->json($data_response);
        }

        try{
            if($check->latest->option_id==2 && $check->latest->action_office_id==$user_office_id){
                $delete = DTSDocsHistory::where('option_id', 2)
                    ->where('doc_id', $id)
                    ->where('action_office_id', $user_office_id)
                    ->delete();
                $auto_increment = DB::update("ALTER TABLE dts_docs_history AUTO_INCREMENT = 1;");
                $check = DTSDocs::with('latest')->where('id',$id)->first();
            }

            $date_from = Carbon::parse($check->created_at);
            if($check->latest){
                $date_from = Carbon::parse($check->latest->created_at);

                DTSDocsHistory::where('id', $check->latest->id)
                    ->update([
                        'action' => 1
                    ]);
            }
            $date_to = Carbon::now();

            $diff = $date_to->diff($date_from);

            $days = $diff->days;
            $hours = $diff->h;
            $minutes = $diff->i;

            $insert = new DTSDocsHistory();
            $insert->doc_id = $id;
            $insert->office_id = $office_id;
            $insert->option_id = 2;
            $insert->remarks = $remarks;
            $insert->is_return = $is_return;
            $insert->action_office_id = $user_office_id;
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
    public function forwardTab()
    {
        return view('dts/forwardPagination');
    }
    public function forwardedTab()
    {
        return view('dts/forwardedPagination');
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
                $subQuery->where('option_id', 1);
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
        $select = $request->select;

        $query = $this->paginateQuery1($value,$select);

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

    private function paginateQuery1($value,$select)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $user = Users::with('employee_default')->where('id',$user_id)->first();
        $office_id = $user->employee_default->office_id;

        $docs = DTSDocs::with('office','status','latest.office','latest.action_office','latest.option','history')
            ->whereHas('history', function($subQuery) use ($office_id,$select) {
                $subQuery->where('action_office_id', $office_id);
                $subQuery->where('option_id', 2);
                if($select=='forwarded'){
                    $subQuery->where('is_return', 'N');
                }elseif($select=='returned'){
                    $subQuery->where('is_return', 'Y');
                }
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
            'id_name' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
            'id_name.required' => 'ID Name is required',
            'id_name.string' => 'ID Name must be a string',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function submitValidateRequest($request)
    {
        $rules = [
            'id' => 'required|numeric',
            'office_id' => 'required|string',
            'remarks' => 'nullable|string',
            'is_return' => 'required|string|in:Y,N'
        ];

        $customMessages = [
            'id.required' => 'ID is required',
            'id.numeric' => 'ID must be a number',
            'office_id.required' => 'ID is required',
            'office_id.numeric' => 'ID must be a number',
            'remarks.string' => 'ID Name must be a string',
            'is_return.required' => 'Is Return is required',
            'is_return.in' => 'Is Return must be Y or N only',
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
