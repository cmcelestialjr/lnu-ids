<?php

namespace App\Http\Controllers\FMS\Budget;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ValidateAccessServices;

class PageController extends Controller
{
    private $page;
    private $validate;
    public function __construct()
    {
        $this->page = 'fms/budget';
        $this->validate = new ValidateAccessServices;
    }
    public function home_b($data){
        return view($this->page.'/home',$data);
    }
}
?>