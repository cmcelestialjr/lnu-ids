<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function check(Request $request){
        $result = 'Wrong Username or Password';
        // Validate input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        //$role = $request->role;
        $username = $request->username;
        $password = $request->password;
        $page = '';
        $user = User::where('username',$username)->first();
        if ($user) {
            if(Hash::check($password, $user->password)){
                //$encrypt = Crypt::encryptString($token(4).hash('123').$token(4));
                if($user->status_id=='1'){
                    Auth::login($user);
                    if($user->update_password==NULL){
                        $page = 'change_password';
                    }else{
                        $page = 'system';
                    }
                    $result = 'success';
                }elseif($user->status_id=='3'){
                    $result = 'On-hold';
                }else{
                    $result = 'Inactive';
                }
            }

        }
        $response = array('result' => $result, 'page' => $page);
        return response()->json($response);
    }
    private function checkPassword($user,$password){
        $decrypt = Crypt::decryptString($user->password);
        $remove_first = substr($decrypt, 4);
        $hash_password = substr($remove_first, 0, -4);
        $isValid = Hash::check($password, $hash_password);
        return $isValid;
    }
    public function logout()
    {
        Auth::logout();
        Session::flush();
        Session::flash('toastr', array('warning', 'You are now logout!.'));
        return redirect()->route('indexpage');
    }
}

?>
