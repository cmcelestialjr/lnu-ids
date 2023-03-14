<?php

namespace App\Http\Controllers\SIMS;
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
        $this->page = 'sims';
        $this->validate = new ValidateAccessServices;
    }
    public function home($data){
        return view($this->page.'/home',$data);
    }
    public function information($data){        
        return view($this->page.'/information',$data);
    }
    public function teachers($data){
        return view($this->page.'/home',$data);
    }
    public function info($data){
        return view($this->page.'/home',$data);
    }
    public function grades($data){
        return view($this->page.'/home',$data);
    }
    public function schedule($data){
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