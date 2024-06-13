<?php

namespace App\Imports;

use App\Models\EducBranch;
use App\Models\EducCurriculum;
use App\Models\EducCurriculumBranch;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CurriculumImport implements ToModel, WithHeadingRow
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
        $id = $row['id'];
        $program_id = $row['program_id'];
        $code = $row['code'];
        $name = $row['name'];
        $remarks = $row['remarks'];
        $year_from = $row['year_from'];
        if($year_from=='\N' || $year_from==''){
            $year_from = NULL;
        }
        $check = EducCurriculum::find($id);
        if($check){
            $update = EducCurriculum::find($id);
            $update->program_id = $program_id;
            $update->code = $code;
            $update->name = $name;
            $update->remarks = $remarks;
            $update->year_from = $year_from;
        }else{
            $update = new EducCurriculum;
            $update->id = $id;
            $update->program_id = $program_id;
            $update->code = $code;
            $update->name = $name;
            $update->remarks = $remarks;
            $update->year_from = $year_from;
            $update->status_id = 1;
        }
        $update->save();

        $check_branch = EducCurriculumBranch::where('curriculum_id',$id)->get();
        if($check_branch->count()<=0){
            $brances = EducBranch::get();
            if($brances->count()>0){
                foreach($brances as $branch){
                    $insert = new EducCurriculumBranch;
                    $insert->curriculum_id = $id;
                    $insert->branch_id = $branch->id;
                    $insert->status_id = 1;
                    $insert->updated_by = 1;
                    $insert->save();
                }
            }
        }

        $connectionName = 'server';
        DB::connection($connectionName)->getPdo();
        $check_server = DB::connection($connectionName)->table('educ_curriculum')->where('id',$id)->first();
        if($check_server){
            $dataToInsert = [
                'program_id' => $program_id,
                'code' => $code,
                'name' => $name,
                'remarks' => $remarks,
                'year_from' => $year_from
            ];
            DB::connection($connectionName)->table('educ_curriculum')->where('id',$id)->update($dataToInsert);
        }else{
            $dataToInsert = [
                'id' => $id,
                'program_id' => $program_id,
                'code' => $code,
                'name' => $name,
                'remarks' => $remarks,
                'year_from' => $year_from,
                'status_id' => 1,
                'updated_by' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            DB::connection($connectionName)->table('educ_curriculum')->insert($dataToInsert);
        }

        $check_branch_server = DB::connection($connectionName)->table('educ_curriculum_branch')->where('curriculum_id',$id)->get();
        if($check_branch_server->count()<=0){
            $brances_server = DB::connection($connectionName)->table('educ_branch')->get();
            if($brances_server->count()>0){
                foreach($brances_server as $branch){
                    $dataToInsert = [
                        'curriculum_id' => $id,
                        'branch_id' => $branch->id,
                        'status_id' => 1,
                        'updated_by' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    DB::connection($connectionName)->table('educ_curriculum_branch')->insert($dataToInsert);
                }
            }
        }
    }
}
