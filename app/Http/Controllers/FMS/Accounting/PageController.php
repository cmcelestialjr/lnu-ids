<?php

namespace App\Http\Controllers\FMS\Accounting;
use App\Http\Controllers\Controller;
use App\Models\EducBranch;
use App\Models\EducProgramLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ValidateAccessServices;

class PageController extends Controller
{
    private $page;
    private $validate;
    public function __construct()
    {
        $this->page = 'fms/accounting';
        $this->validate = new ValidateAccessServices;
    }
    public function home_a($data){
        return view($this->page.'/home',$data);
    }
    public function fund($data){
        return view($this->page.'/fund',$data);
    }
    public function fees($data){
        $data['branch'] = EducBranch::get();
        $data['program_level'] = EducProgramLevel::get();
        return view($this->page.'/fees/fees',$data);
    }
}
?>