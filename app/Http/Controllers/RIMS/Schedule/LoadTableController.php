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
        $program = $request->program;
        $school_year = $request->school_year;
        $offered_program_ids = EducOfferedPrograms::where('school_year_id',$school_year)
                                ->where('id',$program)->pluck('id')->toArray();
        $offered_curriculum_ids = EducOfferedCurriculum::whereIn('offered_program_id',$offered_program_ids)
                                ->pluck('id')->toArray();
        $query = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                    ->orderBy('section_code')
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
                $data_list['f2'] = $r['curriculum'];
                $data_list['f3'] = $r['section_code'];
                $data_list['f4'] = $r['course_code'];
                $data_list['f5'] = $r['grade_level'];                
                $data_list['f6'] = '<span id="searchCourseSched'.$x.'">'.$r['schedule'].'</span>';
                $data_list['f7'] = '<span id="searchCourseRoom'.$x.'">'.$r['room'].'</span>';
                $data_list['f8'] = '<span id="searchCourseInstructor'.$x.'">'.$r['instructor'].'</span>';
                $data_list['f9'] = '<button class="btn btn-primary btn-primary-scan searchCourseSched"
                                        data-id="'.$r['id'].'">
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
        $offered_program_ids = EducOfferedPrograms::where('school_year_id',$school_year)->pluck('id')->toArray();
        $offered_curriculum_ids = EducOfferedCurriculum::whereIn('offered_program_id',$offered_program_ids)
                    ->pluck('id')->toArray();
        $schedule_ids = array();
        $instructor_ids = array();
        $room_ids = array();
        if($option!=''){
            foreach($option as $opt){
                if($opt=='schedule'){
                    $schedule_ids = $this->schedule_wo($offered_curriculum_ids);
                }
                if($opt=='instructor'){
                    $instructor_ids = $this->instructor_wo($offered_curriculum_ids);
                }
                if($opt=='room'){
                    $schedule_ids1 = $this->schedule_wo($offered_curriculum_ids);
                    $room_ids = array_merge($this->room_wo($offered_curriculum_ids),$schedule_ids1);
                }
            }
        }else{
            $schedule_ids = $this->schedule_wo($offered_curriculum_ids);
            $instructor_ids = $this->instructor_wo($offered_curriculum_ids);
            $room_ids = $this->room_wo($offered_curriculum_ids);
        }
        $offered_courses_ids = array_merge($schedule_ids, $instructor_ids, $room_ids);
        $query = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                    ->whereIn('id',$offered_courses_ids)
                    ->orderBy('section_code')
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
                $data_list['f7'] = '<span id="searchCourseSched'.$x.'">'.$r['schedule'].'</span>';
                $data_list['f8'] = '<span id="searchCourseRoom'.$x.'">'.$r['room'].'</span>';
                $data_list['f9'] = '<span id="searchCourseInstructor'.$x.'">'.$r['instructor'].'</span>';
                $data_list['f10'] = '<button class="btn btn-primary btn-primary-scan searchCourseSched"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-calendar"></span> Sched
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return $data;
    }
    private function schedule_wo($offered_curriculum_ids){
        return EducOfferedCourses::doesntHave('schedule')
                ->whereIn('offered_curriculum_id',$offered_curriculum_ids)
                ->pluck('id')->toArray();
    }
    private function instructor_wo($offered_curriculum_ids){
        return EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                ->where('instructor_id',NULL)
                ->pluck('id')->toArray();
    }
    private function room_wo($offered_curriculum_ids){
        return EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                ->whereHas('schedule', function($query){
                    $query->where('room_id',NULL);
                })
                ->pluck('id')->toArray();
    }
    private function search($request){
        $name_services = new NameServices;
        $data = array();
        $option = $request->option;
        $school_year = $request->school_year;
        $option_select = $request->option_select;
        $offered_program_ids = EducOfferedPrograms::where('school_year_id',$school_year)->pluck('id')->toArray();
        $offered_curriculum_ids = EducOfferedCurriculum::whereIn('offered_program_id',$offered_program_ids)
                    ->pluck('id')->toArray();
        if($option=='course_code'){
            $query = $this->courseCode($offered_curriculum_ids,$option_select);
        }elseif($option=='course_desc'){
            $query = $this->courseDesc($offered_curriculum_ids,$option_select);
        }elseif($option=='section_code'){
            $query = $this->sectionCode($offered_curriculum_ids,$option_select);
        }elseif($option=='instructor'){
            $query = $this->instructor($offered_curriculum_ids,$option_select);
        }elseif($option=='room'){
            $query = $this->room($offered_curriculum_ids,$option_select);
        }
        $query = $query->get()
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
                $data_list['f7'] = '<span id="searchCourseSched'.$x.'">'.$r['schedule'].'</span>';
                $data_list['f8'] = '<span id="searchCourseRoom'.$x.'">'.$r['room'].'</span>';
                $data_list['f9'] = '<span id="searchCourseInstructor'.$x.'">'.$r['instructor'].'</span>';
                $data_list['f10'] = '<button class="btn btn-primary btn-primary-scan searchCourseSched"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-calendar"></span> Sched
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return $data;
    }
    private function courseCode($offered_curriculum_ids,$option_select){
        $query = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                    ->where('code', $option_select)
                    ->orderBy('code');
        return $query;
    }
    
    private function courseDesc($offered_curriculum_ids,$option_select){
        $query = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                    ->whereHas('course', function($query) use ($option_select){
                        $query->where('name', $option_select);
                    })
                    ->orderBy('code');
                    return $query;
    }
    private function sectionCode($offered_curriculum_ids,$option_select){
        $query = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                    ->where('section_code', $option_select)
                    ->orderBy('section_code');
        return $query;
    }
    private function instructor($offered_curriculum_ids,$option_select){
        $query = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                    ->where('instructor_id', $option_select)
                    ->where('instructor_id','<>',NULL)
                    ->orderBy('section_code');
        return $query;
    }
    private function room($offered_curriculum_ids,$option_select){
        $query = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                    ->whereHas('schedule', function($query) use ($option_select){
                        $query->where('room_id', $option_select);
                    })
                    ->orderBy('section_code');
        return $query;
    }    
}

?>