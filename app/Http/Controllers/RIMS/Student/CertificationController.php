<?php

namespace App\Http\Controllers\RIMS\Student;

use App\Http\Controllers\Controller;
use App\Models\EducGradePeriod;
use App\Models\EducProgramLevel;
use App\Models\EducYearLevel;
use App\Models\StudentsCourses;
use App\Models\StudentsDocuments;
use App\Models\StudentsDocumentsList;
use App\Models\Users;
use App\Services\NameServices;
use App\Services\PasswordServices;
use App\Services\TokenServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class CertificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = $request->id;
        $student = Users::where('id',$id)
            ->where('stud_id','!=',NULL)
            ->first();

        // Check if the student exists
        if($student==NULL){
            return response()->json(['result' => 'error']);
        }
        
        $program_level = EducProgramLevel::whereHas('students_courses', function ($subQuery) use ($id) {
                $subQuery->where('user_id', $id);
            })->get();

        $data = array(
            'student' => $student,
            'program_level' => $program_level
        );
        return view('rims/student/certificationModal',$data);
    }

    /**
     * Show the form for viewing a resource.
     */
    public function pdf(Request $request){            
        $stud_id = $request->stud_id;
        $certification = $request->certification;
        $program_level = $request->program_level;
        $school_year = $request->school_year;
        $period = $request->period;
        $date = $request->date;
        $pdf_code = $request->pdf_code;
        
        $first_remove = substr($pdf_code, 4);
        $second_remove = substr($first_remove, 0, -4);
        $new_pdf_code = $second_remove;
        
        $student = Users::where('stud_id',$stud_id)->first();
        if($student==NULL || $new_pdf_code!=mb_substr($date, -1)){
            return view('layouts/error/404');
        }
        
        $src = 'storage\rims\students/'.$stud_id.'\certification/'.$certification.'_'.$program_level.'_'.$school_year.'_'.$period.'_'.$date.'_'.$pdf_code.'.pdf';

        $data = array(
                'src' => $src
            );
        return view('pdf/main_view',$data);
    }

    /**
     * Display a listing of the resource.
     */
    public function display(Request $request)
    {
        $src = $request->src;
        if($src=='error'){
            $src = 'assets\pdf\pdf_error.pdf';
        }else{
            $src = 'storage\rims\students/'.$src.'.pdf';
        }        
        $data = array(
            'src' => $src
        );
        return view('rims/student/certificationDisplay',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function showSYperiod(Request $request)
    {
        $id = $request->id;
        $student = Users::where('id',$id)
            ->where('stud_id','!=',NULL)
            ->first();

        // Check if the student exists
        if($student==NULL){
            return response()->json(['result' => 'error']);
        }

        $program_level_id = $request->program_level;
        $program_level = EducProgramLevel::where('id',$program_level_id)
            ->first();
        if($program_level==NULL){
            return response()->json(['result' => 'error']);
        }

        $school_years = [];
        $years = StudentsCourses::select('year_from')
            ->where('program_level_id',$program_level_id)
            ->where('user_id',$id)
            ->groupBy('year_from')
            ->orderBy('year_from','DESC')
            ->get();
        if($years->count()>0){
            foreach($years as $year){
                $school_years[] = $year->year_from.'-'.$year->year_from+1;
            }
        }

        $period = EducGradePeriod::where('period',$program_level->period)
            ->get();

        return response()->json(['result' => 'success',
                                 'school_years' => $school_years,
                                 'period' => $period
                                ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // Validate the request
        $validator = $this->showValidateRequest($request);

        // Check if validation fails
        if ($validator->fails()) {
            // If validation fails, return a JSON response with validation errors and a 400 status code
            return response()->json(['result' => $validator->errors()], 400);
        }

        $id = $request->id;
        $student = Users::where('id',$id)
            ->where('stud_id','!=',NULL)
            ->first();

        // Check if the student exists
        if($student==NULL){
            return response()->json(['result' => 'error']);
        }

        $certification = $request->certification;

        if($certification=='scholasticReport'){
            // Validate the request
            $validator = $this->scholasticReportValidateRequest($request);

            // Check if validation fails
            if ($validator->fails()) {
                // If validation fails, return a JSON response with validation errors and a 400 status code
                return response()->json(['result' => $validator->errors()], 400);
            }

            $url = $this->generateStudentDocuments($request);

            return response()->json(['result' => 'success',
                                'url' => $url
                                ]);
        }
    }

    private function generateStudentDocuments($request){
        $user = Auth::user();
        $name_services = new NameServices;
        $name_user = mb_strtoupper($name_services->lastname($user->lastname,$user->firstname,$user->middlename,$user->extname));

        $url = 'error';
        $id = $request->id;
        $certification = $request->certification;

        $document_id = StudentsDocuments::where('name2',$certification)->first('id');

        if($document_id == NULL){
            return $url;
        }      

        if($certification=='scholasticReport'){
            $program_level = $request->program_level;
            $school_year = $request->school_year;
            $period = $request->period;
            $exp_school_year = explode('-',$school_year);
            $year_from = $exp_school_year[0];
            $year_to = $exp_school_year[1];

            $insert = new StudentsDocumentsList();
            $insert->document_id = $document_id->id;
            $insert->user_id = $id;
            $insert->program_level_id = $program_level;
            $insert->grade_period_id = $period;
            $insert->year_from = $year_from;
            $insert->year_to = $year_to;
            $insert->updated_by = $user->id;
            $insert->save();

            $url = $this->generateQRscholasticReport($request,$name_user);
        }        

        return $url;
    }

    private function generateQRscholasticReport($request,$name_user){
        error_reporting(E_ERROR);

        $id = $request->id;
        $certification = $request->certification;
        $program_level = $request->program_level;
        $school_year = $request->school_year;
        $period = $request->period;
        $date = date('Y-m-d');

        $token = new TokenServices;
        $token1 = $token->token_w_upper(4);
        $token2 = $token->token_w_upper(4);
        $pdf_code = $token1.mb_substr($date, -1).$token2;
        $password = $token1.mb_substr($date, -1).$token2;

        $student = Users::where('id',$id)
            ->where('stud_id','!=',NULL)
            ->first();
        $stud_id = $student->stud_id;

        $path = 'storage\rims\students/'.$stud_id.'\certification/';
        
        // File::deleteDirectory($path);

        $image = QrCode::format('png')
                    ->merge(public_path('assets\images\logo\lnu_logo.png'), .28, true)
                    ->style('round', 0.2)
                    //->eye('circle')
                    ->eyeColor(1, /*outer*/ 0, 0, 128, /*inner*/ 212,175,55, 0, 0)
                    ->eyeColor(2, /*outer*/ 212,175,55, /*inner*/ 0, 0, 128, 0, 0)
                    ->size(300)
                    ->errorCorrection('H')
                    ->generate('student/certification/'.$stud_id.'/'.$certification.'_protected/'.$program_level.'/'.$school_year.'/'.$period.'/'.$date.'/'.$pdf_code);
        $imageName = $certification.'_'.$program_level.'_'.$school_year.'_'.$date.'_'.$pdf_code.'.png';        

        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        $file = public_path($path . $imageName);
        file_put_contents($file, $image);
        $qrcode = $path.$imageName;
        $src = $this->generatePDFscholasticReport($student,$request,$date,$qrcode,$pdf_code,$name_user,$password);
        return $src;
    }

    private function generatePDFscholasticReport($student,$request,$date,$qrcode,$pdf_code,$name_user,$password){
        $password_services = new PasswordServices;
        $certification = $request->certification;
        $program_level = $request->program_level;
        $school_year = $request->school_year;
        $period = $request->period;      
        $stud_id = $student->stud_id;

        $master_password = $password_services->master();

        //$pdf = new PDF('A4', 'mm', '', true, 'UTF-8', false);
        $page_size = array(215.9, 330.2);
        $pdf = new Pdf('P', 'mm', $page_size, true, 'UTF-8', false);
        $height = 185;
        $width = 260;
        $pdf::reset();  
        $pdf = $this->generatePDFscholasticReportDetails($pdf,$student,$request,$date,$qrcode,$pdf_code,$name_user,$password);
        $pdf::setProtection();
        $pathUserUnprotected  = 'storage/rims/students/'.$stud_id.'\certification/'.$certification.'_'.$program_level.'_'.$school_year.'_'.$period.'_'.$date.'_'.$pdf_code.'.pdf';
        $pdf::Output(public_path($pathUserUnprotected),'F');       
   
        // $pdf = new Pdf('P', 'mm', $page_size, true, 'UTF-8', false);
        // $pdf::reset();
        // $pdf = $this->generatePDFscholasticReportDetails($pdf,$student,$request,$date,$qrcode,$pdf_code,$name_user,$password);
        // $pdf::setProtection(array('print'), $password, $master_password, 0, null);
        // $pathUserProtected = 'storage/rims/students/'.$stud_id.'\certification/'.$certification.'_protected'.'_'.$program_level.'_'.$school_year.'_'.$period.'_'.$date.'_'.$pdf_code.'.pdf';
        // $pdf::Output(public_path($pathUserProtected),'F');
        
        return $stud_id.'\certification/'.$certification.'_'.$program_level.'_'.$school_year.'_'.$period.'_'.$date.'_'.$pdf_code;
    }

    private function generatePDFscholasticReportDetails($pdf,$student,$request,$date,$qrcode,$pdf_code,$name_user,$password){
        $name_services = new NameServices;
        $certification = $request->certification;
        $program_level = $request->program_level;
        $school_year = $request->school_year;
        $period = $request->period;
        $student_program = $request->program;
        $student_grade_level = $request->year;

        $exp_school_year = explode('-',$school_year);
        $year_from = $exp_school_year[0];
        $year_to = $exp_school_year[1];
        $user_id = $student->id;
        $stud_id = $student->stud_id;
        $name_student = mb_strtoupper($name_services->lastname_full($student->lastname,$student->firstname,$student->middlename,$student->extname));
        
        $grade_period = EducGradePeriod::find($period);
        $grade_period_name_no = $grade_period->name_no;

        $get_grade_level = StudentsCourses::select('grade_level_id')
            ->where('user_id',$user_id)
            ->where('program_level_id',$program_level)
            ->where('grade_period_id',$period)
            ->where('year_from',$year_from)
            ->where('year_to',$year_to)
            ->groupBy('grade_level_id')
            ->get();
        if($get_grade_level->count()>0){
            $list_grade_level = [];
            foreach($get_grade_level as $row){
                if($row->grade_level_id){
                    $list_grade_level[] = $row->grade_level_id;
                }
            }
            if(count($list_grade_level)>0){
                $grade_level_counts = array_count_values($list_grade_level);
                $most_common_grade_level_id = array_search(max($grade_level_counts), $grade_level_counts);
                $grade_level = EducYearLevel::where('id',$most_common_grade_level_id)->first();
                $student_grade_level = $grade_level->level;
            }
        }
        $student_courses = StudentsCourses::with('program')
            ->where('user_id',$user_id)
            ->where('program_level_id',$program_level)
            ->where('grade_period_id',$period)
            ->where('year_from',$year_from)
            ->where('year_to',$year_to)
            ->orderBy('course_code', 'ASC')
            ->first();
        if($student_courses){
            if($student_courses->program->program_shorten){
                $student_program = $student_courses->program->program_shorten;
            }
        }

        $logo = public_path('assets\images\logo\lnu_logo.png');
        $logo_blur = public_path('assets\images\logo\lnu_logo_blur1.png');
        $at_icon = public_path('assets\images\icons\png\at.png');
        $web_icon = public_path('assets\images\icons\png\web.png');
        $phone_icon = public_path('assets\images\icons\png\phone.png');

              

        $pdf::SetCreator(PDF_CREATOR);
        $pdf::SetAuthor($name_user);
        $pdf::SetTitle($stud_id.' - '.$name_student);
        $pdf::SetSubject('Scholastic Report '.$grade_period_name_no.' '.$school_year);

        // $pdf::setHeaderCallback(function($pdf) use ($logo,$qrcode,$at_icon,$web_icon,$phone_icon,$grade_period_name_no,$school_year,$password){
        //        $y = 8;
        //        $x = 10;
        //        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        //        $pdf->Image($logo,12,10, 25, 25, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
               
        //        $pdf->SetXY($x, $y);
        //        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        //        $pdf->Cell(197, 29, '', 1, 1, 'L', 0, '', 1);
        //        $pdf->SetXY($x, $y+3);
        //        $pdf->Cell(115, 23, '', 'R', 1, 'L', 0, '', 1);
        //        $pdf->SetXY($x, $y+3);
        //        $pdf->Cell(157, 23, '', 'R', 1, 'L', 0, '', 1);
        //        $pdf->SetXY($x+118, $y+7);
        //        $pdf->Image($phone_icon, '', '', 3, 3, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
        //        $pdf->SetXY($x+118, $y+13);
        //        $pdf->Image($at_icon, '', '', 3, 3, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
        //        $pdf->SetXY($x+118, $y+20);
        //        $pdf->Image($web_icon, '', '', 3, 3, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
        //        $pdf->SetXY($x+160, $y+2);
        //        $pdf->Image($qrcode, '', '', 35, 24, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
        //        $pdf->SetXY($x+160, $y+25.5);
        //        $pdf->SetFont('arial','',8);
        //        $pdf->Cell(35, '', $password, 0, 1, 'C', 0, '', 1);
               
        //        $pdf->SetXY($x+30, $y+4);
        //        $pdf->SetFont('arial','',10);
        //        $pdf->Cell(95, '', 'Republic of the Philippines', 0, 1, 'L', 0, '', 1);
        //        $pdf->SetXY($x+30, $y+15);
        //        $pdf->Cell(95, '', 'Tacloban City, Philippines', 0, 1, 'L', 0, '', 1);
        //        $pdf->SetFont('arial','',9);
        //        $pdf->SetXY($x+125, $y+6);
        //        $pdf->Cell(30, '', '+63 53 888 0855', 0, 1, 'L', 0, '', 1);
        //        $pdf->SetXY($x+125, $y+12);
        //        $pdf->Cell(30, '', 'registrar@lnu.edu.ph', 0, 1, 'L', 0, '', 1);
        //        $pdf->SetXY($x+125, $y+19);
        //        $pdf->Cell(30, '', 'www.lnu.edu.ph', 0, 1, 'L', 0, '', 1);


        //        $pdf->SetXY($x+30, $y+9);
        //        $pdf->SetFont('arialb','',12);
        //        $pdf->Cell(95, '', 'LEYTE NORMAL UNIVERSITY', 0, 1, 'L', 0, '', 1);
        //        $pdf->SetXY($x+30, $y+20);
        //        $pdf->Cell(95, '', 'Office of the Registrar', 0, 1, 'L', 0, '', 1);

        //        $pdf->SetXY($x, $y+32);
        //        $pdf->Cell(197, '', "STUDENT'S SCHOLASTIC REPORT", 0, 1, 'C', 0, '', 1);
        //        $pdf->SetFont('arial','',10);
        //        $pdf->SetXY($x, $y+37);
        //        $pdf->Cell(197, '', $grade_period_name_no.' '.$school_year.'/FINAL GRADE', 0, 1, 'C', 0, '', 1);
        // });

        $pdf::setHeaderCallback(function($pdf) use ($grade_period_name_no,$school_year){
               $y = 5;
               $x = 10;
               $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);               

               $pdf->SetXY($x, $y+4);
               $pdf->SetFont('typewrite','',10);
               $pdf->Cell(197, '', 'Republic of the Philippines', 0, 1, 'C', 0, '', 1);
               $pdf->SetXY($x, $y+8);
               $pdf->SetFont('typewriteb','',10);
               $pdf->Cell(197, '', 'LEYTE NORMAL UNIVERSITY', 0, 1, 'C', 0, '', 1);
               $pdf->SetXY($x, $y+12);
               $pdf->SetFont('typewrite','',8);
               $pdf->Cell(197, '', 'Tacloban City', 0, 1, 'C', 0, '', 1);
               $pdf->SetFont('typewrite','',10);
               $pdf->SetXY($x, $y+17.5);
               $pdf->Cell(197, '', 'OFFICE OF THE REGISTRAR', 0, 1, 'C', 0, '', 1);
               $pdf->SetXY($x, $y+23);
               $pdf->SetFont('typewriteb','',9);
               $pdf->Cell(197, '', "STUDENT'S SCHOLASTIC REPORT", 0, 1, 'C', 0, '', 1);               
               $pdf->SetXY($x, $y+27);
               $pdf->SetFont('typewrite','',9);
               $pdf->Cell(197, '', $grade_period_name_no.' '.$school_year.'/FINAL GRADE', 0, 1, 'C', 0, '', 1);
        });

        $y = 40;
        $x = 10;
        $y_add = 5;
        $x_add = 0;

        $pdf::AddPage('P',$page_size);
        $pdf::SetAutoPageBreak(TRUE, 3);
        
        $pdf::SetFont('typewrite','',10);
        $pdf::SetXY($x, $y);
        $pdf::Cell(30, '', 'STUDENT NO.:', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+31, $y);
        $pdf::Cell(96, '', $stud_id, 'B', 1, 'L', 0, '', 1);

        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(30, '', 'STUDENT NAME:', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+31, $y+$y_add);
        $pdf::Cell(96, '', $name_student, 'B', 1, 'L', 0, '', 1);

        $pdf::SetXY($x+130, $y);
        $pdf::Cell(15, '', 'DATE:', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+155, $y);
        $pdf::Cell(42, '', date('F d, Y'), 'B', 1, 'L', 0, '', 1);

        $pdf::SetXY($x+130, $y+$y_add);
        $pdf::Cell(25, '', 'COURSE/YR:', 0, 1, 'L', 0, '', 1);

        
        $pdf::SetXY($x+155, $y+$y_add);
        $pdf::Cell(42, '', $student_program.'/'.$student_grade_level, 'B', 1, 'L', 0, '', 1);

        $y_add = $y_add+8;
        $pdf::SetFont('typewriteb','',9);
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(35, '', 'Code', 1, 1, 'C', 0, '', 1);

        $x_add = $x_add+35;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(91, '', 'Description', 1, 1, 'C', 0, '', 1);

        $x_add = $x_add+91;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(19, '', 'Grade', 1, 1, 'C', 0, '', 1);

        $x_add = $x_add+19;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(19, '', 'Unit', 1, 1, 'C', 0, '', 1);

        $x_add = $x_add+19;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(33, '', 'Remakrs', 1, 1, 'C', 0, '', 1);

        $pdf::SetFont('typewrite','',9);
        $student_courses = StudentsCourses::with('status')
            ->where('user_id',$user_id)
            ->where('program_level_id',$program_level)
            ->where('grade_period_id',$period)
            ->where('year_from',$year_from)
            ->where('year_to',$year_to)
            ->orderBy('course_code', 'ASC')
            ->get();
        $total_units = 0;
        $passed_units = 0;
        $grade_unit_product = 0;
        if($student_courses->count()>0){
            $y_add = $y_add+5;
            foreach($student_courses as $course){
                $x_add = 0;
                
                $pdf::SetXY($x, $y+$y_add);
                // $pdf::Cell(40, 8, $course->course_code, 'LR', 1, 'C', 0, '', 1);
                $pdf::MultiCell(35, 6, $course->course_code, 'LR', 'L', 0, 0, '', '', true);

                $x_add = $x_add+35;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                //$pdf::Cell(86, 8, $course->course_desc, 'LR', 1, 'L', 0, '', 1);
                $pdf::MultiCell(91, 6, $course->course_desc, 'LR', 'L', 0, 1, '', '', true, 0, false, true, 0, '', true);

                $x_add = $x_add+91;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                //$pdf::Cell(19, 8, $course->grade, 'LR', 1, 'C', 0, '', 1);
                $course_grade = '';
                $inc = '';
                if($course->grade>0){
                    $course_grade = number_format($course->grade,1);
                }
                if($course->inc=='INC'){
                    $inc = 'INC ';
                }
                $pdf::MultiCell(19, 6, $inc.$course_grade, 'LR', 'C', 0, 0, '', '', true);

                $x_add = $x_add+19;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                //$pdf::Cell(19, 8, $course->course_units, 'LR', 1, 'C', 0, '', 1);
                $course_units = '';
                if($course->course_units>0){
                    $course_units = number_format($course->course_units,2);
                }
                $pdf::MultiCell(19, 6, $course_units, 'LR', 'C', 0, 0, '', '', true);

                $x_add = $x_add+19;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                //$pdf::Cell(33, 8, $course->status->name, 'LR', 1, 'C', 0, '', 1);
                if($course->student_course_status_id==NULL){
                    $pdf::MultiCell(33, 6, 'NG', 'LR', 'C', 0, 0, '', '', true);
                }else{
                    $pdf::MultiCell(33, 6, $course->status->name, 'LR', 'C', 0, 0, '', '', true);
                }

                $y_add = $y_add+6;
                
                if($course->status->option==1){
                    $passed_units += $course->course_units;
                    $grade_unit_product += $course->grade*$course->course_units;
                }
                $total_units += $course->course_units;
            }
            $x_add = 0;
            $y_add = $y_add-1;
            $pdf::SetXY($x, $y+$y_add);
            $pdf::Cell(35, '', '', 'LR', 1, 'C', 0, '', 1);
                
            $x_add = $x_add+35;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(91, '', '- - - - - Nothing Follows - - - - -', 'LR', 1, 'C', 0, '', 1);

            $x_add = $x_add+91;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(19, '', '', 'LR', 1, 'C', 0, '', 1);

            $x_add = $x_add+19;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(19, '', '', 'LR', 1, 'C', 0, '', 1);

            $x_add = $x_add+19;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(33, '', '', 'LR', 1, 'C', 0, '', 1);            
        }
        $x_add = 0;
        $y_add = $y_add+4;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(40, '', 'TOTAL NO. OF UNITS', 1, 1, 'C', 0, '', 1);

        $x_add = $x_add+40;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(13, '', $total_units, 1, 1, 'C', 0, '', 1);

        $x_add = $x_add+13;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(23, '', 'UNITS PASSED', 1, 1, 'C', 0, '', 1);

        $x_add = $x_add+23;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(13, '', $passed_units, 1, 1, 'C', 0, '', 1);

        $x_add = $x_add+13;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(24, '', 'UNITS FAILED', 1, 1, 'C', 0, '', 1);

        $x_add = $x_add+24;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(13, '', $total_units-$passed_units, 1, 1, 'C', 0, '', 1);

        $gwa = '';
        if($passed_units>0){
            $gwa = round(($grade_unit_product/$passed_units),2);
        }
        $x_add = $x_add+13;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(38, '', 'G.W.A. : '.$gwa, 1, 1, 'C', 0, '', 1);

        $x_add = $x_add+38;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(33, '', '', 1, 1, 'C', 0, '', 1);

        $x_add = 5;
        $y_add = $y_add+7;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(190, '', 'UNDERGRADUATE GRADES TRANSMUTATION TABLE:', 0, 1, 'L', 0, '', 1);
        $y_add = $y_add+4;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(190, '', '1.0(100-95) Excellent; 1.1-1.5(94-90) Very Good; 1.6-2.5(89-80) Good; 2.6-3.0(79-75) Fair; 4.0(74-70) Conditioned.', 0, 1, 'L', 0, '', 1);
        $y_add = $y_add+4;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(190, '', '5.0(69 and below) Failure: student must repeat; WDR - Withdrawn Subject; DR - Dropped; INC - Incomplete.', 0, 1, 'L', 0, '', 1);
        
        return $pdf;
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function showValidateRequest(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'certification' => 'required|string'
        ];

        $customMessages = [
            'id.required' => 'ID is required.',
            'id.numeric' => 'ID must be a number.',
            'certification.required' => 'Certification is required.',
            'certification.string' => 'Certification must be a string.'
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }

    /**
     * Validate the request data.
     *
     * @param Request $request The HTTP request instance.
     * @return \Illuminate\Contracts\Validation\Validator The validation validator instance.
     */
    private function scholasticReportValidateRequest(Request $request)
    {
        $rules = [
            'program_level' => 'required|numeric',
            'school_year' => 'required|string',
            'period' => 'required|numeric',
            'program' => 'nullable|string',
            'year' => 'nullable|string'
        ];

        $customMessages = [
            'program_level.required' => 'Program Level is required.',
            'program_level.numeric' => 'Program Level must be a number.',
            'school_year.required' => 'School Year is required.',
            'school_year.string' => 'School Year must be a string.',
            'period.required' => 'Period is required.',
            'period.numeric' => 'Period must be a number.',
        ];

        return Validator::make($request->all(), $rules, $customMessages);
    }
}
