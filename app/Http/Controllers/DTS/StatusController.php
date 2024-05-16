<?php

namespace App\Http\Controllers\DTS;

use App\Http\Controllers\Controller;
use App\Models\DTSDocs;
use App\Models\DTSStatus;
use App\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDOException;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->indexValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return view('layouts/error/404');
        }

        $dts_id = $request->dts_id;

        $doc = DTSDocs::with('latest')
            ->where('dts_id',$dts_id)->first();

        if($doc==NULL){
            return view('layouts/error/404');
        }

        $user = Auth::user();
        $user_id = $user->id;
        $user = Users::with('employee_default')->where('id',$user_id)->first();
        $user_office_id = $user->employee_default->office_id;

        $latest_office_id = '';
        if($doc->latest){
            $latest_office_id = $doc->latest->office_id;
        }

        if($doc->office_id!=$user_office_id && $latest_office_id!=$user_office_id){
            return view('layouts/error/404');
        }

        $statuses = DTSStatus::whereNotIn('id',[$doc->status_id])->get();

        $data = array(
            'doc' => $doc,
            'user_office_id' => $user_office_id,
            'statuses' => $statuses
        );
        return view('dts/statusModal',$data);
    }

    public function submit(Request $request)
    {
        // Validate the incoming request data
        $validator = $this->submitValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json(['result' => 'error']);
        }

        $dts_id = $request->dts_id;
        $status_id = $request->status_id;

        $doc = DTSDocs::with('latest','status')
            ->where('dts_id',$dts_id)->first();
        $status = DTSStatus::where('id',$status_id)->first();

        if($doc==NULL && $status==NULL){
            return  response()->json(['result' => 'error']);
        }

        $user = Auth::user();
        $user_id = $user->id;
        $user = Users::with('employee_default')->where('id',$user_id)->first();
        $user_office_id = $user->employee_default->office_id;

        $latest_office_id = '';
        if($doc->latest){
            $latest_office_id = $doc->latest->office_id;
        }

        if($doc->office_id!=$user_office_id && $latest_office_id!=$user_office_id){
            return  response()->json(['result' => 'error']);
        }

        try{

            DTSDocs::where('id', $doc->id)
                ->update([
                    'status_id' => $status_id,
                    'status_change_by' => $user_id,
                    'status_change_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $user_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

            $change_at = $status->name.' at: '.date('M d, Y h:i a');
            $change_by = $status->name.' by: '.$user->lastname.', '.$user->firstname;
            $date_from = Carbon::parse($doc->created_at);
            $date_to = Carbon::now();
            $diff = $date_to->diff($date_from);
            $days = $diff->days;
            $hours = $diff->h;
            $minutes = $diff->i;
            $duration = $this->getDuration($days.'-'.$hours.'-'.$minutes);
            return response()->json([
                'result' => 'success',
                'oldClass' => $doc->status->btn,
                'newClass' => $status->btn,
                'html' => $status->name,
                'change_at' => $change_at,
                'change_by' => $change_by,
                'duration' => $duration]);

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

    private function getDuration($dhm){
        if($dhm==''){
            return '';
        }
        $explode = explode('-',$dhm);
        $days = $explode[0];
        $hrs = $explode[1];
        $mins = $explode[2];

        if($days==1){
            $days_view = $days.' day ';
        }elseif($days>1){
            $days_view = $days.' days ';
        }else{
            $days_view = '';
        }

        if($hrs==1){
            $hrs_view = $hrs.' hr ';
        }elseif($hrs>1){
            $hrs_view = $hrs.' hrs ';
        }else{
            $hrs_view = '';
        }

        if($mins==1){
            $mins_view = $mins.' min ';
        }elseif($mins>1){
            $mins_view = $mins.' mins ';
        }else{
            $mins_view = '';
        }

        return $days_view.$hrs_view.$mins_view;
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
            'dts_id' => 'required|string',
        ];

        $customMessages = [
            'dts_id.required' => 'DTS No. is required',
            'dts_id.string' => 'DTS No. must be a string',
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
            'dts_id' => 'required|string',
            'status_id' => 'required|numeric',
        ];

        $customMessages = [
            'dts_id.required' => 'DTS No. is required',
            'dts_id.string' => 'DTS No. must be a string',
            'status_id.required' => 'Status is required',
            'status_id.numeric' => 'Status must be a number',
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
