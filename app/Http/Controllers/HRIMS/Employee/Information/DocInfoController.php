<?php

namespace App\Http\Controllers\HRIMS\Employee\Information;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_access_level = $request->session()->get('user_access_level');

        $data = array(
            'user_access_level' => $user_access_level
        );
        return view('hrims/employee/information/docInfo',$data);
    }
}
