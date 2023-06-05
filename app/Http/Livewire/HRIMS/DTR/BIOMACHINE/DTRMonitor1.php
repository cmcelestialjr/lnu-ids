<?php

namespace App\Http\Livewire\HRIMS\DTR\BIOMACHINE;

use App\Models\DTRlogs;
use App\Models\UsersDTR;
use App\Services\NameServices;
use Carbon\Carbon;
use Livewire\Component;

class DTRMonitor1 extends Component
{
    public $dtr;
    protected $listeners = [
        'refreshMonitor1' => 'refreshMonitor1'
    ];
    public function refreshMonitor1()
    {    

    }
    public function mount()
    {      

    }
    public function render()
    {
        $name_services = new NameServices;
        $currentDateTime = Carbon::now();
        $ipaddress = '10.5.201.72';
        $query = UsersDTR::where('ipaddress',$ipaddress)->orderBy('dateTime','DESC')->first();
        $list = DTRlogs::orderBy('dateTime','DESC')->limit(20)->get();
        $diffInSeconds = 0;
        if($query!=NULL){
            $toDateTime = Carbon::parse($query->updated_at);
            $diffInSeconds = $currentDateTime->diffInSeconds($toDateTime);
        }
        $display = 'none';
        $in_am = '<br>';
        $out_am = '<br>';
        $in_pm = '<br>';
        $out_pm = '<br>';
        $id_no = '';
        $name = '';
        if($diffInSeconds>0 && $diffInSeconds<=7){
            $display = 'view';
            if($query->time_in_am!=NULL){
                $in_am = date('h:ia',strtotime($query->time_in_am));
            }
            if($query->time_out_am!=NULL){
                $out_am = date('h:ia',strtotime($query->time_out_am));
            }
            if($query->time_in_pm!=NULL){
                $in_pm = date('h:ia',strtotime($query->time_in_pm));
            }
            if($query->time_out_pm!=NULL){
                $out_pm = date('h:ia',strtotime($query->time_out_pm));
            }
            $id_no = $query->user->id_no;
            $name = $name_services->firstname($query->user->lastname,$query->user->firstname,$query->user->middlename,$query->user->extname);
        }
        $data = array(
            'query' => $query,
            'display' => $display,
            'diffInSeconds' => $diffInSeconds,
            'in_am' => $in_am,
            'out_am' => $out_am,
            'in_pm' => $in_pm,
            'out_pm' => $out_pm,
            'id_no' => $id_no,
            'name' => $name,
            'list' => $list,
            'name_services' => $name_services
        );
        return view('livewire.h-r-i-m-s.d-t-r.b-i-o-m-a-c-h-i-n-e.d-t-r-monitor1',$data);
    }
}
