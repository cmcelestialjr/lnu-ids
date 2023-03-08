<?php

namespace App\Http\Controllers\USERS;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\EncryptServices;
class PageController extends Controller
{
    private $page;
    public function __construct()
    {
        $this->page = 'users';
    }
    public function home($data){

        return view($this->page.'/home',$data);
    }
    public function systems($data){

        return view($this->page.'/systems',$data);
    }
}
?>