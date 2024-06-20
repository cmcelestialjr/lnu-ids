<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid data'], 400);
        }

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user1 = User::with('systems.system')->where('username',$request->username)->first();
            $token = Str::random(80);

            $user = User::find($user1->id);
            $user->api_token = Hash::make($token);
            $user->save();

            $role = 0;

            $systems = [];

            if($user1->systems){
                foreach($user1->systems as $row){
                    if($row->system->shorten=='HRIMS' ||
                        $row->system->shorten=='RIMS' ||
                        $row->system->shorten=='FIS'
                    ){
                        $systems[] = $row->system->shorten;
                    }
                }
            }

            return response()->json([
                'username' => $user->username,
                'role' => $role,
                'id_no' => $user->id_no,
                'stud_id' => $user->stud_id,
                'lastname' => $user->lastname,
                'firstname' => $user->firstname,
                'middlename' => $user->middlename,
                'extname' => $user->extname,
                'token' => $token,
                'systems' => $systems
            ], 200);

        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
