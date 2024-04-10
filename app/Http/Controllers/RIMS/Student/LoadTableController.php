<?php

namespace App\Http\Controllers\RIMS\Student;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedSchoolYear;
use App\Models\StudentsCourses;
use App\Models\StudentsInfo;
use App\Models\StudentsProgram;
use Illuminate\Http\Request;
use App\Services\NameServices;

class LoadTableController extends Controller
{
    public function studentTable(Request $request){
        $name_services = new NameServices;
        $data = array();
        $option = $request->option;        
        $query = StudentsInfo::with('info', 'program.program_level', 'grade_level')->orderBy('grade_level_id','DESC');
        if($request->level!=''){
            foreach($request->level as $row){
                $level[] = $row;
            }
            $query = $query->whereHas('program', function ($query) use ($level) {
                        $query->whereIn('program_level_id', $level);
                    });
        }
        if($option=='Graduated'){
            $year_graduate = $request->year_graduate;
            $query = $query->whereHas('student_program', function ($query) use ($year_graduate) {
                        $query->whereYear('date_graduate', $year_graduate);
                        $query->where('date_graduate','<>',NULL);
                    });
        }else{
            $school_year_id = $request->school_year;
            if($option=='unenrolled'){
                // $school_year = EducOfferedSchoolYear::where('id',$school_year_id)->first();
                // $school_year = $school_year->year_from;
                $query = $query->whereDoesntHave('courses', function ($query) use ($school_year_id) {
                    $query->where('school_year_id',$school_year_id);
                    $query->where('school_year_id',NULL);
                })->whereIn('student_status_id',[1,2,3,4]);
                // $query = $query->whereHas('student_program', function ($query) use ($school_year) {
                //     $query->whereYear('date_admitted',$school_year);
                // });
            }elseif($option=='Graduating'){
                $query = $query->whereHas('student_program', function ($query) {
                        $query->where('date_graduate', NULL);
                    })->whereHas('courses', function ($query) use ($school_year_id) {
                        $query->where('school_year_id', $school_year_id);
                    });
            }else{
                $query = $query->whereHas('courses', function ($query) use ($school_year_id) {
                            $query->where('school_year_id', $school_year_id);
                            $query->where('school_year_id','!=',NULL);
                        });
            }
        }
        $query = $query->get()
                    ->map(function($query) use ($name_services) {
                        $name = $name_services->lastname($query->info->lastname,$query->info->firstname,$query->info->middlename,$query->info->extname);
                        return [
                            'id' => $query->user_id,
                            'program_level' => $query->program->program_level->name,                            
                            'program' => $query->program->name.' ('.$query->program->shorten.')',
                            'name' => $name,
                            'id_no' => $query->id_no,
                            'level' => $query->grade_level->name
                        ];
                    })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;                
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['id_no'];
                $data_list['f4'] = $r['program_level'];
                $data_list['f5'] = $r['program'];
                $data_list['f6'] = $r['level'];
                $data_list['f7'] = '<button class="btn btn-primary btn-primary-scan studentView"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }  
    public function studentSchoolYearTable(Request $request){
        $data = array();
        $id = $request->id;
        $query = EducOfferedSchoolYear::with('grade_period',
                        'student_courses.program.program_level',
                        'student_courses.program.program_info',
                        'student_courses.grade_level')
            ->whereHas('student_courses', function ($query) use ($id) {
                $query->where('user_id', $id);
            })
            ->orderBy('year_from','DESC')
            ->get()
            ->map(function($query) use ($id) {
                $course_count = count($query->student_courses);
                foreach($query->student_courses as $courses){
                    $program_level = $courses->program->program_level->name;
                    $program_info_name = $courses->program->program_info->name;
                    $program_info_shorten = $courses->program->program_info->shorten;
                    $grade_level = $courses->grade_level->name;
                }
                return [
                    'id' => $query->id,
                    'school_year' => $query->year_from.'-'.$query->year_to.' ('.$query->grade_period->name.')',                            
                    'program_level' => $program_level,
                    'program' => $program_info_name.' ('.$program_info_shorten.')',
                    'grade_level' => $grade_level,
                    'course_count' => $course_count
                ];
            })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['school_year'];
                $data_list['f3'] = $r['program_level'];
                $data_list['f4'] = $r['program'];
                $data_list['f5'] = $r['grade_level'];
                $data_list['f6'] = '<button class="btn btn-primary btn-primary-scan studentCoursesModal"
                                        data-id="'.$r['id'].'"
                                        <span class="fa fa-eye"></span> '.$r['course_count'].'
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    public function studentCoursesTable(Request $request){
        $name_services = new NameServices;
        $data = array();
        $id = $request->id;
        $school_year_id = $request->school_year_id;
        $query = StudentsCourses::with('course.instructor',
                                    'course.schedule.room',
                                    'course.schedule.days',
                                    'course.curriculum.curriculum',                                    
                                    'course.course.grade_level',
                                    'course.curriculum.offered_program.program',
                                    'status')
                        ->where('user_id',$id)
                        ->where('school_year_id',$school_year_id)
                        ->get()
                        ->map(function($query) use ($name_services) {
                            $schedule = 'TBA';
                            $room = 'TBA';
                            $instructor = 'TBA';
                            if($query->instructor_id!=NULL){
                                $instructor = $name_services->firstname($query->course->instructor->lastname,
                                            $query->course->instructor->firstname,
                                            $query->course->instructor->middlename,
                                            $query->course->instructor->extname);
                            }
                            if(count($query->course->schedule)>0){
                                foreach($query->course->schedule as $row){
                                    $days = array();
                                    if($row->room_id==NULL){
                                        $rm = 'TBA';
                                    }else{
                                        $rm = $row->room->name;
                                    }
                                    foreach($row->days as $day){
                                        $days[] = $day->day;
                                    }
                                    $days1 = implode('',$days);
                                    $schedules[] = date('h:ia',strtotime($row->time_from)).'-'.
                                                        date('h:ia',strtotime($row->time_to)).' '.$days1;
                                    $rooms[] = $rm;
                                }
                                $schedule = implode('<br>',$schedules);
                                $room = implode('<br>',$rooms);
                            }
                            if($query->status->name==NULL){
                                $status = 'NG';
                            }else{
                                $status = $query->status->name;
                            }
                            return [
                                'id' => $query->id,
                                'program' => $query->course->curriculum->offered_program->name.'-'.
                                            $query->course->curriculum->offered_program->program->shorten,
                                'code' => $query->course->code,
                                'grade_level' => $query->course->course->grade_level->name,
                                'units' => $query->course->course->units,
                                'lab' => $query->course->course->lab,
                                'section' => $query->course->section,
                                'schedule' => $schedule,
                                'room' => $room,
                                'instructor' => $instructor,
                                'curriculum' => $query->course->curriculum->curriculum->year_from.'-'.
                                                $query->course->curriculum->curriculum->year_to.' ('.
                                                $query->course->curriculum->code.')',
                                'status' => $status
                            ];
                        })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['program'];
                $data_list['f3'] = $r['curriculum'];
                $data_list['f4'] = $r['code'];
                $data_list['f5'] = $r['grade_level'];
                $data_list['f6'] = $r['section'];
                $data_list['f7'] = $r['units'];
                $data_list['f8'] = $r['lab'];
                $data_list['f9'] = $r['schedule'];
                $data_list['f10'] = $r['room'];
                $data_list['f11'] = $r['instructor'];
                $data_list['f12'] = $r['status'];
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
}

?>