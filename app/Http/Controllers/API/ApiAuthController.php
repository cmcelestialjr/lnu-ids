<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::where('username',$request->username)->first();
            $token = Str::random(80);

            $user = User::find($user->id);
            $user->api_token = hash('sha256', $token);
            $user->save();

            return response()->json([
                'username' => $user->username,
                'isAdmin' => 0,
                'id_no' => $user->id_no,
                'stud_id' => $user->stud_id,
                'lastname' => $user->lastname,
                'firstname' => $user->firstname,
                'middlename' => $user->middlename,
                'extname' => $user->extname,
                'token' => $token
            ], 200);

        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
