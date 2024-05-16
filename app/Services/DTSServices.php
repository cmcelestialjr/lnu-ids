<?php
namespace App\Services;

use App\Models\DTSDocs;

class DTSServices
{
    public function dts_id(){
        $year = date('Y');
        $month = date('m');
        $dts_id = date('Y').date('m').'-00001';
        $latest_dts_id = DTSDocs::whereYear('created_at',$year)
            ->whereMonth('created_at',$month)
            ->orderBy('dts_id','DESC')
            ->first();
        if($latest_dts_id){
            $latest_dts_id = explode('-',$latest_dts_id->dts_id);
            $dts_id_no = (int)$latest_dts_id[1] + 1;
            $dts_id = $year.$month.'-'.str_pad($dts_id_no, 5, '0', STR_PAD_LEFT);
        }
        return $dts_id;
    }
}
