<?php

namespace App\Http\Controllers\DTS;
use App\Http\Controllers\Controller;
use App\Services\ValidateAccessServices;

class PageController extends Controller
{
    private $page;
    private $validate;
    public function __construct()
    {
        $this->page = 'dts';
        $this->validate = new ValidateAccessServices;
    }
    public function inbox($data){
        return view($this->page.'/inbox',$data);
    }
    public function receive($data){
        return view($this->page.'/receive',$data);
    }
}
?>
