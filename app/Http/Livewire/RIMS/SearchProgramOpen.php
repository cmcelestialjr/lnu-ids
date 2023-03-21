<?php

namespace App\Http\Livewire\RIMS;

use Livewire\Component;
use App\Models\EducPrograms;
use App\Models\EducDepartments;
use App\Models\EducOfferedSchoolYear;
use App\Models\EducProgramLevel;

class SearchProgramOpen extends Component
{
    public $name;
    public $departments;
    public $school_year_id;
    protected $listeners = [
        'updatedDepartments' => 'handleUpdateDepartments',
        'shoolYearIDs' => 'handleShoolYearIDs'
    ];
    public function handleUpdateDepartments($value)
    {
        $this->departments = $value;
    }
    public function handleShoolYearIDs($value)
    {        
        $this->school_year_id = $value[0];
    }
    public function render()
    {
        $school_year_id = $this->school_year_id;
        $query = EducOfferedSchoolYear::with('grade_period')->where('id',$school_year_id)->first();
        if($query!=NULL){
            $grade_period = $query->grade_period->period;
            $program_level_ids = EducProgramLevel::where('period',$grade_period)->pluck('id')->toArray();
        }else{
            $grade_period = '';
            $program_level_ids = [];
        }
        
        if($this->departments=='GS'){
            $departments = EducDepartments::where('shorten','=','GS')->pluck('id')->toArray();
        }else{
            $departments = EducDepartments::where('shorten','!=','GS')->pluck('id')->toArray();            
        }
        $query = EducPrograms::with('departments','program_level')
                    ->where('status_id',1)
                    ->where('shorten', 'LIKE', '%'.$this->name.'%')
                    ->whereIn('program_level_id',$program_level_ids)
                    ->whereIn('department_id',$departments)->get();
        $data = array(
            'query' => $query
        );
        return view('livewire.r-i-m-s.search-program-open',$data);
    }
}

?>
