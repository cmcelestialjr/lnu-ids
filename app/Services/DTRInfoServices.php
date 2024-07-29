<?php
namespace App\Services;

use App\Models\UsersDTRInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DTRInfoServices
{
    public function index($data)
    {
        $user_id = $data['user_id'];
        $id_no = $data['id_no'];
        $dtr = $data['dtr'];
        $getDtr = $data['getDtr'];
        $included_days = $data['included_days'];
        $getDtrNext = $data['getDtrNext'];
        $year = $data['year'];
        $month = $data['month'];
        $option_id = $data['option_id'];

        $user = Auth::user();
        $updated_by = $user->id;

        for ($k = 0; $k < $getDtr->count(); $k++){
            $row = $getDtr[$k];
            $row_next = ($getDtr->count()==$k+1) ? $getDtrNext : $getDtr[$k+1];
            $day = date('j', strtotime($row->date));

            $index = array_search($day, $included_days);
            if ($index !== false) {
                unset($included_days[$index]);
            }

            $time_in_am = (strtotime($row->time_in_am)) ? date('H:i',strtotime($row->time_in_am)) : NULL;
            $time_out_am = (strtotime($row->time_out_am)) ? date('H:i',strtotime($row->time_out_am)) : NULL;
            $time_in_pm = (strtotime($row->time_in_pm)) ? date('H:i',strtotime($row->time_in_pm)) : NULL;
            $time_out_pm = (strtotime($row->time_out_pm)) ? date('H:i',strtotime($row->time_out_pm)) : NULL;

            $time_in_am_type = $row->time_in_am_type;
            $time_out_am_type = $row->time_out_am_type;
            $time_in_pm_type = $row->time_in_pm_type;
            $time_out_pm_type = $row->time_out_pm_type;

            $total_minutes = 0;
            $tardy_minutes = 0;
            $tardy_no = 0;
            $ud_minutes = 0;
            $ud_no = 0;
            $hd_minutes = 0;
            $hd_no = 0;
            $abs_minutes = 0;
            $abs_no = 0;
            $time_out_am_next = NULL;

            $sched_count = count($dtr[$day]['sched_time']);
            $total_time_diff = 0;
            //dd($dtr[$day]['sched_time']);
            foreach($dtr[$day]['sched_time'] as $sched){
                if(strtotime($sched['in']) && strtotime($sched['out'])){
                    $in_from = date('H:i',strtotime($sched['in']));
                    $out_to = date('H:i',strtotime($sched['out']));

                    $in_from_ = Carbon::parse($in_from)->seconds(0);
                    $out_to_ = Carbon::parse($out_to)->seconds(0);

                    $total_time_diff += $out_to_->diffInMinutes($in_from_);

                    if($out_to>$in_from){
                        if($in_from>='00:00' && $out_to<='13:59'){
                            if($time_in_am && $time_in_am>$in_from){
                                $time_from_ = Carbon::parse($in_from)->seconds(0);
                                $time_to_ = Carbon::parse($time_in_am)->seconds(0);
                                $tardy_minutes += $time_to_->diffInMinutes($time_from_);
                                $total_minutes += $tardy_minutes;
                                $tardy_no++;
                            }

                            if($time_out_am && $time_out_am<$out_to){
                                $time_from_ = Carbon::parse(($time_out_am<$in_from) ? $in_from : $time_out_pm)->seconds(0);
                                $time_to_ = Carbon::parse($out_to)->seconds(0);
                                $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                $total_minutes += $ud_minutes;
                                $ud_no++;
                            }

                            if($time_in_am==NULL && $time_out_am==NULL &&
                                $time_in_am_type==NULL && $time_out_am_type==NULL
                            ){
                                if($sched_count>1){
                                    $hd_minutes = $out_to_->diffInMinutes($in_from_);
                                    $total_minutes += $hd_minutes;
                                    $hd_no = 1;
                                }else{
                                    $abs_minutes = $out_to_->diffInMinutes($in_from_);
                                    $total_minutes += $abs_minutes;
                                    $abs_no = 1;
                                }
                            }
                        }elseif($in_from>='10:00' && $out_to<='23:59'){
                            if($time_in_pm && $time_in_pm>$in_from){
                                $time_from_ = Carbon::parse($in_from)->seconds(0);
                                $time_to_ = Carbon::parse($time_in_pm)->seconds(0);
                                $tardy_minutes += $time_to_->diffInMinutes($time_from_);
                                $total_minutes += $tardy_minutes;
                                $tardy_no++;
                            }

                            if($time_out_pm && $time_out_pm<$out_to){
                                $time_from_ = Carbon::parse(($time_out_pm<$in_from) ? $in_from : $time_out_pm)->seconds(0);
                                $time_to_ = Carbon::parse($out_to)->seconds(0);
                                $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                $total_minutes += $ud_minutes;
                                $ud_no++;
                            }

                            if($time_in_pm==NULL && $time_out_pm==NULL &&
                                $time_in_pm_type==NULL && $time_out_pm_type==NULL
                            ){
                                if($sched_count>1){
                                    $hd_minutes = $out_to_->diffInMinutes($in_from_);
                                    $total_minutes += $hd_minutes;
                                    $hd_no = 1;
                                }else{
                                    $abs_minutes = $out_to_->diffInMinutes($in_from_);
                                    $total_minutes += $abs_minutes;
                                    $abs_no = 1;
                                }
                            }
                        }else{
                            if($time_in_am && $time_in_am>$in_from){
                                $time_from_ = Carbon::parse($in_from)->seconds(0);
                                $time_to_ = Carbon::parse($time_in_am)->seconds(0);
                                $tardy_minutes += $time_to_->diffInMinutes($time_from_);
                                $total_minutes += $tardy_minutes;
                                $tardy_no++;
                            }
                            if($time_out_pm && $time_out_pm<$out_to){
                                $time_from_ = Carbon::parse(($time_out_pm<$in_from) ? $in_from : $time_out_pm)->seconds(0);
                                $time_to_ = Carbon::parse($out_to)->seconds(0);
                                $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                $total_minutes += $ud_minutes;
                                $ud_no++;
                            }
                            if($time_in_am==NULL && $time_out_pm==NULL &&
                                $time_in_am_type==NULL && $time_out_pm_type==NULL
                            ){
                                if($sched_count>1){
                                    $hd_minutes = $out_to_->diffInMinutes($in_from_);
                                    $total_minutes += $hd_minutes;
                                    $hd_no = 1;
                                }else{
                                    $abs_minutes = $out_to_->diffInMinutes($in_from_);
                                    $total_minutes += $abs_minutes;
                                    $abs_no = 1;
                                }
                            }
                        }
                    }else{
                        if($time_in_pm && $time_in_pm>$in_from){
                            $time_from_ = Carbon::parse($in_from)->seconds(0);
                            $time_to_ = Carbon::parse($time_in_pm)->seconds(0);
                            $tardy_minutes += $time_to_->diffInMinutes($time_from_);
                            $total_minutes += $tardy_minutes;
                            $tardy_no++;
                        }
                        if($time_out_pm && $time_out_pm>$out_to){
                            $time_from_ = Carbon::parse($time_out_pm)->seconds(0);
                            $time_to_ = Carbon::parse('23:59')->seconds(0);
                            $time_from_add_ = Carbon::parse('00:00')->seconds(0);
                            $time_to_add_ = Carbon::parse($out_to)->seconds(0);
                            $ud_minutes += $time_to_->diffInMinutes($time_from_);
                            $ud_minutes += $time_to_add_->diffInMinutes($time_from_add_);
                            $total_minutes += $ud_minutes;
                            $ud_no++;
                        }else{
                            if($row_next){
                                $time_out_am_next = (strtotime($row_next->time_out_am)) ? date('H:i',strtotime($row_next->time_out_am)) : NULL;
                                if($time_out_am_next && $time_out_am_next<$out_to){
                                    $time_from_ = Carbon::parse($time_out_am_next)->seconds(0);
                                    $time_to_ = Carbon::parse($out_to)->seconds(0);
                                    $ud_minutes += $time_to_->diffInMinutes($time_from_);
                                    $total_minutes += $ud_minutes;
                                    $ud_no++;
                                }
                            }
                        }
                    }
                }
            }

            if($row->time_type==1){
                $abs_minutes = $total_time_diff;
                $total_minutes += $abs_minutes;
                $abs_no = 1;
            }elseif($row->time_type==2 || $row->time_type==3){
                $hd_minutes = $total_time_diff/2;
                $total_minutes += $hd_minutes;
                if($total_minutes>0){
                    $hd_no = 1;
                }
            }

            $hours = 0;
            $minutes = $total_minutes;
            if($total_minutes>=60){
                $hours = floor($total_minutes / 60);
                $minutes = $total_minutes % 60;
            }
            $earned_hours = 0;
            $earned_minutes = $total_time_diff-$total_minutes;
            if($earned_minutes>=60){
                $earned_hours = floor($earned_minutes / 60);
                $earned_minutes = $earned_minutes % 60;
            }
            $tardy_hr = 0;
            $tardy_min = $tardy_minutes;
            if($tardy_min>=60){
                $tardy_hr = floor($tardy_minutes / 60);
                $tardy_min = $tardy_minutes % 60;
            }
            $ud_hr = 0;
            $ud_min = $ud_minutes;
            if($ud_minutes>=60){
                $ud_hr = floor($ud_minutes / 60);
                $ud_min = $ud_minutes % 60;
            }
            $hd_hr = 0;
            $hd_min = $hd_minutes;
            if($hd_minutes>=60){
                $hd_hr = floor($hd_minutes / 60);
                $hd_min = $hd_minutes % 60;
            }
            $abs_hr = 0;
            $abs_min = $abs_minutes;
            if($abs_minutes>=60){
                $abs_hr = floor($abs_minutes / 60);
                $abs_min = $abs_minutes % 60;
            }

            $dtr_info = [
                'user_id' => $user_id,
                'id_no' => $id_no,
                'date' => $row->date,
                'hours' => $hours,
                'minutes' => $minutes,
                'tardy_hr' => $tardy_hr,
                'tardy_min' => $tardy_min,
                'tardy_no' => $tardy_no,
                'ud_hr' => $ud_hr,
                'ud_min' => $ud_min,
                'ud_no' => $ud_no,
                'hd_hr' => $hd_hr,
                'hd_min' => $hd_min,
                'hd_no' => $hd_no,
                'abs_hr' => $abs_hr,
                'abs_min' => $abs_min,
                'abs_no' => $abs_no,
                'earned_hours' => $earned_hours,
                'earned_minutes' => $earned_minutes,
                'updated_by' => $updated_by,
                'option_id' => $option_id
            ];
            $this->update($dtr_info);
            // $dtr[$day]['hours'] = $hours;
            // $dtr[$day]['minutes'] = $minutes;
            // $dtr[$day]['tardy_hr'] = $tardy_hr;
            // $dtr[$day]['tardy_min'] = $tardy_min;
            // $dtr[$day]['tardy_no'] = $tardy_no;
            // $dtr[$day]['ud_hr'] = $ud_hr;
            // $dtr[$day]['ud_min'] = $ud_min;
            // $dtr[$day]['ud_no'] = $ud_no;
            // $dtr[$day]['hd_hr'] = $hd_hr;
            // $dtr[$day]['hd_min'] = $hd_min;
            // $dtr[$day]['hd_no'] = $hd_no;
            // $dtr[$day]['abs_hr'] = $abs_hr;
            // $dtr[$day]['abs_min'] = $abs_min;
            // $dtr[$day]['abs_no'] = $abs_no;
        }
        foreach($included_days as $row){
            $total_minutes = 0;
            $total_time_diff = 0;
            foreach($dtr[$row]['sched_time'] as $sched){
                if(strtotime($sched['in']) && strtotime($sched['out'])){
                    $in_from = date('H:i',strtotime($sched['in']));
                    $out_to = date('H:i',strtotime($sched['out']));

                    $in_from_ = Carbon::parse($in_from)->seconds(0);
                    $out_to_ = Carbon::parse($out_to)->seconds(0);

                    $total_time_diff += $out_to_->diffInMinutes($in_from_);
                }
            }
            $abs_minutes = $total_time_diff;
            $total_minutes = $total_time_diff;
            $abs_no = 1;
            $hours = 0;
            $minutes = $total_minutes;
            if($total_minutes>=60){
                $hours = floor($total_minutes / 60);
                $minutes = $total_minutes % 60;
            }
            $earned_hours = 0;
            $earned_minutes = $total_time_diff-$total_minutes;
            if($earned_minutes>=60){
                $earned_hours = floor($earned_minutes / 60);
                $earned_minutes = $earned_minutes % 60;
            }
            $abs_hr = 0;
            $abs_min = $abs_minutes;
            if($abs_minutes>=60){
                $abs_hr = floor($abs_minutes / 60);
                $abs_min = $abs_minutes % 60;
            }
            $dtr[$row]['hours'] = $hours;
            $dtr[$row]['minutes'] = $minutes;
            $dtr[$row]['abs_hr'] = $abs_hr;
            $dtr[$row]['abs_min'] = $abs_min;
            $dtr[$row]['abs_no'] = $abs_no;

            $dtr_info = [
                'user_id' => $user_id,
                'id_no' => $id_no,
                'date' => date('Y-m-d',strtotime($year.'-'.$month.'-'.$row)),
                'hours' => $hours,
                'minutes' => $minutes,
                'tardy_hr' => 0,
                'tardy_min' => 0,
                'tardy_no' => 0,
                'ud_hr' => 0,
                'ud_min' => 0,
                'ud_no' => 0,
                'hd_hr' => 0,
                'hd_min' => 0,
                'hd_no' => 0,
                'abs_hr' => $abs_hr,
                'abs_min' => $abs_min,
                'abs_no' => $abs_no,
                'earned_hours' => $earned_hours,
                'earned_minutes' => $earned_minutes,
                'updated_by' => $updated_by,
                'option_id' => $option_id
            ];
            $this->update($dtr_info);
        }
    }

    private function update($data){

        $check = UsersDTRInfo::where('user_id',$data['user_id'])
            ->where('date',$data['date'])
            ->where('option_id',$data['option_id'])
            ->first();
        if($check){
            $update = UsersDTRInfo::find($check->id);

        }else{
            $update = new UsersDTRInfo;
            $update->user_id = $data['user_id'];
            $update->id_no = $data['id_no'];
            $update->date = $data['date'];
            $update->option_id = $data['option_id'];
        }

        $update->hours = $data['hours'];
        $update->minutes = $data['minutes'];
        $update->tardy_hr = $data['tardy_hr'];
        $update->tardy_min = $data['tardy_min'];
        $update->tardy_no = $data['tardy_no'];
        $update->ud_hr = $data['ud_hr'];
        $update->ud_min = $data['ud_min'];
        $update->ud_no = $data['ud_no'];
        $update->hd_hr = $data['hd_hr'];
        $update->hd_min = $data['hd_min'];
        $update->hd_no = $data['hd_no'];
        $update->abs_hr = $data['abs_hr'];
        $update->abs_min = $data['abs_min'];
        $update->abs_no = $data['abs_no'];
        $update->earned_hours = $data['earned_hours'];
        $update->earned_minutes = $data['earned_minutes'];
        $update->updated_by = $data['updated_by'];
        $update->save();
    }
}
