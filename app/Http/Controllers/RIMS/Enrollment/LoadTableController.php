<?php

namespace App\Http\Controllers\RIMS\Enrollment;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedSchedule;
use App\Models\StudentsCourses;
use App\Models\Users;
use App\Services\NameServices;
use Illuminate\Http\Request;

class LoadTableController extends Controller
{
    public function enrollmentTable(Request $request){
        $data = array();
        $id = $request->id;
        $query = EducOfferedPrograms::where('school_year_id',$id)->orderBy('name')->get()
                    ->map(function($query) {
                        $offered_curriculum_ids = EducOfferedCurriculum::where('offered_program_id',$query->id)
                                    ->pluck('id')->toArray();
                        $offered_course_ids = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                                    ->pluck('id')->toArray();
                        $student_ids = StudentsCourses::whereIn('offered_course_id',$offered_course_ids)
                                    ->pluck('user_id')->toArray();
                        $students = Users::whereIn('id',$student_ids)
                                    ->pluck('id')->toArray();
                        $count = count($students);
                        return [
                            'id' => $query->id,
                            'department' => $query->department->shorten,
                            'program' => '('.$query->name.') '.$query->program->shorten,
                            'count' => $count
                        ];
                    })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['department'];
                $data_list['f3'] = $r['program'];
                $data_list['f4'] = $r['count'];
                $data_list['f5'] = '<button class="btn btn-primary btn-primary-scan sectionViewModal"
                                        data-id="'.$r['id'].'"
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    public function courseAnotherTable(Request $request){
        $name_services = new NameServices;
        $data = array();
        $id = $request->id;
        $course = EducOfferedCourses::where('id',$id)->first();
        $school_year_id = $course->curriculum->offered_program->school_year_id;
        $offered_course_ids_not = EducOfferedCourses::where('offered_curriculum_id',$course->offered_curriculum_id)
                                ->where('section',$course->section)
                                ->pluck('id')->toArray();
        $offered_program_ids = EducOfferedPrograms::where('school_year_id',$school_year_id)
                                ->pluck('id')->toArray();
        $offered_curriculum_ids = EducOfferedCurriculum::where('offered_program_id',$offered_program_ids)                                
                                ->pluck('id')->toArray();
        $offered_course_ids = EducOfferedCourses::whereIn('offered_curriculum_id',$offered_curriculum_ids)
                                ->whereNotIn('id',$offered_course_ids_not)
                                ->where('year_level',$course->year_level)
                                ->where('status_id',1)
                                ->whereHas('course', function ($query) use ($course) {
                                    $query->where('name','LIKE','%'.$course->course->name.'%');
                                })
                                ->pluck('id')->toArray();
        $offered_course_idss = array();
        $offered_course_ids_list = EducOfferedCourses::where('offered_curriculum_id',$course->offered_curriculum_id)
                                ->where('section',$course->section)
                                ->where('course_id',$course->course_id)
                                ->get();
        if(count($offered_course_ids_list)>0){
            foreach($offered_course_ids_list as $list){
                if(count($list->schedule)>0){
                    foreach($list->schedule as $row){
                        foreach($row->days as $day){
                            $days[] = $day->day;
                        }
                        $offered_schedule_ids = EducOfferedSchedule::whereIn('offered_course_id',$offered_course_ids)
                                            ->where(function ($query) use ($row) {
                                                $query->where(function ($query) use ($row) {
                                                    $query->where('time_from','>=',$row->time_from)
                                                    ->where('time_to','<=',$row->time_from);
                                                });
                                                $query->orWhere(function ($query) use ($row) {
                                                    $query->where('time_from','<=',$row->time_from)
                                                    ->where('time_to','>',$row->time_from);
                                                });
                                                $query->orWhere(function ($query) use ($row) {
                                                    $query->where('time_from','<',$row->time_to)
                                                    ->where('time_to','>=',$row->time_to);
                                                });
                                                $query->orWhere(function ($query) use ($row) {
                                                    $query->where('time_from','>=',$row->time_from)
                                                    ->where('time_to','<=',$row->time_to);
                                                });
                                            })
                                            ->whereHas('days', function ($query) use ($days) {
                                                $query->whereIn('day', $days);
                                            })
                                            ->pluck('offered_course_id')->toArray();
                        $offered_schedule_ids_open = EducOfferedCourses::whereIn('id',$offered_course_ids)
                                            ->whereNotIn('id',$offered_schedule_ids)
                                            ->where('year_level',$course->year_level)
                                            ->whereHas('course', function ($query) use ($course) {
                                                $query->where('name','LIKE','%'.$course->course->name.'%');
                                            })
                                            ->whereHas('schedule', function ($query) use ($course) {
                                                $query->where('id','<>',NULL);
                                            })
                                            ->pluck('id')->toArray();
                        $offered_course_idss = array_merge($offered_course_idss,$offered_schedule_ids_open);
                    }
                }
            }
        }
        $query = EducOfferedCourses::whereIn('id',$offered_course_idss)->get()
                        ->map(function($query) use ($name_services) {
                            $schedule = 'TBA';
                            $room = 'TBA';
                            $instructor = 'TBA';
                            if($query->instructor_id!=NULL){
                                $instructor = $name_services->firstname($query->instructor->lastname,$query->instructor->firstname,$query->instructor->middlename,$query->instructor->extname);
                            }
                            if(count($query->schedule)>0){
                                foreach($query->schedule as $row){
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
                            return [
                                'id' => $query->id,
                                'program' => $query->curriculum->offered_program->name.'-'.$query->curriculum->offered_program->program->shorten,
                                'code' => $query->code,
                                'name' => $query->course->name,
                                'units' => $query->course->units,
                                'section' => $query->section_code,
                                'schedule' => $schedule,
                                'room' => $room,
                                'instructor' => $instructor
                            ];
                        })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['program'];
                $data_list['f3'] = $r['section'];
                $data_list['f4'] = $r['code'];
                $data_list['f5'] = $r['name'];
                $data_list['f6'] = $r['units'];
                $data_list['f7'] = $r['schedule'];
                $data_list['f8'] = $r['room'];
                $data_list['f9'] = $r['instructor'];
                $data_list['f10'] = '<input type="radio" class="form-control anotherCourseSelected" value="'.$r['id'].'">';
                array_push($data,$data_list);
                $x++;
            }
        }        
        return  response()->json($data);
    }
}

?>