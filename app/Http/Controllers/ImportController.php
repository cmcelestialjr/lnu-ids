<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataImport;
class ImportController extends Controller
{
    public function import(Request $request){
        Excel::import(new DataImport, $request->file('file')->store('temp'));
        return back();
    }
}
?>