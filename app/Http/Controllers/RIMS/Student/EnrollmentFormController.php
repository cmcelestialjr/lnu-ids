<?php

namespace App\Http\Controllers\RIMS\Student;
use App\Http\Controllers\Controller;
use App\Models\EducFees;
use App\Models\EducFeesType;
use App\Models\EducGradePeriod;
use App\Models\EducLabGroup;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedDiscount;
use App\Models\EducOfferedDiscountFeesType;
use App\Models\EducOfferedDiscountList;
use App\Models\EducOfferedFees;
use App\Models\EducOfferedLabCourses;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramLevel;
use App\Models\StudentsCourses;
use App\Models\StudentsEnrollmentForm;
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

class EnrollmentFormController extends Controller
{
    public function form(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
            $id_no = $request->id_no;
            $school_year = explode('-',$request->school_year);
            $year_from = $school_year[0];
            $year_to = $school_year[1];            
            $school_period = str_replace('-',' ',$request->school_period);
            $student = StudentsInfo::where('id_no',$id_no)->first();
            $school_year_info = EducOfferedSchoolYear::where('year_from',$year_from)
                ->where('year_to',$year_to)
                ->whereHas('grade_period', function ($query) use ($school_period) {
                    $query->where('name',$school_period);
                })->first();
            if($student && $school_year_info){
                $school_year_id = $school_year_info->id;
                $courses = StudentsCourses::where('user_id',$student->user_id)
                    ->where('school_year_id',$school_year_id)
                    ->first();
                if($courses){
                   $src = $this->generateQR($courses,substr($year_from, -2),$school_year_id,$request->school_year,$request->school_period);
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
    private function generateQR($student,$year_from,$school_year_id,$school_year,$school_year_period){
        error_reporting(E_ERROR);
        $enrollment_form_no = $this->enrollment_form_no($student->user_id,$school_year_id,$year_from);
        $id_no = $student->student_info->id_no;
        $image = QrCode::format('png')
                    ->merge(public_path('assets\images\logo\lnu_logo.png'), .28, true)
                    ->style('round', 0.2)
                    //->eye('circle')
                    ->eyeColor(1, /*outer*/ 0, 0, 128, /*inner*/ 212,175,55, 0, 0)
                    ->eyeColor(2, /*outer*/ 212,175,55, /*inner*/ 0, 0, 128, 0, 0)
                    ->size(300)
                    ->errorCorrection('H')
                    ->generate('ENROLLMENTFORM_'.$id_no.'_'.$school_year.'_'.$school_year_period.'_'.$enrollment_form_no);
        $imageName = $id_no.'_'.$school_year.'_'.$school_year_period.'.png';
        $path = 'storage\rims\students/'.$id_no.'\enrollment_form/';
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
        $file = public_path($path . $imageName);
        file_put_contents($file, $image);
        $qrcode = $path.$imageName;
        $src = $this->generatePDF($student,$school_year_id,$school_year,$school_year_period,$qrcode,$enrollment_form_no);
        return $src;
    }
    private function enrollment_form_no($id,$school_year_id,$year_from){
        $user = Auth::user();
        $updated_by = $user->id;
        $check_enrollment_form_no = StudentsEnrollmentForm::where('school_year_id',$school_year_id)
            ->where('user_id',$id)
            ->first();
        if($check_enrollment_form_no==NULL){
            $get_no = StudentsEnrollmentForm::where('school_year_id',$school_year_id)
                ->orderBy('no','DESC')
                ->first();
            $no = $year_from.'00001';
            if($get_no!=NULL){
                $no = $get_no->no+1;
            }
            $insert = new StudentsEnrollmentForm(); 
            $insert->school_year_id = $school_year_id;
            $insert->user_id = $id;
            $insert->no = $no;
            $insert->updated_by = $updated_by;
            $insert->save();
            $enrollment_form_no = $no;
        }else{
            $enrollment_form_no = $check_enrollment_form_no->no;
        }
        return $enrollment_form_no;
    }
    private function generatePDF($student,$school_year_id,$school_year,$school_year_period,$qrcode,$enrollment_form_no){
        $user = Auth::user();
        $pathUser = NULL;
        $user_id = $student->student_info->user_id;
        $name_services = new NameServices;
        $name_student = mb_strtoupper($name_services->lastname($student->student_info->info->lastname,$student->student_info->info->firstname,$student->student_info->info->middlename,$student->student_info->info->extname));
        $name_user = mb_strtoupper($name_services->lastname($user->lastname,$user->firstname,$user->middlename,$user->extname));
        $position_user = $user->employee_default->position_title;
        $logo = public_path('assets\images\logo\lnu_logo.png');
        $logo_blur = public_path('assets\images\logo\lnu_logo_blur1.png');
        $at_icon = public_path('assets\images\icons\png\at.png');
        $web_icon = public_path('assets\images\icons\png\web.png');
        $phone_icon = public_path('assets\images\icons\png\phone.png');
        
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
        $get_no = EducOfferedCourses::whereHas('students', function ($query) use ($user_id,$school_year_id) {
                $query->where('user_id',$user_id);
                $query->where('school_year_id',$school_year_id);
            })
            ->select('section_code', DB::raw('COUNT(*) as count'))
            ->groupBy('section_code')
            ->orderByDesc('count')
            ->first();
        $section = $get_no->section_code;
        //$pdf = new PDF('A4', 'mm', '', true, 'UTF-8', false);
        $page_size = array(210, 270);
        $pdf = new Pdf('P', 'mm', $page_size, true, 'UTF-8', false);
        $pdf::reset();
        $pdf::AddPage('P',$page_size);
        $pdf::SetAutoPageBreak(TRUE, 3);
       //landscape scale A4
        //$height = 185;
        //$width = 260;
        //Portrait scale A4
        //$width = 210;
        //height = 270;

        //$pdf::Image($logo_blur,34,85, 140, 135, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
        
        $y = 7;
        $x = 7;
        $pdf::SetXY($x, $y);
        $pdf::Image($logo,'','', 25, 25, '', '', 'T', false, 0, '', false, false, 0, false, false, false);
        
        
        $y_add = 7;
        $pdf::SetXY($x+26, $y+$y_add);
        $pdf::SetFont('times','b',12);
        $pdf::Cell(100, '', 'LEYTE NORMAL UNIVERSITY', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+116, $y+$y_add);
        $pdf::SetFont('times','b',10);
        $pdf::Cell(80, '', str_replace('-',' ',$school_year_period).' '.$school_year, 0, 1, 'R', 0, '', 1);

        $y_add = $y_add+6;
        $pdf::SetXY($x+26, $y+$y_add);
        $pdf::SetFont('times','',10);
        $pdf::Cell(100, '', 'Tacloban City, Leyte', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+116, $y+$y_add);
        $pdf::Cell(80, '', 'Enrollment and Assessment Form No. '.$enrollment_form_no, 0, 1, 'R', 0, '', 1);

        $y_add = $y_add+13;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(198, 17, '', 'TB', 1, 'L', 0, '', 1);

        $y_add = $y_add+1;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(15, '', 'ID No:', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+17, $y+$y_add);
        $pdf::Cell(25, '', $student->student_info->id_no, 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+38, $y+$y_add);
        $pdf::Cell(13, '', 'Name:', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+50, $y+$y_add);
        $pdf::Cell(70, '', $name_student, 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+122, $y+$y_add);
        $pdf::Cell(15, '', 'Year:', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+137, $y+$y_add);
        $pdf::Cell(15, '', $student->program->grade_level->level, 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+177, $y+$y_add);
        $pdf::Cell(18, '', date('m/d/Y'), 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+5;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(15, '', 'College:', 0, 1, 'L', 0, '', 1);
        
        $pdf::SetXY($x+17, $y+$y_add);
        $pdf::Cell(70, '', $student->program->program_info->departments->name, 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+122, $y+$y_add);
        $pdf::Cell(15, '', 'Section:', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+137, $y+$y_add);
        $pdf::Cell(15, '', $section, 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+5;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(15, '', 'Course:', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+17, $y+$y_add);
        $pdf::Cell(100, '', $student->program->program_info->name, 0, 1, 'L', 0, '', 1);
        
        $pdf::SetFont('times','',9);
        $y_add = $y_add+7;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(20, '', 'SUBJECT', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+20, $y+$y_add);
        $pdf::Cell(64, '', 'DESCRIPTION', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+84, $y+$y_add);
        $pdf::Cell(8, '', 'UNITS', 0, 1, 'C', 0, '', 1);
        
        $pdf::SetXY($x+94, $y+$y_add);
        $pdf::Cell(6, '', 'LAB', 0, 1, 'C', 0, '', 1);

        $pdf::SetXY($x+102, $y+$y_add);
        $pdf::Cell(45, '', 'SCHEDULE AND ROOM', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+147, $y+$y_add);
        $pdf::Cell(15, '', 'SECTION', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+162, $y+$y_add);
        $pdf::Cell(23, '', 'INSTRUCTOR', 0, 1, 'L', 0, '', 1);
        
        $courses = StudentsCourses::where('user_id',$user_id)
            ->where('school_year_id',$school_year_id)
            ->get();
        $total_units = 0;
        $courses_code_list = [];
        if($courses->count()>0){
            $count_row = 0;
            foreach($courses as $row){
                $courses_code_list[] = $row->course_code;
                $y_add = $y_add + ($count_row == 0 ? 4 : 4);                
                $pdf::SetFont('times','',9);
                $pdf::SetXY($x, $y+$y_add);
                $pdf::Cell(20, '', $row->course_code, 0, 1, 'L', 0, '', 1);

                $pdf::SetFont('times','',8);
                $pdf::SetXY($x+20, $y+$y_add);
                $pdf::Cell(64, '', substr($row->course_desc,0,51), 0, 1, 'L', 0, '', 1);

                $pdf::SetFont('times','',9);
                $pdf::SetXY($x+84, $y+$y_add);
                $pdf::Cell(8, '', $row->course_units, 0, 1, 'C', 0, '', 1);
                
                $lab_units = $row->lab_units > 0 ? $row->lab_units : '';
                $pdf::SetXY($x+94, $y+$y_add);
                $pdf::Cell(6, '', $lab_units, 0, 1, 'C', 0, '', 1);                

                $pdf::SetXY($x+147, $y+$y_add);
                $pdf::Cell(10, '', $row->course->section_code, 0, 1, 'L', 0, '', 1);

                $instructor = $row->course->instructor_id!=NULL ? substr($row->course->instructor->firstname, 0, 1).'. '.$row->course->instructor->lastname : 'TBA';
                $pdf::SetXY($x+163, $y+$y_add);
                $pdf::Cell(23, '', $instructor, 0, 1, 'L', 0, '', 1);

                $pdf::SetXY($x+188, $y+$y_add);
                $pdf::Cell(10, '', '', 'B', 1, 'L', 0, '', 1);

                $pdf::SetFont('times','',9);
                if(count($row->course->schedule)>=1){
                    $count_sched = 0;
                    foreach($row->course->schedule as $sched){
                        if($count_sched>0){
                            $y_add = $y_add + 4;
                        }
                        $pdf::SetXY($x+102, $y+$y_add);
                        $pdf::Cell(18, '', date('ha',strtotime($sched->time_from)).'-'.date('ha',strtotime($sched->time_to)), 0, 1, 'L', 0, '', 1);
                        $day_get = [];
                        foreach($sched->days as $day){
                            $day_get[] = $day->day;
                        }
                        $day_get = implode('',$day_get);
                        $pdf::SetXY($x+120, $y+$y_add);
                        $pdf::Cell(15, '', $day_get, 0, 1, 'L', 0, '', 1);
                        
                        $pdf::SetXY($x+132, $y+$y_add);
                        $pdf::Cell(12, '', $sched->room->name, 0, 1, 'L', 0, '', 1);
                        $count_sched++;
                    }
                }else{
                    $pdf::SetXY($x+102, $y+$y_add);
                    $pdf::Cell(15, '', 'TBA', 0, 1, 'L', 0, '', 1);
                    
                    $pdf::SetXY($x+117, $y+$y_add);
                    $pdf::Cell(15, '', 'TBA', 0, 1, 'L', 0, '', 1);

                    $pdf::SetXY($x+132, $y+$y_add);
                    $pdf::Cell(15, '', 'TBA', 0, 1, 'L', 0, '', 1);
                }
                $count_row++;
                $total_units += $row->course_units+$row->lab_units;
            }
        }
        $y_add = $y_add+8;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(198, '', '', 'T', 1, 'L', 0, '', 1);

        $y_add = $y_add+1;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::SetFont('times','b',9);
        $pdf::Cell(99, '', 'ASSESSMENT DETAILS:', 0, 1, 'L', 0, '', 1);

        $program_level_id = $student->program->program_level_id;
        $program_level_name = $student->program->program_level->name;
        $program_id = $student->program->program_id;
        $school_year_id = $student->school_year_id;
        
        $fees_course_code = EducOfferedLabCourses::where('school_year_id',$school_year_id)
            ->whereIn('course_code',$courses_code_list)
            ->get();
        $fees_lab_group = EducOfferedLabCourses::where('school_year_id',$school_year_id)
            ->whereIn('course_code',$courses_code_list)
            ->pluck('lab_group_id')->toArray();
        $fees_course_code_count = $fees_course_code->count();

        $get_discount = EducOfferedDiscountList::where('school_year_id',$school_year_id)
            ->where('program_id',$program_id);
        $offered_discount_id = $get_discount->pluck('offered_discount_id')->toArray();
        $offered_discount = EducOfferedDiscount::whereIn('id',$offered_discount_id)->get();
        $fees = EducFeesType::where('id','!=',4)->get();

        if(count($offered_discount_id)>0){
            $pdf::SetXY($x+90, $y+$y_add);
            $pdf::Cell(99, '', 'DISCOUNTS:', 0, 1, 'L', 0, '', 1);
        }

        if($fees->count()>0){
            $total_assessment = 0;
            $total_discount_amount = 0;
            $total_discount_balance = 0;
            $pdf::SetFont('times','',8);
            foreach($fees as $row_fees){
                $fees_type_id = $row_fees->id;
                
                if(($fees_type_id==2 && $fees_course_code_count) || $fees_type_id!=2){
                    $y_add = $y_add+6;
                    $pdf::SetXY($x, $y+$y_add);
                    $pdf::Cell(50, '', $row_fees->name.'s:', 0, 1, 'L', 0, '', 1);

                    if($fees_type_id==1){
                        if($offered_discount->count()>0){
                            // $offered_discount_cell = 108/$offered_discount->count();
                            // $offered_discount_cell1 = $offered_discount_cell/2;
                            // $offered_discount_add_x = 0;
                            // foreach($offered_discount as $row_offered_discount){
                            //     $pdf::SetXY($x+90+$offered_discount_add_x, $y+$y_add-3);
                            //     $pdf::Cell($offered_discount_cell, '', $row_offered_discount->name, 0, 1, 'C', 0, '', 1);

                            //     $pdf::SetXY($x+90+$offered_discount_add_x, $y+$y_add);
                            //     $pdf::Cell($offered_discount_cell1, '', 'Amount', 0, 1, 'R', 0, '', 1);
                            //     $pdf::SetXY($x+90+$offered_discount_add_x+$offered_discount_cell1, $y+$y_add);
                            //     $pdf::Cell($offered_discount_cell1, '', 'Balance', 0, 1, 'R', 0, '', 1);

                            //     $offered_discount_add_x = $offered_discount_cell;
                            // }
                            
                            foreach($offered_discount as $row_offered_discount){
                                $offered_discount_name[] = $row_offered_discount->name;
                            }
                            $offered_discount_name = implode(', ',$offered_discount_name);
                            $pdf::SetXY($x+90, $y+$y_add-3);
                            $pdf::Cell(99, '', $offered_discount_name, 0, 1, 'C', 0, '', 1);
                            $pdf::SetXY($x+90, $y+$y_add);
                            $pdf::Cell(45, '', 'Amount', 0, 1, 'R', 0, '', 1);
                            $pdf::SetXY($x+90+45, $y+$y_add);
                            $pdf::Cell(45, '', 'Balance', 0, 1, 'R', 0, '', 1);
                        }
                    }

                    if($fees_type_id==2){
                        $fees_lab_group = EducLabGroup::whereIn('id',$fees_lab_group)->get();
                        if($fees_lab_group->count()>0){
                            $fees_x = 0;
                            $fees_amount = 0;
                            foreach($fees_lab_group as $row_lab_group){  
                                $fees_x++;
                                $count_course_code = EducOfferedLabCourses::where('school_year_id',$school_year_id)
                                    ->where('lab_group_id',$row_lab_group->id)
                                    ->get()->count();
                                $amount_course_code = EducOfferedLabCourses::where('school_year_id',$school_year_id)
                                    ->where('lab_group_id',$row_lab_group->id)
                                    ->first();      
                                $y_add = $y_add+4;
                                $pdf::SetXY($x+5, $y+$y_add);
                                $pdf::Cell(50, '', 'Lab Fee - '.$row_lab_group->name.' : '.$count_course_code.' x '.$amount_course_code->amount.'/subject', 0, 1, 'L', 0, '', 1);
                                $pdf::SetXY($x+55, $y+$y_add);
                                $pdf::Cell(16, '', number_format(($count_course_code*$amount_course_code->amount),2), 0, 1, 'R', 0, '', 1);
                                $fees_amount += round(($count_course_code*$amount_course_code->amount),2);
                                $total_assessment += round(($count_course_code*$amount_course_code->amount),2);
                                if($fees_x==$fees_lab_group->count()){
                                    $pdf::SetXY($x+71, $y+$y_add);
                                    $pdf::Cell(16, '', number_format(($fees_amount),2), 0, 1, 'R', 0, '', 1);
                                }

                                if($offered_discount->count()>0){
                                    // $offered_discount_cell = 108/$offered_discount->count();
                                    // $offered_discount_cell1 = $offered_discount_cell/2;
                                    // $offered_discount_add_x = 0;
                                    // $discount_amount_less = 0;
                                    // foreach($offered_discount as $row_offered_discount){
                                    //     $offered_discount = EducOfferedDiscountFeesType::where('offered_discount_id',$offered_discount_id)
                                    //         ->where('fees_type_id',$fees_type_id)->first();                                        
                                    //     if($offered_discount!=NULL){
                                    //         $discount_amount = round((($count_course_code*$amount_course_code->amount-$discount_amount_less)*round((50/100),2)),2);                                            
                                    //         $discount_balance = ($count_course_code*$amount_course_code->amount-$discount_amount_less)-$discount_amount;

                                    //         $pdf::SetXY($x+90+$offered_discount_add_x, $y+$y_add);
                                    //         $pdf::Cell($offered_discount_cell1, '', number_format($discount_amount,2), 0, 1, 'R', 0, '', 1);
                                    //         $pdf::SetXY($x+90+$offered_discount_add_x+$offered_discount_cell1, $y+$y_add);
                                    //         $pdf::Cell($offered_discount_cell1, '', number_format($discount_balance,2), 0, 1, 'R', 0, '', 1);
                                    //         $discount_amount_less = $discount_amount;
                                    //         $offered_discount_add_x = $offered_discount_cell;
                                    //     }
                                    // }
                                    $discount_percent = 0;
                                    foreach($offered_discount as $row_offered_discount){
                                        $offered_discount_percent = EducOfferedDiscountFeesType::where('offered_discount_id',$offered_discount_id)
                                            ->where('fees_type_id',$fees_type_id)->first();
                                        if($offered_discount_percent!=NULL){
                                            $discount_percent += $row_offered_discount->percent;
                                        }
                                    }
                                    $discount_amount = round((($count_course_code*$amount_course_code->amount)*round(($discount_percent/100),2)),2);
                                    if($discount_amount>=($count_course_code*$amount_course_code->amount)){
                                        $discount_amount = round(($count_course_code*$amount_course_code->amount),2);
                                    }
                                    $discount_balance = round((($count_course_code*$amount_course_code->amount)-$discount_amount),2);
                                    $pdf::SetXY($x+90, $y+$y_add);
                                    $pdf::Cell(45, '', number_format($discount_amount,2), 0, 1, 'R', 0, '', 1);
                                    $pdf::SetXY($x+90+45, $y+$y_add);
                                    $pdf::Cell(45, '', number_format($discount_balance,2), 0, 1, 'R', 0, '', 1);
                                    $total_discount_amount += $discount_amount;
                                    $total_discount_balance += $discount_balance;
                                }
                            }
                        }
                    }
                    $fees_offered = EducOfferedFees::where('school_year_id',$school_year_id)
                        ->where('fees_type_id',$fees_type_id)->get();
                    if($fees_offered->count()>0){
                        $fees_x = 0;
                        $fees_amount = 0;
                        foreach($fees_offered as $row_fees_offered){
                            $fees_x++;                            
                            $program_level = $row_fees_offered->fees->program_level==NULL ? ' - '.ucwords(mb_strtolower($program_level_name)) : '';
                            if($fees_type_id==1){
                                $y_add = $y_add+4;
                                $pdf::SetXY($x+5, $y+$y_add);
                                $pdf::Cell(50, '', $row_fees_offered->fees->name.$program_level.' : '.$total_units.' x '.$row_fees_offered->amount.'/unit', 0, 1, 'L', 0, '', 1);
                                $pdf::SetXY($x+55, $y+$y_add);
                                $pdf::Cell(16, '', number_format(($total_units*$row_fees_offered->amount),2), 0, 1, 'R', 0, '', 1);
                                $fees_amount += round(($total_units*$row_fees_offered->amount),2);
                                $total_assessment += round(($total_units*$row_fees_offered->amount),2);

                                if($offered_discount->count()>0){
                                    $discount_percent = 0;
                                    foreach($offered_discount as $row_offered_discount){
                                        $offered_discount_percent = EducOfferedDiscountFeesType::where('offered_discount_id',$offered_discount_id)
                                            ->where('fees_type_id',$fees_type_id)->first(); 
                                        if($offered_discount_percent!=NULL){
                                            $discount_percent += $row_offered_discount->percent;
                                        }
                                    }
                                    $discount_amount = round((($total_units*$row_fees_offered->amount)*round(($discount_percent/100),2)),2);
                                    if($discount_amount>=($total_units*$row_fees_offered->amount)){
                                        $discount_amount = round(($total_units*$row_fees_offered->amount),2);
                                    }
                                    $discount_balance = round((($total_units*$row_fees_offered->amount)-$discount_amount),2);
                                    $pdf::SetXY($x+90, $y+$y_add);
                                    $pdf::Cell(45, '', number_format($discount_amount,2), 0, 1, 'R', 0, '', 1);
                                    $pdf::SetXY($x+90+45, $y+$y_add);
                                    $pdf::Cell(45, '', number_format($discount_balance,2), 0, 1, 'R', 0, '', 1);
                                    $total_discount_amount += $discount_amount;
                                    $total_discount_balance += $discount_balance;
                                }
                            }elseif($fees_type_id==2){
                            }else{
                                $y_add = $y_add+4;
                                $pdf::SetXY($x+5, $y+$y_add);
                                $pdf::Cell(50, '', $row_fees_offered->fees->name.$program_level, 0, 1, 'L', 0, '', 1);
                                $pdf::SetXY($x+55, $y+$y_add);
                                $pdf::Cell(16, '', number_format(($row_fees_offered->amount),2), 0, 1, 'R', 0, '', 1);
                                $fees_amount += round(($row_fees_offered->amount),2);
                                $total_assessment += round(($row_fees_offered->amount),2);

                                if($offered_discount->count()>0){
                                    $discount_percent = 0;
                                    foreach($offered_discount as $row_offered_discount){
                                        $offered_discount_percent = EducOfferedDiscountFeesType::where('offered_discount_id',$offered_discount_id)
                                            ->where('fees_type_id',$fees_type_id)->first(); 
                                        if($offered_discount_percent!=NULL){
                                            $discount_percent += $row_offered_discount->percent;
                                        }
                                    }
                                    $discount_amount = round(($row_fees_offered->amount*round(($discount_percent/100),2)),2);
                                    if($discount_amount>=$row_fees_offered->amount){
                                        $discount_amount = round($row_fees_offered->amount,2);
                                    }
                                    $discount_balance = round((($row_fees_offered->amount)-$discount_amount),2);
                                    $pdf::SetXY($x+90, $y+$y_add);
                                    $pdf::Cell(45, '', number_format($discount_amount,2), 0, 1, 'R', 0, '', 1);
                                    $pdf::SetXY($x+90+45, $y+$y_add);
                                    $pdf::Cell(45, '', number_format($discount_balance,2), 0, 1, 'R', 0, '', 1);
                                    $total_discount_amount += $discount_amount;
                                    $total_discount_balance += $discount_balance;
                                }
                            }
                            
                            if($fees_x==$fees_offered->count() && $fees_type_id!=2){
                                $pdf::SetXY($x+71, $y+$y_add);
                                $pdf::Cell(16, '', number_format(($fees_amount),2), 0, 1, 'R', 0, '', 1);
                            }
                        }
                    }
                }
            }
            
            $y_add = $y_add+7;
            $pdf::SetXY($x, $y+$y_add);
            $pdf::Cell(65, '', 'Total Assessment:', 0, 1, 'L', 0, '', 1);

            $pdf::SetXY($x+71, $y+$y_add);
            $pdf::Cell(16, '', number_format(($total_assessment),2), 0, 1, 'R', 0, '', 1);

            $pdf::SetXY($x+90, $y+$y_add);
            $pdf::Cell(45, '', number_format($total_discount_amount,2), 0, 1, 'R', 0, '', 1);
            $pdf::SetXY($x+90+45, $y+$y_add);
            $pdf::Cell(45, '', number_format($total_discount_balance,2), 0, 1, 'R', 0, '', 1);
        }
        // $tuition_fees = EducFees::where('program_level_id',$program_level_id)
        //     ->where('type_id',1)
        //     ->get();
        
        // $total_tuition = 0;
        // if($tuition_fees->count()>0){
        //     foreach($tuition_fees as $row){
        //         $y_add = $y_add+4;
        //         $pdf::SetXY($x+5, $y+$y_add);
        //         $pdf::Cell(65, '', $row->name.' - '.ucwords(mb_strtolower($program_level_name)).' : '.$total_units.' x '.$row->amount.'/unit', 0, 1, 'L', 0, '', 1);

        //         $pdf::SetXY($x+70, $y+$y_add);
        //         $pdf::Cell(16, '', number_format(($total_units*$row->amount),2), 0, 1, 'R', 0, '', 1);

        //         $pdf::SetXY($x+90, $y+$y_add);
        //         $pdf::Cell(16, '', number_format(($total_units*$row->amount),2), 0, 1, 'R', 0, '', 1);

        //         $total_tuition+=round(($total_units*$row->amount),2);
        //     }
        // }

        // $y_add = $y_add+8;
        // $pdf::SetXY($x, $y+$y_add);
        // $pdf::Cell(198, '', 'Miscellaneous Fees:', 0, 1, 'L', 0, '', 1);

        // $miscellaneous_fees = EducFees::where(function ($query) use ($program_level_id) {
        //     $query->where('program_level_id',$program_level_id);
        //     $query->orWhere('program_level_id',NULL);
        // })
        // ->where('type_id',2)
        // ->get();
        // $total_miscellaneous = 0;
        // if($miscellaneous_fees->count()>0){
        //     $miscellaneous_count = 0;
        //     foreach($miscellaneous_fees as $row){
        //         $program_level = $row->program_level_id!=NULL ? ' - '.ucwords(mb_strtolower($program_level_name)) : '';

        //         $y_add = $y_add+4;
        //         $pdf::SetXY($x+5, $y+$y_add);
        //         $pdf::Cell(65, '', $row->name.$program_level, 0, 1, 'L', 0, '', 1);

        //         $pdf::SetXY($x+70, $y+$y_add);
        //         $pdf::Cell(16, '', number_format(($row->amount),2), 0, 1, 'R', 0, '', 1);

        //         $total_miscellaneous+=$row->amount;
        //         $miscellaneous_count++;

        //         if($miscellaneous_count==$miscellaneous_fees->count()){
        //             $pdf::SetXY($x+90, $y+$y_add);
        //             $pdf::Cell(16, '', number_format(($total_miscellaneous),2), 0, 1, 'R', 0, '', 1);
        //         }
        //     }
        // }

        // $y_add = $y_add+9;
        // $pdf::SetXY($x, $y+$y_add);
        // $pdf::Cell(65, '', 'Total Assessment:', 0, 1, 'L', 0, '', 1);

        // $pdf::SetXY($x+90, $y+$y_add);
        // $pdf::Cell(16, '', number_format(($total_tuition+$total_miscellaneous),2), 0, 1, 'R', 0, '', 1);

        $y_add = 189;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(198, '', '', 'T', 1, 'L', 0, '', 1);

        $y_add = 190;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::SetFont('times','b',9);
        $pdf::Cell(198, '', 'SCHEDULE OF PAYMENTS:', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+4;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::SetFont('times','',9);
        $pdf::Cell(30, '', 'Upon Enrollment', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+30, $y+$y_add);
        $pdf::Cell(25, '', 'Amount', 0, 1, 'R', 0, '', 1);

        $y_add = $y_add+4;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(30, '', 'Balance Payable', 0, 1, 'L', 0, '', 1);

        $pdf::SetXY($x+30, $y+$y_add);
        $pdf::Cell(25, '', 'Amount', 0, 1, 'R', 0, '', 1);

        $y_add = $y_add+11;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(198, '', '', 'T', 1, 'L', 0, '', 1);

        $y_add = $y_add+1;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::SetFont('times','b',9);
        $pdf::Cell(198, '', 'SCHOOL POLICY FOR WITHDRAWAL:', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+4;
        $pdf::SetXY($x+10, $y+$y_add);
        $pdf::SetFont('times','',8);
        $pdf::Cell(198, '', 'Students who wish to withdraw their enrollment should officially notify the Registrar and their instructors. They will still have to pay the school', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+3;
        $pdf::SetXY($x+10, $y+$y_add);
        $pdf::Cell(198, '', 'fees according to the following schedule:', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+3;
        $pdf::SetXY($x+10, $y+$y_add);
        $pdf::Cell(198, '', '30% of the fees - Within one week from the opening of classes whether or not they have attended classes', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+3;
        $pdf::SetXY($x+10, $y+$y_add);
        $pdf::Cell(198, '', '50% of the fees - Within the second week', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+3;
        $pdf::SetXY($x+10, $y+$y_add);
        $pdf::Cell(198, '', '70% of the fees - Within the third week', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+3;
        $pdf::SetXY($x+10, $y+$y_add);
        $pdf::Cell(198, '', 'Full payment - From the fourth week onward', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+4;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::Cell(198, 15, '', 'TB', 1, 'L', 0, '', 1);

        $y_add = $y_add+1;
        $pdf::SetXY($x, $y+$y_add);
        $pdf::SetFont('times','',9);
        $pdf::Cell(198, '', 'NOTE: This is a system generated report.', 0, 1, 'L', 0, '', 1);

        $y_add = $y_add+6;
        $pdf::SetXY($x+110, $y+$y_add);
        $pdf::SetFont('times','',8);
        $pdf::Cell(80, '', 'Processed by: '.$name_user, 0, 1, 'C', 0, '', 1);

        $y_add = $y_add+4;
        $pdf::SetXY($x+120, $y+$y_add);
        $pdf::Cell(70, '', date('m/d/Y h:i A'), 0, 1, 'C', 0, '', 1);
        

        $pathUser = 'storage\rims\students/'.$student->student_info->id_no.'\enrollment_form/'.$school_year.'_'.$school_year_period.'_'.$enrollment_form_no.'.pdf';
        $pdf::Output(public_path($pathUser),'F');

        return $pathUser;
    }
}