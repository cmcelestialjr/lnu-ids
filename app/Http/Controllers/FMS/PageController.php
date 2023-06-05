<?php

namespace App\Http\Controllers\FMS;
use App\Http\Controllers\Controller;
use App\Services\ValidateAccessServices;

class PageController extends Controller
{
    private $page;
    private $validate;
    public function __construct()
    {
        $this->page = 'fms';
        $this->validate = new ValidateAccessServices;
    }
    public function home_fms($data){
        return view($this->page.'/home',$data);
    }
}
?>