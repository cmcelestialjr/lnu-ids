<?php

namespace App\Http\Controllers\RIMS\Schedule;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use App\Services\NameServices;
use Illuminate\Http\Request;

class LoadTableController extends Controller
{
    public function searchTable(Request $request){
        $data = $this->search($request);
        return  response()->json($data);
    }    
    public function viewTable(Request $request){
        $name_services = new NameServices;
        $data = array();        
        $school_year = $request->school_year;
        $branch = $request->branch;
        $program = $request->program;
        $query = EducOfferedCourses::with('schedule.days',
                                        'schedule.room',
                                        'instructor',
                                        'curriculum.offered_program.program',
                                        'curriculum.curriculum',
                                        'course.grade_level')
                    ->whereHas('curriculum', function ($subQuery) use ($school_year,$program,$branch) {
                        $subQuery->whereHas('offered_program', function ($subQuery) use ($school_year,$program,$branch) {
                            $subQuery->where('branch_id', $branch);
                            $subQuery->where('program_id', $program);
                            $subQuery->where('school_year_id', $school_year);
                        });
                    })
                    ->orderBy('year_level','ASC')
                    ->orderBy('section_code','DESC')
                    ->orderBy('id','DESC') 
                    ->get()
                    ->sortBy('curriculum.offered_program.program.shorten')
                    ->sortBy('curriculum.offered_program.name')
                    ->map(function($query) use ($name_services) {
                        $schedule = '<u>TBA</u>';
                        $room = '<u>TBA</u>';
                        $instructor = 'TBA';
                        if($query->instructor_id!=NULL){
                            $instructor = $name_services->lastname($query->instructor->lastname,$query->instructor->firstname,$query->instructor->middlename,$query->instructor->extname);
                        }
                        if(count($query->schedule)>0){
                            foreach($query->schedule as $row){   
                                $days = array();
                                foreach($row->days as $day){
                                    $days[] = $day->day;
                                }
                                $days1 = implode('',$days);
                                $schedules[] = '<u>'.date('h:ia',strtotime($row->time_from)).'-'.
                                                    date('h:ia',strtotime($row->time_to)).' '.$days1.'</u>';
                                    if($row->room_id==NULL){
                                        $rooms[] = '<u>TBA</u>';
                                    }else{
                                        $rooms[] = '<u>'.$row->room->name.'</u>';
                                    }                                
                            }
                            $schedule = implode('<br>',$schedules);
                            $room = implode('<br>',$rooms);
                        }
                        return [
                            'id' => $query->id,
                            'program' => $query->curriculum->offered_program->name.'-'.$query->curriculum->offered_program->program->shorten,
                            'curriculum' => $query->curriculum->curriculum->year_from.'-'.$query->curriculum->curriculum->year_to.' ('.$query->curriculum->curriculum->code.')',
                            'section_code' => $query->section_code,
                            'course_code' => $query->code,
                            'schedule' => $schedule,
                            'room' => $room,
                            'instructor' => $instructor,
                            'grade_level' => $query->course->grade_level->name
                        ];
                    })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $id = $r['id'];
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['curriculum'];
                $data_list['f3'] = $r['section_code'];
                $data_list['f4'] = $r['course_code'];
                $data_list['f5'] = $r['grade_level'];                
                $data_list['f6'] = '<span id="courseSchedule'.$id.'">'.$r['schedule'].'</span>';
                $data_list['f7'] = '<span id="courseRoom'.$id.'">'.$r['room'].'</span>';
                $data_list['f8'] = '<span id="courseInstructor'.$id.'">'.$r['instructor'].'</span>';
                $data_list['f9'] = '<button class="btn btn-primary btn-primary-scan scheduleCourseModal"
                                        data-id="'.$id.'">
                                        <span class="fa fa-calendar"></span> Sched
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return $data;
    }
    public function schedWoTable(Request $request){
        $name_services = new NameServices;
        $data = array();
        $option = $request->option;
        $school_year = $request->school_year;
        $query = EducOfferedCourses::with('schedule.days',
                                          'schedule.room',
                                          'instructor',
                                          'curriculum.offered_program.program',
                                          'curriculum.curriculum',
                                          'course.grade_level')
                    ->whereHas('curriculum', function ($subQuery) use ($school_year,) {
                        $subQuery->whereHas('offered_program', function ($subQuery) use ($school_year) {
                            $subQuery->where('school_year_id', $school_year);
                        });
                    })
                    ->where(function ($subQuery) use ($option) {
                        if($option!=''){
                            foreach($option as $opt){
                                if($opt=='schedule'){
                                    $subQuery->orwhereDoesntHave('schedule');
                                }
                                if($opt=='instructor'){
                                    $subQuery->orWhere('instructor_id',NULL);
                                }
                                if($opt=='room'){
                                    $subQuery->orwhereDoesntHave('schedule');
                                    $subQuery->orWhereHas('schedule', function($query){
                                        $query->where('room_id',NULL);
                                    });
                                }
                            }
                        }else{
                            $subQuery->orwhereDoesntHave('schedule');
                            $subQuery->orWhere('instructor_id',NULL);
                            $subQuery->orWhereHas('schedule', function($query){
                                        $query->where('room_id',NULL);
                                    });
                        }         
                    })->orderBy('section_code')
                    ->get()
                    ->sortBy('curriculum.offered_program.program.shorten')
                    ->sortBy('curriculum.offered_program.name')
                    ->map(function($query) use ($name_services) {
                        $schedule = '<u>TBA</u>';
                        $room = '<u>TBA</u>';
                        $instructor = 'TBA';
                        if($query->instructor_id!=NULL){
                            $instructor = $name_services->lastname($query->instructor->lastname,$query->instructor->firstname,$query->instructor->middlename,$query->instructor->extname);
                        }
                        if(count($query->schedule)>0){
                            foreach($query->schedule as $row){   
                                $days = array();                             
                                foreach($row->days as $day){
                                    $days[] = $day->day;
                                }
                                $days1 = implode('',$days);
                                $schedules[] = '<u>'.date('h:ia',strtotime($row->time_from)).'-'.
                                                    date('h:ia',strtotime($row->time_to)).' '.$days1.'</u>';
                                    if($row->room_id==NULL){
                                        $rooms[] = '<u>TBA</u>';
                                    }else{
                                        $rooms[] = '<u>'.$row->room->name.'</u>';
                                    }                                
                            }
                            $schedule = implode('<br>',$schedules);
                            $room = implode('<br>',$rooms);
                        }
                        return [
                            'id' => $query->id,
                            'program' => $query->curriculum->offered_program->name.'-'.$query->curriculum->offered_program->program->shorten,
                            'curriculum' => $query->curriculum->curriculum->year_from.'-'.$query->curriculum->curriculum->year_to.' ('.$query->curriculum->curriculum->code.')',
                            'section_code' => $query->section_code,
                            'course_code' => $query->code,
                            'schedule' => $schedule,
                            'room' => $room,
                            'instructor' => $instructor,
                            'grade_level' => $query->course->grade_level->name
                        ];
                    })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['program'];
                $data_list['f3'] = $r['curriculum'];
                $data_list['f4'] = $r['section_code'];
                $data_list['f5'] = $r['course_code'];
                $data_list['f6'] = $r['grade_level'];                
                $data_list['f7'] = '<span id="courseSchedule'.$x.'">'.$r['schedule'].'</span>';
                $data_list['f8'] = '<span id="courseRoom'.$x.'">'.$r['room'].'</span>';
                $data_list['f9'] = '<span id="courseInstructor'.$x.'">'.$r['instructor'].'</span>';
                $data_list['f10'] = '<button class="btn btn-primary btn-primary-scan scheduleCourseModal"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-calendar"></span> Sched
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return $data;
    }    
    private function search($request){
        $name_services = new NameServices;
        $data = array();
        $option = $request->option;
        $school_year = $request->school_year;
        $option_select = $request->option_select;
        if($option=='course_code'){
            $query = $this->courseCode($option_select);
        }elseif($option=='course_desc'){
            $query = $this->courseDesc($option_select);
        }elseif($option=='section_code'){
            $query = $this->sectionCode($option_select);
        }elseif($option=='instructor'){
            $query = $this->instructor($option_select);
        }elseif($option=='room'){
            $query = $this->room($option_select);
        }
        $query = $query
                    ->whereHas('curriculum.offered_program', function ($subQuery) use ($school_year) {
                            $subQuery->where('school_year_id', $school_year);
                    })
                    ->with('schedule.days',
                            'schedule.room',
                            'instructor',
                            'curriculum.offered_program.program',
                            'curriculum.curriculum',
                            'course.grade_level')
                    ->get()
                    ->sortBy('curriculum.offered_program.program.shorten')
                    ->sortBy('curriculum.offered_program.name')
                    ->map(function($query) use ($name_services) {
                        $schedule = '<u>TBA</u>';
                        $room = '<u>TBA</u>';
                        $instructor = 'TBA';
                        if($query->instructor_id!=NULL){
                            $instructor = $name_services->lastname($query->instructor->lastname,$query->instructor->firstname,$query->instructor->middlename,$query->instructor->extname);
                        }
                        if(count($query->schedule)>0){
                            foreach($query->schedule as $row){   
                                $days = array();                             
                                foreach($row->days as $day){
                                    $days[] = $day->day;
                                }
                                $days1 = implode('',$days);
                                $schedules[] = '<u>'.date('h:ia',strtotime($row->time_from)).'-'.
                                                    date('h:ia',strtotime($row->time_to)).' '.$days1.'</u>';
                                    if($row->room_id==NULL){
                                        $rooms[] = '<u>TBA</u>';
                                    }else{
                                        $rooms[] = '<u>'.$row->room->name.'</u>';
                                    }                                
                            }
                            $schedule = implode('<br>',$schedules);
                            $room = implode('<br>',$rooms);
                        }
                        return [
                            'id' => $query->id,
                            'program' => $query->curriculum->offered_program->name.'-'.$query->curriculum->offered_program->program->shorten,
                            'curriculum' => $query->curriculum->curriculum->year_from.'-'.$query->curriculum->curriculum->year_to.' ('.$query->curriculum->curriculum->code.')',
                            'section_code' => $query->section_code,
                            'course_code' => $query->code,
                            'schedule' => $schedule,
                            'room' => $room,
                            'instructor' => $instructor,
                            'course_desc' => $query->course->name
                        ];
                    })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['program'];
                $data_list['f3'] = $r['curriculum'];
                $data_list['f4'] = $r['section_code'];
                $data_list['f5'] = $r['course_code'];
                $data_list['f6'] = $r['course_desc'];
                $data_list['f7'] = '<span id="courseSchedule'.$x.'">'.$r['schedule'].'</span>';
                $data_list['f8'] = '<span id="courseRoom'.$x.'">'.$r['room'].'</span>';
                $data_list['f9'] = '<span id="courseInstructor'.$x.'">'.$r['instructor'].'</span>';
                $data_list['f10'] = '<button class="btn btn-primary btn-primary-scan scheduleCourseModal"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-calendar"></span> Sched
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return $data;
    }
    private function courseCode($option_select){
        $query = EducOfferedCourses::where('code', $option_select)
                    ->orderBy('code');
        return $query;
    }    
    private function courseDesc($option_select){
        $query = EducOfferedCourses::whereHas('course', function($query) use ($option_select){
                        $query->where('name', $option_select);
                    })
                    ->orderBy('code');
                    return $query;
    }
    private function sectionCode($option_select){
        $query = EducOfferedCourses::where('section_code', $option_select)
                    ->orderBy('section_code');
        return $query;
    }
    private function instructor($option_select){
        $query = EducOfferedCourses::where('instructor_id', $option_select)
                    ->where('instructor_id','<>',NULL)
                    ->orderBy('section_code');
        return $query;
    }
    private function room($option_select){
        $query = EducOfferedCourses::whereHas('schedule', function($query) use ($option_select){
                        $query->where('room_id', $option_select);
                    })
                    ->orderBy('section_code');
        return $query;
    }    
}

?>