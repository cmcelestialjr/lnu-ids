<?php

namespace App\Http\Controllers\DTS;

use App\Http\Controllers\Controller;
use App\Models\DTSDocs;
use App\Models\DTSDocsHistory;
use App\Models\Office;
use App\Models\Users;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $insert = new DTSDocsHistory();
            $insert->doc_id = $id;
            $insert->office_id = $office_id;
            $insert->option_id = 2;
            $insert->remarks = $remarks;
            $insert->is_return = $is_return;
            $insert->action_office_id = $user_office_id;
            $insert->created_by = $user_id;
            $insert->updated_by = $user_id;
            $insert->save();

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
}
