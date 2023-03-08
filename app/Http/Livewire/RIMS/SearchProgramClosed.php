<?php

namespace App\Http\Livewire\RIMS;

use Livewire\Component;
use App\Models\EducPrograms;
use App\Models\EducDepartments;

class SearchProgramClosed extends Component
{   
    public $name;
    public $departments;
    protected $listeners = [
        'updatedDepartments' => 'handleUpdateDepartments'
    ];
    public function handleUpdateDepartments($value)
    {
        $this->departments = $value;
    }
    public function render()
    {
        if($this->departments=='GS'){
            $departments = EducDepartments::where('shorten','=','GS')->pluck('id')->toArray();
        }else{
            $departments = EducDepartments::where('shorten','!=','GS')->pluck('id')->toArray();            
        }
        
        $query = EducPrograms::with('departments','program_level')
                    ->where('status_id',2)
                    ->where('shorten', 'LIKE', '%'.$this->name.'%')
                    ->whereIn('department_id',$departments)->get();
        $data = array(
            'query' => $query,
            'dept' => $this->departments
        );
        return view('livewire.r-i-m-s.search-program-closed',$data);
    }
}
