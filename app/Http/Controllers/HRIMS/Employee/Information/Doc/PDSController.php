<?php

namespace App\Http\Controllers\HRIMS\Employee\Information\Doc;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;

class PDSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_access_level = $request->session()->get('user_access_level');

        $id = $request->id;

        $user = Users::find($id);


        if(!$user){
            return view('layouts/error/404');
        }

        $doc = 'assets/pdf/pdf_error.pdf';

        $data = array(
            'doc' => $doc,
            'user_access_level' => $user_access_level
        );
        return view('hrims/employee/information/doc/pds',$data);
    }
}
