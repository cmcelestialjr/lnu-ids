<?php

namespace App\Http\Controllers\ADMS;
use App\Http\Controllers\Controller;
use App\Services\ValidateAccessServices;

class PageController extends Controller
{
    private $page;
    private $validate;
    public function __construct()
    {
        $this->page = 'adms';
        $this->validate = new ValidateAccessServices;
    }
    public function home($data){
        return view($this->page.'/home',$data);
    }
    public function students($data){
        return view($this->page.'/home',$data);
    }
    public function admit($data){
        return view($this->page.'/home',$data);
    }
    // public function grades($data){
    //     $level_ids = array(1,2,3); 
    //     $validate = $this->validate->check($data,$level_ids);
    //     if($validate=='success'){
    //         return view($this->page.'/home',$data);
    //     }else{
    //         return view('layouts/error/404');
    //     }
    // }
}
?>