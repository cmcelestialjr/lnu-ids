<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::where('username',$request->username)->first();
            $tokenResult = $user->createToken('authToken');
            $token = $tokenResult->accessToken->plainTextToken;

            return response()->json([
                'username' => $user->username,
                'isAdmin' => 0,
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
