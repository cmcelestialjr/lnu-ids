<?php

namespace App\Imports;

use App\Models\EducCourses;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CoursesImport implements ToModel, WithHeadingRow
{
/**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        $curriculum_id = $row['curriculum_id'];
        $grade_level_id = $row['grade_level_id'];
        $grade_period_id = $row['grade_period_id'];
        $code = $row['code'];
        $name = $row['name'];
        $units = $row['units'];
        if($curriculum_id!=''){
            $check_course = EducCourses::where('curriculum_id',$curriculum_id)
                ->where('code',$code)->first();
            if($check_course){
                $dataToInsert = [
                    'curriculum_id' => $curriculum_id,
                    'grade_level_id' => $grade_level_id,
                    'grade_period_id' => $grade_period_id,
                    'name' => $name,
                    'shorten' => $code,
                    'code' => $code,
                    'units' => $units,
                    'pay_units' => $units,
                    'description' => $name,
                    'status_id' => $check_course->status_id,
                    'course_type_id' => $check_course->course_type_id,
                    'updated_by' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                EducCourses::where('id',$check_course->id)->update($dataToInsert);
            }else{
                $dataToInsert = [
                    'curriculum_id' => $curriculum_id,
                    'grade_level_id' => $grade_level_id,
                    'grade_period_id' => $grade_period_id,
                    'name' => $name,
                    'shorten' => $code,
                    'code' => $code,
                    'units' => $units,
                    'pay_units' => $units,
                    'description' => $name,
                    'status_id' => 1,
                    'course_type_id' => 1,
                    'updated_by' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                EducCourses::insert($dataToInsert);
            }

            $connectionName = 'server';
            DB::connection($connectionName)->getPdo();
            $check_course = DB::connection($connectionName)->table('educ_courses')->where('curriculum_id',$curriculum_id)
                ->where('code',$code)->first();
            if($check_course){
                $dataToInsert = [
                    'curriculum_id' => $curriculum_id,
                    'grade_level_id' => $grade_level_id,
                    'grade_period_id' => $grade_period_id,
                    'name' => $name,
                    'shorten' => $code,
                    'code' => $code,
                    'units' => $units,
                    'pay_units' => $units,
                    'description' => $name,
                    'status_id' => $check_course->status_id,
                    'course_type_id' => $check_course->course_type_id,
                    'updated_by' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                DB::connection($connectionName)->table('educ_courses')->where('id',$check_course->id)->update($dataToInsert);
            }else{
                $dataToInsert = [
                    'curriculum_id' => $curriculum_id,
                    'grade_level_id' => $grade_level_id,
                    'grade_period_id' => $grade_period_id,
                    'name' => $name,
                    'shorten' => $code,
                    'code' => $code,
                    'units' => $units,
                    'pay_units' => $units,
                    'description' => $name,
                    'status_id' => 1,
                    'course_type_id' => 1,
                    'updated_by' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                DB::connection($connectionName)->table('educ_courses')->insert($dataToInsert);
            }
        }
    }
}
