<?php

namespace App\Http\Controllers\USERS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\EncryptServices;
class UserController extends Controller
{
    public function user($data){
        return view('users/list',$data);
    }
}
?>