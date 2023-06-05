<?php

namespace App\Http\Controllers\FIS\Students;
use App\Http\Controllers\Controller;
use App\Models\StudentsInfo;
use App\Models\StudentsProgram;
use Illuminate\Http\Request;

class ModalController extends Controller
{
    public function studentViewModal(Request $request){
        $id = $request->id;
        $query = StudentsInfo::where('user_id',$id)->first();
        $program_level = StudentsProgram::where('user_id',$id)
                ->select('program_level_id','curriculum_id')
                ->orderBy('program_level_id','DESC')->first();
        $data = array(
            'id' => $id,
            'query' => $query,
            'program_level' => $program_level->program_level_id,
            'curriculum' => $program_level->curriculum_id
        );
        return view('fis/students/studentViewModal',$data);
    }
}