<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UsersSystems;
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
            $user = User::where('username',$request->username)->first();
            $token = Str::random(80);

            $user1 = User::find($user->id);
            $user1->api_token = Hash::make($token);
            $user1->save();

            $role = 0;

            $user_systems = UsersSystems::with('system')
                ->where('user_id',$user->id)
                ->whereIn('system_id',[1,6,7,9])
                ->get();
            $systems = [];
            if($user_systems->count()>0){
                foreach($user_systems as $row){
                    if (!in_array($row->system->shorten, $systems)) {
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
