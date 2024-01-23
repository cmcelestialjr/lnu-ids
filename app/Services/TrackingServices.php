<?php
namespace App\Services;

use App\Models\Tracking;

class TrackingServices
{
    public function tracking_id($year,$month){
        $tracking_id = $year.$month.'00001';
        $latest_tracking = Tracking::whereYear('created_at',$year)
            ->whereMonth('created_at',$month)
            ->orderBy('tracking_id','DESC')
            ->first();
        if($latest_tracking){
            $latest_tracking_id = $latest_tracking->tracking_id;
            $tracking_number = (int)substr($latest_tracking_id, -5) + 1;
            $tracking_id = $year.$month.str_pad($tracking_number, 5, '0', STR_PAD_LEFT);
        }
        return $tracking_id;
    }
}