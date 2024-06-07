<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\DTRImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        // Import data using DataImport class
        //Excel::import(new DataImport, $request->file('file')->store('temp'));
        Excel::import(new DTRImport, $request->file('file')->store('temp'));
        return back();
    }

}
?>
