<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use App\Models\EducPrograms;
use App\Models\PSGCRegions;

class DataImport implements ToModel, WithHeadingRow
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
    //programs
    // public function model(array $row)
    // {
    //     $user = Auth::User();
    //     $user_id = $user->id;
    //     $insert = new EducPrograms();        
    //     $insert->department_id = $row["department_id"];
    //     $insert->program_level_id = $row["program_level_id"];
    //     $insert->name = $row["name"];
    //     $insert->shorten = $row["shorten"];
    //     $insert->updated_by = $user_id;
    //     $insert->save();
    // }

    public function model(array $row)
    {
        $user = Auth::User();
        $user_id = $user->id;
        $insert = new EducPrograms();        
        $insert->department_id = $row["department_id"];
        $insert->program_level_id = $row["program_level_id"];
        $insert->name = $row["name"];
        $insert->shorten = $row["shorten"];
        $insert->updated_by = $user_id;
        $insert->save();
    }
}
?>
