<?php

namespace App\Http\Controllers\RIMS\Sections;
use App\Http\Controllers\Controller;
use App\Models\EducOfferedCourses;
use App\Models\EducOfferedCurriculum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EducOfferedPrograms;
use App\Models\EducOfferedSchedule;
use App\Models\EducOfferedScheduleDay;
use App\Services\NameServices;
use DateInterval;
use DatePeriod;
use DateTime;

class LoadTableController extends Controller
{
    public function viewTable(Request $request){
        $data = array();
        $id = $request->id;
        $program_id = $request->program_id;
        $offered_curriculum_ids = EducOfferedCurriculum::where('offered_program_id',$program_id)->pluck('id')->toArray();
        $query = EducOfferedCourses::with('curriculum.curriculum')
                    ->select('year_level','offered_curriculum_id')
                    ->whereIn('offered_curriculum_id',$offered_curriculum_ids)
                    ->groupBy('year_level')->groupBy('offered_curriculum_id')
                    ->orderBy('offered_curriculum_id')->orderBy('year_level')
                    ->get()
                    ->map(function($query) {
                        $courses = EducOfferedCourses::with('course.grade_level')
                                    ->where('year_level',$query->year_level)
                                    ->where('offered_curriculum_id',$query->offered_curriculum_id)->first();
                        $get = EducOfferedCourses::select('section')
                                    ->where('year_level',$query->year_level)
                                    ->where('offered_curriculum_id',$query->offered_curriculum_id)
                                    ->groupBy('section')->get();
                        $count = $get->count();
                        return [
                            'offered_curriculum_id' => $query->offered_curriculum_id,
                            'year_level' => $query->year_level,
                            'grade_level' => $courses->course->grade_level->name,
                            'section' => $count,
                            'curriculum' => $query->curriculum->curriculum->year_from.' - '.$query->curriculum->curriculum->year_to.' ('.$query->curriculum->code.')'
                        ];
                    })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['curriculum'];
                $data_list['f3'] = $r['grade_level'];
                $data_list['f4'] = $r['section'];
                $data_list['f5'] = '<button class="btn btn-primary btn-primary-scan sectionViewModal"
                                        data-id="'.$r['offered_curriculum_id'].'"
                                        data-level="'.$r['year_level'].'">
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    public function sectionViewTable(Request $request){
        $data = array();
        $id = $request->id;
        $level = $request->level;
        $query = EducOfferedCourses::select('year_level','offered_curriculum_id','section')
                    ->where('offered_curriculum_id',$id)
                    ->where('year_level',$level)
                    ->groupBy('section')->groupBy('year_level')->groupBy('offered_curriculum_id')
                    ->orderBy('section')
                    ->get()
                    ->map(function($query) {
                        $courses = EducOfferedCourses::with('course','curriculum.curriculum')
                                    ->where('year_level',$query->year_level)
                                    ->where('offered_curriculum_id',$query->offered_curriculum_id)
                                    ->where('section',$query->section)->first();
                        return [
                            'grade_level' => $courses->course->grade_level->name,
                            'section' => $courses->section,
                            'section_code' => $courses->section_code,
                            'curriculum' => $courses->curriculum->curriculum->year_from.' - '.$courses->curriculum->curriculum->year_to,
                            'id' => $courses->id
                        ];
                    })->toArray();
        if(count($query)>0){
            $x = 1;            
            foreach($query as $r){
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['section'];
                $data_list['f3'] = $r['section_code'];
                $data_list['f4'] = '<button class="btn btn-primary btn-primary-scan courseViewModal"
                                        data-id="'.$r['id'].'">
                                        <span class="fa fa-eye"></span> View
                                    </button>';
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }
    public function courseViewTable(Request $request){
        $user_access_level = $request->session()->get('user_access_level');
        $data = array();
        $name_services = new NameServices;
        $id = $request->id;
        $course = EducOfferedCourses::where('id',$id)->first(['offered_curriculum_id','year_level','section']);
        $query = EducOfferedCourses::with('course','instructor','schedule.days','schedule.room','students')
                    ->where('offered_curriculum_id',$course->offered_curriculum_id)
                    ->where('year_level',$course->year_level)
                    ->where('section',$course->section)
                    ->get()
                    ->map(function($query) use ($name_services){
                        $schedule = '<u>TBA</u>';
                        $room = '<u>TBA</u>';
                        $instructor = 'TBA';
                        $no_students = count($query->students);
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
                        $units = $query->course->units;
                        if($query->course->lab>0){
                            $units = $query->course->units.' - L('.$query->course->lab.')';
                        }
                        return [
                            'id' => $query->id,
                            'name' => $query->course->name,
                            'code' => $query->course->code,
                            'units' => $units,
                            'min_max' => $query->min_student.'-'.$query->max_student,
                            'no_students' => $no_students,
                            'schedule' => $schedule,
                            'room' => $room,
                            'instructor' => $instructor,
                        ];
                    })->toArray();
        if(count($query)>0){
            $x = 1;
            foreach($query as $r){
                $schedule = $r['schedule'];
                $room = $r['room'];
                if($user_access_level==1 || $user_access_level==2 || $user_access_level==3){
                    $schedule = '<button class="btn btn-primary btn-primary-scan btn-sm courseSchedRmModal"
                                    data-id="'.$r['id'].'">
                                    '.$r['schedule'].'</button>';
                    $room = '<button class="btn btn-info btn-info-scan btn-sm courseSchedRmModal"
                                    data-id="'.$r['id'].'">
                                    '.$r['room'].'</button>';
                }
                $data_list['f1'] = $x;
                $data_list['f2'] = $r['name'];
                $data_list['f3'] = $r['code'];
                $data_list['f4'] = $r['units'];
                $data_list['f5'] = $r['min_max'];
                $data_list['f6'] = $r['no_students'];
                $data_list['f7'] = $schedule;
                $data_list['f8'] = $room;
                $data_list['f9'] = $r['instructor'];
                array_push($data,$data_list);
                $x++;
            }
        }
        return  response()->json($data);
    }    
}

?>