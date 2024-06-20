<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Users;
use App\Models\UsersDTR;
use Illuminate\Support\Facades\Validator;

class ApiDtrController extends Controller
{
    public function fetchDtr(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'token' => 'required|string',
            'year' => 'required|integer',
            'month' => 'required|integer',
            'option' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid data'], 400);
        }

        $user = Users::where('username',$request->username)->first();

        if(!$user){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $api_token = $user->api_token;

       // if (Hash::check($request->token, $api_token)) {

            $option = $request->option;

            if ($option == 'Today') {
                $dtrData = UsersDTR::where('user_id', $user->id)
                    ->whereDate('date', today())
                    ->get();
            } else {
                $year = $request->year;
                $month = date('m',strtotime($year.'-'.$request->month.'-01'));
                $dtrData = UsersDTR::where('id_no', $user->id_no)
                    ->whereYear('date', $year)
                    ->whereMonth('date', $month)
                    ->get();
            }

            $responseData = $dtrData->map(function ($dtr) {
                return [
                    'date' => $dtr->date,
                    'time_in_am' => strtotime($dtr->time_in_am) ? date('H:i:s', strtotime($dtr->time_in_am)) : '',
                    'time_out_am' => strtotime($dtr->time_out_am) ? date('H:i:s', strtotime($dtr->time_out_am)) : '',
                    'time_in_pm' => strtotime($dtr->time_in_pm) ? date('H:i:s', strtotime($dtr->time_in_pm)) : '',
                    'time_out_pm' => strtotime($dtr->time_out_pm) ? date('H:i:s', strtotime($dtr->time_out_pm)) : '',
                ];
            });

            return response()->json($responseData, 200);

        // }else{
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
    }
}
