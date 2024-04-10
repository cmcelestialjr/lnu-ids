<?php

namespace App\Http\Controllers\PDF;
use App\Http\Controllers\Controller;
use App\Models\HRPayroll;
use App\Services\CodeServices;
use App\Services\NameServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PDFController extends Controller
{
    public function view(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $user = Auth::user();
        $id_no = $user->id_no;
        
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $data = array(
                'pdf_option' => $request->pdf_option
            );
            
                return view('pdf/pdf',$data);
                
        }else{
            return view('layouts/error/404');
        }
    }
    public function src(Request $request){
        $result = 'error';
        $src = '';
        $pdf_option = $request->pdf_option;
    
        $exp = explode(': ',$pdf_option);
        $result = 'error';
        $src = '';
        
        $response = array('result' => $result,
                          'src' => $src);
                          
        if(isset($exp[1]) && isset($exp[2])){
            $id = str_replace(' Code','',$exp[1]);
            $code = $exp[2];
            $code_services = new CodeServices;
            $decode = $code_services->decode($code,$id);            
            if($decode=='success'){
                $controller = 'controller_'.$exp[2][16];
                $response = $this->$controller($exp[0],$pdf_option);
            }
        }                          
        return response()->json($response);
    }
    private function controller_1($controller,$pdf_option){
        if($controller=='Payroll'){
            return app('App\Http\Controllers\HRIMS\Payroll\PayrollPrintController')->src($pdf_option);
        }
        return array('result' => 'error',
                          'src' => '');     
    }
}