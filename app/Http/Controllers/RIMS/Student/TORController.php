<?php

namespace App\Http\Controllers\RIMS\Student;
use App\Http\Controllers\Controller;
use App\Models\EducGradePeriod;
use App\Models\EducProgramLevel;
use App\Models\StudentsCourses;
use App\Models\StudentsInfo;
use App\Models\StudentsTOR;
use App\Services\NameServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class TORController extends Controller
{
    public function tor(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id_no = $request->id_no;
            $level = $request->level;
            $dateTime = date('Y-m-d H:i:s',strtotime(str_replace('_',' ',$request->dateTime)));
            $student = StudentsInfo::where('id_no',$id_no)->first();
            if($student){
                $tor = StudentsTOR::where('user_id',$student->user_id)
                    ->where('level_id',$level)
                    ->where('created_at',$dateTime)
                    ->first();
                if($tor){
                    $src = $this->generateQR($id_no,$level,$dateTime);
                    $data = array(
                        'id_no' => $id_no,
                        'src' => $src
                    );
                    return view('pdf/main_view',$data);
                }
            }
        }
        return view('layouts/error/404');        
    }
    private function generateQR($id_no,$level,$dateTime){
        error_reporting(E_ERROR);
        $dateTime = date('Y-m-d H-i',strtotime($dateTime));
        $level_name = EducProgramLevel::find($level);
        $image = QrCode::format('png')
                    ->merge(public_path('assets\images\logo\lnu_logo.png'), .28, true)
                    ->style('round', 0.2)
                    //->eye('circle')
                    ->eyeColor(1, /*outer*/ 0, 0, 128, /*inner*/ 212,175,55, 0, 0)
                    ->eyeColor(2, /*outer*/ 212,175,55, /*inner*/ 0, 0, 128, 0, 0)
                    ->size(300)
                    ->errorCorrection('H')
                    ->generate('TOR_'.$id_no.'_'.$level_name->name.'_'.$dateTime);
        $imageName = $id_no.'_'.$level.'_'.$dateTime.'.png';
        $path = 'storage\rims\students/'.$id_no.'\tor/'.$level.'/';
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
        $file = public_path($path . $imageName);
        file_put_contents($file, $image);
        $qrcode = $path.$imageName;
        $src = $this->generatePDF($id_no,$level,$dateTime,$qrcode);
        return $src;
    }
    private function generatePDF($id_no,$level,$dateTime,$qrcode){
        $user = Auth::user();
        $pathUser = NULL;
        $student = StudentsInfo::with('info')->where('id_no',$id_no)->first();
        $user_id = $student->user_id;
        $name_services = new NameServices;
        $name_student = mb_strtoupper($name_services->lastname_full($student->info->lastname,$student->info->firstname,$student->info->middlename,$student->info->extname));
        $name_user = mb_strtoupper($name_services->firstname($user->lastname,$user->firstname,$user->middlename,$user->extname));
        $position_user = $user->employee_default->position_title;
        $logo = public_path('assets\images\logo\lnu_logo.png');
        $logo_blur = public_path('assets\images\logo\lnu_logo_blur1.png');
        $at_icon = public_path('assets\images\icons\png\at.png');
        $web_icon = public_path('assets\images\icons\png\web.png');
        $phone_icon = public_path('assets\images\icons\png\phone.png');

        //$pdf = new PDF('A4', 'mm', '', true, 'UTF-8', false);
        $page_size = array(215.9, 330.2);
        $pdf = new Pdf('P', 'mm', $page_size, true, 'UTF-8', false);
        $height = 185;
        $width = 260;
        $pdf::reset();
        $pdf::setHeaderCallback(function($pdf) use ($logo,$qrcode,$at_icon,$web_icon,$phone_icon){
               $y = 8;
               $x = 10;
               $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
               $pdf->Image($logo,12,10, 25, 25, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
               
               $pdf->SetXY($x, $y);
               $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
               $pdf->Cell(197, 29, '', 1, 1, 'L', 0, '', 1);
               $pdf->SetXY($x, $y+3);
               $pdf->Cell(115, 23, '', 'R', 1, 'L', 0, '', 1);
               $pdf->SetXY($x, $y+3);
               $pdf->Cell(157, 23, '', 'R', 1, 'L', 0, '', 1);
               $pdf->SetXY($x+118, $y+7);
               $pdf->Image($phone_icon, '', '', 3, 3, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
               $pdf->SetXY($x+118, $y+13);
               $pdf->Image($at_icon, '', '', 3, 3, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
               $pdf->SetXY($x+118, $y+20);
               $pdf->Image($web_icon, '', '', 3, 3, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
               $pdf->SetXY($x+160, $y+2);
               $pdf->Image($qrcode, '', '', 35, 25, '', '', 'T', false, 0, '', false, false, 0, false, false, false);

               $pdf->SetXY($x+30, $y+4);
               $pdf->SetFont('arial','',10);
               $pdf->Cell(95, '', 'Republic of the Philippines', 0, 1, 'L', 0, '', 1);
               $pdf->SetXY($x+30, $y+15);
               $pdf->Cell(95, '', 'Tacloban City, Philippines', 0, 1, 'L', 0, '', 1);
               $pdf->SetFont('arial','',9);
               $pdf->SetXY($x+125, $y+6);
               $pdf->Cell(30, '', '+63 53 888 0855', 0, 1, 'L', 0, '', 1);
               $pdf->SetXY($x+125, $y+12);
               $pdf->Cell(30, '', 'registrar@lnu.edu.ph', 0, 1, 'L', 0, '', 1);
               $pdf->SetXY($x+125, $y+19);
               $pdf->Cell(30, '', 'www.lnu.edu.ph', 0, 1, 'L', 0, '', 1);


               $pdf->SetXY($x+30, $y+9);
               $pdf->SetFont('arialb','',12);
               $pdf->Cell(95, '', 'LEYTE NORMAL UNIVERSITY', 0, 1, 'L', 0, '', 1);
               $pdf->SetXY($x+30, $y+20);
               $pdf->Cell(95, '', 'Office of the Registrar', 0, 1, 'L', 0, '', 1);

               $pdf->SetXY($x, $y+32);
               $pdf->Cell(197, '', 'Official Transcript of Records', 0, 1, 'C', 0, '', 1);

       });
       $pdf::setFooterCallback(function($pdf) use ($name_user,$position_user){
        // $pdf->getAliasNumPage().' of '.$pdf->getAliasNbPages()
            $y = 240;
            $x = 10;
            $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));            
            $pdf->SetXY($x, $y+1.5);
            $pdf->Cell(197, 12.5, '', 'TB', 1, 'C', 0, '', 1);
            $pdf->SetFont('arial','',8);
            $pdf->SetXY($x, $y+2);
            $pdf->Cell(197, '', 'UNDERGRADUATE GRADES TRANMUTATION TABLE: THE FINAL RATING IS CUMMULATIVE', 0, 1, 'L', 0, '', 1);
            $pdf->SetFont('arial','',6);
            $pdf->SetXY($x, $y+4.5);
            $pdf->Cell(197, '', '1.0(100-95) Excellent; 1.1-1.5(94-90) Very Good; 1.6-2.5(89-80) Good;2.6-3.0(79-75) Fair; 4.0(74-70) Conditioned;', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x, $y+6.5);
            $pdf->Cell(197, '', '5.0(69 and below) Failure; student must repeat; WDR - Withdrawn Subject; DR - Dropped; INC - Incomplete;', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x, $y+9);
            $pdf->Cell(197, '', 'CREDITS:', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x, $y+11);
            $pdf->Cell(197, '', 'One unit of credit is one hour of lecture or three hours of laboratory.', 0, 1, 'L', 0, '', 1);
            $pdf->SetFont('arial','',8);
            $pdf->SetXY($x, $y+14);
            $pdf->Cell(80, '', 'GRADUATE Grading SystemTranscript Guide', 0, 1, 'L', 0, '', 1);

            $pdf->SetXY($x, $y+30);
            $pdf->Cell(197, '', '', 'B', 1, 'C', 0, '', 1);

            $pdf->SetXY($x+120, $y+14);
            $pdf->Cell(80, '', 'Other Symbols', 0, 1, 'L', 0, '', 1);

            $pdf->SetFont('arial','',6);
            $pdf->SetXY($x, $y+17);
            $pdf->Cell(50, '', '1.0 - Superior', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x, $y+19);
            $pdf->Cell(50, '', '1.1', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x, $y+21);
            $pdf->Cell(50, '', '1.2 - Very Good', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x, $y+23);
            $pdf->Cell(50, '', '1.3', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x, $y+25);
            $pdf->Cell(50, '', '1.5', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x, $y+27);
            $pdf->Cell(50, '', '1.6 - Good', 0, 1, 'L', 0, '', 1);

            $pdf->SetXY($x+70, $y+17);
            $pdf->Cell(50, '', '1.7', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x+70, $y+19);
            $pdf->Cell(50, '', '1.8', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x+70, $y+21);
            $pdf->Cell(50, '', '1.9 - Satisfactory', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x+70, $y+23);
            $pdf->Cell(50, '', '2.0', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x+70, $y+25);
            $pdf->Cell(50, '', '3.0 - Poor but Passing', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x+70, $y+27);
            $pdf->Cell(50, '', '5.0 - Failed', 0, 1, 'L', 0, '', 1);

            $pdf->SetXY($x+120, $y+17);
            $pdf->Cell(50, '', 'T - Term Work Incomplete', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x+120, $y+19);
            $pdf->Cell(50, '', 'P - Passed', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x+120, $y+21);
            $pdf->Cell(50, '', 'HP - High Passed', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x+120, $y+23);
            $pdf->Cell(50, '', 'SP - Satisfactory Progress', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x+120, $y+25);
            $pdf->Cell(50, '', 'UP - Unsatisfactory Progress', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x+120, $y+27);
            $pdf->Cell(50, '', 'SC - Satisfactory Completion', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x+120, $y+29);
            $pdf->Cell(50, '', 'INC - Incomplete', 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x+120, $y+31);
            $pdf->Cell(50, '', 'DR - Dropped', 0, 1, 'L', 0, '', 1);

            $pdf->SetXY($x+105, $y+40);
            $pdf->SetFont('arial','',9);
            $pdf->Cell(80, '', 'Certified Correct:', 0, 1, 'L', 0, '', 1);

            $pdf->SetXY($x+128, $y+43);
            $pdf->SetFont('arialb','',9);
            $pdf->Cell(50, '', 'GAY A. PINOTE, DPA', 0, 1, 'C', 0, '', 1);
            $pdf->SetXY($x+128, $y+47);
            $pdf->SetFont('arial','',9);
            $pdf->Cell(50, '', 'Registrar III', 0, 1, 'C', 0, '', 1);
            $pdf->SetXY($x+128, $y+51);
            $pdf->SetFont('arialb','',9);
            $pdf->Cell(50, '', date('d M Y'), 0, 1, 'C', 0, '', 1);

            $pdf->SetXY($x+38, $y+57);
            $pdf->SetFont('arial','',9);
            $pdf->Cell(30, '', 'Prepared by:', 0, 1, 'L', 0, '', 1);

            $pdf->SetXY($x+57, $y+57);
            $pdf->SetFont('arialb','',9);
            $pdf->Cell(60, '', $name_user, 0, 1, 'L', 0, '', 1);
            $pdf->SetXY($x+57, $y+61);
            $pdf->SetFont('arialb','',9);
            $pdf->Cell(60, '', $position_user, 0, 1, 'L', 0, '', 1);

            $pdf->SetXY($x+3, $y+65);
            $pdf->SetFont('arial','',7);
            $pdf->Cell(30, '', 'NOT VALID WITHOUT', 0, 1, 'C', 0, '', 1);
            $pdf->SetXY($x+3, $y+68);
            $pdf->SetFont('arial','',7);
            $pdf->Cell(30, '', 'SEAL', 0, 1, 'C', 0, '', 1);

            $pdf->SetXY($x, $y+70);
            $pdf->Cell(197, '', $pdf->getAliasNumPage().' of '.$pdf->getAliasNbPages(), 0, 1, 'C', 0, '', 1);
        });
        $pdf::AddPage('P',$page_size);
        $pdf::SetAutoPageBreak(TRUE, 3);
       //landscape scale A4
        //$height = 185;
        //$width = 260;
        //Portrait scale A4
        //$width = 210;
        //height = 270;
        $pdf::Image($logo_blur,34,85, 140, 135, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
        
        $y = 47;
        $x = 10;
        $pdf::SetFont('arialb','',10);
        $pdf::SetXY($x, $y);
        $pdf::Cell(197, '', 'Personal Data', 0, 1, 'L', 0, '', 1);

        $y_add = 5;
        $x_add = 3;
        $pdf::SetFont('arial','',9);
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(197, '', 'Name', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+5;
        $x_add = $x_add;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(197, '', 'Birth Date', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+5;
        $x_add = $x_add;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(197, '', 'Birth Place', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+5;
        $x_add = $x_add;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(197, '', 'Gender', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+5;
        $x_add = $x_add;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(197, '', 'Religion', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+5;
        $x_add = $x_add;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(197, '', 'Citizenship', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+5;
        $x_add = $x_add;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(197, '', 'Parent/Guardian', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+5;
        $x_add = $x_add;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(197, '', 'Address', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+5;
        $x_add = $x_add;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(197, '', 'Entrance Data', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+5;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::SetFont('arialb','',10);
        $pdf::Cell(197, '', 'Educational Background', 0, 1, 'L', 0, '', 1);

        $pdf::SetFont('arial','',8);
        
        for($i=0;$i<3;$i++){
            $y_add = $y_add+4;
            $x_add = $x_add;
            $pdf::SetXY($x+$x_add, $y+$y_add);
            $pdf::Cell(30, '', 'Elementary', 0, 1, 'L', 0, '', 1);
        }

        $pdf::SetFont('arialb','',10);
        $y_add = $y_add+4;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(30, '', 'Course :', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+23, $y+$y_add);
        $pdf::Cell(150, '', 'Course :', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+5;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(30, '', 'Purpose :', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+23, $y+$y_add);
        $pdf::Cell(150, '', 'Purpose :', 0, 1, 'L', 0, '', 1);
        
        $pdf::SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        $pdf::SetFont('arialb','',11);
        $y_add = $y_add+7;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(197, '', 'C O L L E G I A T E   R E C O R D', 'TB', 1, 'C', 0, '', 1);
        
        $pdf::SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
        $pdf::SetFont('arial','',8);
        $y_add = $y_add+7;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(50, '', 'School Term & Course No.', 0, 1, 'C', 0, '', 1);

        $x_add = 50;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(103, '', 'Desciptive Title', 0, 1, 'C', 0, '', 1);

        $x_add = $x_add+103;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(22, '', 'Final Rating', 0, 1, 'R', 0, '', 1);
        
        $x_add = $x_add+22;
        $pdf::SetXY($x+$x_add, $y+$y_add);
        $pdf::Cell(22, '', 'Re-Exam Units', 0, 1, 'R', 0, '', 1);
        
        $y_add = $y_add+2;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(197, '', '', 'B', 1, 'C', 0, '', 1);


        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $query = StudentsCourses::where('user_id',$user_id)
            ->where('year_from','>',1)
            ->select('year_from','year_to','grade_period_id','program_shorten','school_name')
            ->groupBy('year_from')
            ->groupBy('grade_period_id')
            ->orderBy('year_from','ASC')
            ->orderBy('grade_period_id','ASC')
            ->get()
            ->map(function($query) use ($user_id){
                $grade_period = EducGradePeriod::where('id',$query->grade_period_id)->first();
                $courses = StudentsCourses::where('user_id',$user_id)
                    ->where('grade_period_id',$query->grade_period_id)
                    ->where('year_from',$query->year_from)
                    ->get();
                return [
                    'school_name' => $query->school_name,
                    'grade_period' => $grade_period->name,
                    'period' => $query->year_from.'-'.$query->year_to,
                    'courses' => $courses,
                    'program_shorten' => $query->program_shorten
                ];
            })->toArray();
        $school_name_old = '';
        $program_shorten_old = '';
        $grade_period_old = '';
        $add_page_check = 0;
        $first_page = 0;
        foreach($query as $row){
            foreach($row['courses'] as $courses){
                if($y+$y_add>238){
                    $pdf::AddPage('P',$page_size);
                    $pdf::SetAutoPageBreak(TRUE, 3);
                    $y = 43;
                    $y_add = 0;

                    $pdf::SetFont('arialb','',10);
                    
                    $y_add = $y_add+4;
                    $pdf::SetXY($x+5, $y+$y_add);
                    $pdf::Cell(190, '', $name_student, 0, 1, 'L', 0, '', 1);

                    $y_add = $y_add+7;
                    $pdf::SetXY($x, $y+$y_add);
                    $pdf::Cell(30, '', 'Course :', 0, 1, 'L', 0, '', 1);

                    $pdf::SetXY($x+23, $y+$y_add);
                    $pdf::Cell(150, '', 'Course :', 0, 1, 'L', 0, '', 1);

                    $y_add = $y_add+5;
                    $pdf::SetXY($x, $y+$y_add);
                    $pdf::Cell(30, '', 'Purpose :', 0, 1, 'L', 0, '', 1);

                    $pdf::SetXY($x+23, $y+$y_add);
                    $pdf::Cell(150, '', 'Purpose :', 0, 1, 'L', 0, '', 1);

                    $pdf::SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
                    $pdf::SetFont('arialb','',11);
                    $y_add = $y_add+7;
                    $pdf::SetXY($x, $y+$y_add);
                    $pdf::Cell(197, '', 'C O L L E G I A T E   R E C O R D', 'TB', 1, 'C', 0, '', 1);
                    
                    $pdf::SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
                    $pdf::SetFont('arial','',8);
                    $y_add = $y_add+7;
                    $pdf::SetXY($x, $y+$y_add);
                    $pdf::Cell(50, '', 'School Term & Course No.', 0, 1, 'C', 0, '', 1);

                    $x_add = 50;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell(103, '', 'Desciptive Title', 0, 1, 'C', 0, '', 1);

                    $x_add = $x_add+103;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell(22, '', 'Final Rating', 0, 1, 'R', 0, '', 1);
                    
                    $x_add = $x_add+22;
                    $pdf::SetXY($x+$x_add, $y+$y_add);
                    $pdf::Cell(22, '', 'Re-Exam Units', 0, 1, 'R', 0, '', 1);
                    
                    $y_add = $y_add+2;
                    $pdf::SetXY($x, $y+$y_add);
                    $pdf::Cell(197, '', '', 'B', 1, 'C', 0, '', 1);
                    $add_page_check++;
                }
                $school_name = $row['school_name'].', ';
                if($school_name_old!=''){
                    if($school_name==$school_name_old){
                        $school_name = '';
                    }
                }              
                
                $grade_period = $row['grade_period'];
                if($courses->option!=NULL){
                    $grade_period = str_replace('Semester','',$row['grade_period']).' '.$courses->option;
                }
                $program_shorten = $row['program_shorten'];
                if($program_shorten_old!=''){
                    if(($program_shorten==$program_shorten_old && $school_name==$school_name_old) || $school_name==''){
                        $program_shorten = '';
                    }
                }
                if($grade_period!=$grade_period_old){
                    $pdf::SetFont('arialb','',8);
                    $y_add = $y_add+4;
                    $pdf::SetXY($x, $y+$y_add);
                    $pdf::Cell(197, '', $grade_period.' S.Y. '.$row['period'].' '.$school_name.$program_shorten, 0, 1, 'L', 0, '', 1);
                }
                $pdf::SetFont('arialb','',8);
                $y_add = $y_add+4;
                $x_add = 2;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(50, '', $courses->course_code, 0, 1, 'L', 0, '', 1);

                $pdf::SetFont('arial','',8);
                $x_add = $x_add+50;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(103, '', $courses->course_desc, 0, 1, 'L', 0, '', 1);

                if($courses->final_grade!=NULL || $courses->final_grade!=''){
                    $final_grade = $courses->final_grade;
                }else{
                    $final_grade = $courses->status->shorten;
                }

                $x_add = $x_add+103;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(20, '', $final_grade, 0, 1, 'R', 0, '', 1);

                if($courses->final_grade<='3' && ($courses->final_grade!=NULL || $courses->final_grade!='')){
                    $course_units = $courses->course_units;
                }else{
                    $course_units = 0;
                }

                $x_add = $x_add+22;
                $pdf::SetXY($x+$x_add, $y+$y_add);
                $pdf::Cell(20, '', $course_units, 0, 1, 'R', 0, '', 1);
                    
                $school_name_old = $row['school_name'].', ';
                $program_shorten_old = $row['program_shorten'];
                $grade_period_old = $grade_period;
                
            }
        }
        if($y+$y_add<=234){
            $y_add = $y_add+4;
            $pdf::SetXY($x, $y+$y_add);
            $pdf::Cell(197, '', '- - - - - - - - - -  Nothing Follows  - - - - - - - - - -', 0, 1, 'C', 0, '', 1);
        }

        $pathUser = 'storage\rims\students/'.$id_no.'\tor/'.$level.'/'.$id_no.'_'.$level.'_'.$dateTime.'.pdf';
        $pdf::Output(public_path($pathUser),'F');

        return $pathUser;
    }
}