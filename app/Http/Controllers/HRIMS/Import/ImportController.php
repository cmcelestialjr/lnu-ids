<?php

namespace App\Http\Controllers\HRIMS\Import;

use App\Http\Controllers\Controller;
use App\Imports\EmployeeImport;
use App\Imports\PositionImport;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function index(Request $request)
    {
        $option = $request->option;
        $check_options = $this->checkOptions($option);

        if($check_options==0){
            return response()->json(['result' => 'error']);
        }

        if (!$request->hasFile('files')){
            return response()->json(['result' => 'Please upload excel file.']);
        }

        $file = $request->file('files');
        $file_extension = strtolower($file->extension());
        $check_extension = $this->checkExtension($file_extension);

        if($check_extension==0){
            return response()->json(['result' => 'Invalid file format.']);
        }

        $new_import = '';
        if($option=='employee'){
            $new_import = new EmployeeImport;
        }elseif($option=='position'){
            $new_import = new PositionImport;
        }

        if($new_import==''){
            return response()->json(['result' => 'error']);
        }

        Excel::import($new_import, $request->file('files')->store('temp'));

        return response()->json(['result' => 'success']);
    }
    private function checkOptions($option)
    {
        $options = ['employee','position'];
        if (in_array($option, $options)) {
            return 1;
        }
        return 0;
    }
    private function checkExtension($file_extension)
    {
        $fextensions = ['xlsx','xls'];
        if (in_array($file_extension, $fextensions)) {
            return 1;
        }
        return 0;
    }
}
