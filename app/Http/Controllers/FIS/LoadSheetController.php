<?php

namespace App\Http\Controllers\FIS;

use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedSchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class LoadSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {        
        $result = 'error';
        $src = asset('assets\pdf\pdf_error.pdf');

        $response_data = array('result' => $result,
                          'src' => $src);

        // Validate the incoming request data
        $validator = $this->validateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            return  response()->json($response_data);
        }

        $school_year = $request->school_year;

        $check = EducOfferedSchoolYear::with('grade_period')->where('id',$school_year)->first();

        if($check==NULL){
            return  response()->json($response_data);
        }

        $src = $this->qr($check);
        
        $response_data = array('result' => 'success',
                          'src' => $src);
        return response()->json($response_data);
    }

    private function qr($school_year){
        error_reporting(E_ERROR);
        $user = Auth::user();
        $id_no = $user->id_no;
        $image = QrCode::format('png')
                    ->merge(public_path('assets\images\logo\lnu_logo.png'), .28, true)
                    ->style('round', 0.2)
                    //->eye('circle')
                    ->eyeColor(1, /*outer*/ 0, 0, 128, /*inner*/ 212,175,55, 0, 0)
                    ->eyeColor(2, /*outer*/ 212,175,55, /*inner*/ 0, 0, 128, 0, 0)
                    ->size(300)
                    ->errorCorrection('H')
                    ->generate($id_no.'_'.$school_year->year_from.'-'.$school_year->year_to.' ('.$school_year->grade_period->name.')');
        $imageName = $school_year->id.'.png';
        $path = 'storage\fis\loadsheet/'.$id_no.'/';
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
        $file = public_path($path . $imageName);
        file_put_contents($file, $image);
        $qrcode = $path.$imageName;
        $src = $this->pdf($school_year,$user);
        return $src;
    }

    private function pdf($school_year,$user){
        $school_year_id = $school_year->id;
        $year_from = $school_year->year_from;
        $year_to = $school_year->year_to;
        $semester_no = $school_year->grade_period->no;
        $courses_list = EducOfferedCourses::where('instructor_id',$user->id)
            ->whereHas('curriculum.offered_program', function ($query) use ($school_year_id) {
                $query->where('school_year_id',$school_year_id);
            })->get();

        //$pdf = new PDF('A4', 'mm', '', true, 'UTF-8', false);
        $page_size = array(215.9, 330.2);
        $pdf = new Pdf('P', 'mm', $page_size, true, 'UTF-8', false);
        $height = 185;
        $width = 260;
        $pdf::reset();
        $pdf::AddPage('P',$page_size);
        $pdf::SetAutoPageBreak(TRUE, 3);
        //landscape scale A4
        //$height = 185;
        //$width = 260;
        //Portrait scale A4
        //$width = 210;
        //height = 270;

        $pdf::setFooterCallback(function($pdf){
            $pdf->SetFont('arial','',6);
            $pdf->SetXY(5,-5);
            $pdf->Cell(320, '', $pdf->getAliasNumPage().' of '.$pdf->getAliasNbPages(), 0, 1, 'C', 0, '', 1);
            $pdf->SetXY(5,-5);
            $pdf->Cell(320, '', date('M d, Y h:i a'), 0, 1, 'R', 0, '', 1);
        });

        $x = 5;
        $y = 7;
        $x_add = 0;
        $y_add = 0;

        $pdf::SetXY($x, $y);
        $pdf::SetFont('arial','',9);
        $pdf::Cell(205, '', 'LEYTE NORMAL UNIVERSITY', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+4;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(205, '', 'Teaching Load Sheet', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+4;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(45, '', 'School Year  :  '.$year_from.'-'.$year_to, 0, 1, 'L', 0, '', 1);

        $x_add = $x_add+45;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(30, '', 'Semester  :  '.$semester_no, 0, 1, 'L', 0, '', 1);

        $x_add = $x_add+30;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(30, '', 'Unit/Department  :  ', 0, 1, 'L', 0, '', 1);

        $src = 'storage\fis\loadsheet/'.$user->id_no.'.pdf';
        $pdf::Output(public_path($src),'F');

        return asset($src);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function validateRequest(Request $request)
    {
        $rules = [
            'school_year' => 'required|numeric',
        ];
        
        $customMessages = [
            'school_year.required' => 'School Year is required',
            'school_year.numeric' => 'School Year must be a number',
        ];

        // Sanitize the 'search' parameter by removing HTML tags and trimming whitespace
        $sanitizedData = $request->all();

        if (isset($sanitizedData['school_year'])) {
            $sanitizedData['school_year'] = strip_tags(trim($sanitizedData['school_year']));
        }
        
        return Validator::make($sanitizedData, $rules, $customMessages);
    }
}
