<?php
namespace App\Services;

use App\Models\Holidays;
use App\Models\UsersDTR;
use App\Models\UsersDTRInfo;
use App\Models\UsersDTRInfoTotal;
use App\Models\UsersSchedDays;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DTRInfoServices
{
    public function getDtr($id, $year, $month)
    {
        return UsersDTR::with('time_type_')
            ->whereHas('user', fn($query) => $query->where('id', $id))
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'ASC')
            ->get();
    }
    public function getDtrNext($id, $nextDay)
    {
        return UsersDTR::with('time_type_')
            ->whereHas('user', fn($query) => $query->where('id', $id))
            ->whereDate('date', $nextDay)
            ->orderBy('date', 'ASC')
            ->first();
    }
    public function getDtrSched($id, $startDate, $endDate, $optionId)
    {
        return UsersSchedDays::with(['time' => function ($query) use ($id,$startDate,$endDate,$optionId) {
            $query->where('user_id',$id)
            ->where('option_id',$optionId)
            ->where('date_to','>=',$startDate)
            ->where('date_from','<=',$endDate)
            ->orderBy('time_from', 'DESC');
        }])
        ->whereHas('time', function ($query) use ($id,$startDate,$endDate,$optionId) {
            $query->where('user_id',$id)
            ->where('option_id',$optionId)
            ->where('date_to','>=',$startDate)
            ->where('date_from','<=',$endDate);
        })->get();
    }
    public function getHolidays($year, $month)
    {
        return Holidays::where(function ($query) use ($month) {
            $query->whereMonth('date', $month)
                ->where('option', 'Yes');
        })
        ->orWhere(function ($query) use ($year, $month) {
            $query->whereYear('date', $year)
                ->whereMonth('date', $month);
        })
        ->get();
    }
    public function getDtrInfoTotal($id, $year, $month, $option_id)
    {
        return UsersDTRInfoTotal::where('user_id',$id)
                ->whereYear('date',$year)
                ->whereMonth('date',$month)
                ->where('option_id',$option_id)
                ->first();
    }
    public function defaultValues()
    {
        return [
            'day' => null,
            'check' => '',
            'holiday' => '',
            'in_am' => '',
            'out_am' => '',
            'in_pm' => '',
            'out_pm' => '',
            'time_type' => '',
            'time_type_name' => '',
            'time_in_am_type' => 0,
            'time_out_am_type' => 0,
            'time_in_pm_type' => 0,
            'time_out_pm_type' => 0,
            'hours' => 0,
            'minutes' => 0,
            'tardy_hr' => 0,
            'tardy_min' => 0,
            'tardy_no' => 0,
            'ud_hr' => 0,
            'ud_min' => 0,
            'ud_no' => 0,
            'hd_hr' => 0,
            'hd_min' => 0,
            'hd_no' => 0,
            'abs_hr' => 0,
            'abs_min' => 0,
            'abs_no' => 0,
            'sched_time' => []
        ];
    }
    public function removeDuplicate($data)
    {
        $id_no = $data['id_no'];
        $year = $data['year'];
        $month = $data['month'];

        $duplicateDate = UsersDTR::select('date')
            ->where('id_no', $id_no)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->groupBy('date')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('date');

        if ($duplicateDate->isNotEmpty()) {
            $latestIds = UsersDTR::whereIn('id', function ($query) use ($duplicateDate, $id_no) {
                $query->select(DB::raw('MAX(id) as id'))
                    ->from('users_dtr')
                    ->whereIn('date', $duplicateDate)
                    ->where('id_no',$id_no)
                    ->groupBy('date')
                    ->havingRaw('COUNT(*) > 1');
            })
            ->pluck('id');
            UsersDTR::where('id_no',$id_no)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->whereIn('id', $latestIds)
            ->delete();
        }
    }
    public function initial($data)
    {
        $lastDay = $data['lastDay'];
        $year = $data['year'];
        $month = $data['month'];
        $defaultValues = $data['defaultValues'];
        $range = $data['range'];
        $getDtrSched = $data['getDtrSched'];
        $dtr = $data['dtr'];
        $included_days = [];
        for ($i = 1; $i <= $lastDay; $i++){
            $weekDay = date('w', strtotime($year.'-'.$month.'-'.$i));
            $iDate = date('Y-m-d', strtotime($year.'-'.$month.'-'.$i));
            if($weekDay==0){
                $weekDay = 7;
            }
            $dtr[$i] = $defaultValues;
            $dtr[$i]['day'] = $i;

            $include = ($range==2 && $i>15) || $iDate>date('Y-m-d') ? 'no' : 'yes';

            if($include=='yes'){
                $processInitial = $this->processInitial($getDtrSched, $dtr, $included_days, $weekDay, $iDate, $i);
                $dtr = $processInitial['dtr'];
                $included_days = $processInitial['included_days'];
            }
        }
        return [
            'dtr' => $dtr,
            'included_days' => $included_days
        ];
    }
    public function dtrCalculate($data)
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
        $holidays = $data['holidays'];
        $range = $data['range'];
        $days = 0;

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

            $days = $this->processDtrRow($row, $dtr, $day, $option_id, $row_next, $days, $user_id, $id_no, $updated_by);

        }
        foreach($included_days as $row){

            $this->processIncludedDay($dtr, $row, $user_id, $id_no, $year, $month, $updated_by, $option_id);

        }
        $dtr_info = [
            'user_id' => $user_id,
            'id_no' => $id_no,
            'date' => date('Y-m-d',strtotime($year.'-'.$month.'-01')),
            'updated_by' => $updated_by,
            'option_id' => $option_id,
            'days' => $days,
            'holidays' => $holidays,
            'range' => $range
        ];
        $this->dtrInfototal($dtr_info);
    }
    public function holidays($data)
    {
        $getHolidays = $data['getHolidays'];
        $included_days = $data['included_days'];
        $holidays = $data['holidays'];
        $dtr = $data['dtr'];

        foreach($getHolidays as $row){
            $day = date('j',strtotime($row->date));
            $dtr[$day]['check'] = '';
            $dtr[$day]['holiday'] = $row->name;

            $index = array_search($day, $included_days);
            if ($index !== false) {
                unset($included_days[$index]);
            }else{
                $holidays++;
            }
        }
        return [
            'dtr' => $dtr,
            'included_days' => $included_days,
            'holidays' => $holidays
        ];
    }
    public function dtr($data)
    {
        $getDtr = $data['getDtr'];
        $dtr = $data['dtr'];
        $range = $data['range'];
        $included_days = $data['included_days'];

        for ($k = 0; $k < $getDtr->count(); $k++){
            $row = $getDtr[$k];
            $day = date('j', strtotime($row->date));

            $include = 'yes';
            if($range==2 && $day>15){
                $dtr[$day]['check'] = '---';
                $include = 'no';
            }
            if($include=='yes'){
                $index = array_search($day, $included_days);
                if ($index !== false) {
                    unset($included_days[$index]);
                }

                $dtr = $this->processScheduleTimes($row, $dtr, $day);
            }
        }
        return [
            'dtr' => $dtr,
            'included_days' => $included_days
        ];
    }
    public function dtrInfo($data)
    {
        $id = $data['id'];
        $year = $data['year'];
        $month = $data['month'];
        $option_id = $data['option_id'];
        $dtr = $data['dtr'];
        $range = $data['range'];
        $keys = $this->getKeys();

        $getDtrInfo = UsersDTRInfo::where('user_id',$id)
                ->whereYear('date',$year)
                ->whereMonth('date',$month)
                ->where('option_id',$option_id)
                ->orderBy('date','ASC')
                ->get();

        foreach ($getDtrInfo as $row){
            $day = date('j',strtotime($row->date));

            $include = ($range==2 && $day>15) || date('Y-m-d', strtotime($row->date))>date('Y-m-d') ? 'no' : 'yes';

            if($include=='yes'){
                foreach ($keys as $key) {
                    $dtr[$day][$key] = $row->$key;
                }
            }
        }
        return [
            'dtr' => $dtr
        ];
    }
    private function processInitial($getDtrSched, $dtr, $included_days, $weekDay, $iDate, $i)
    {
        foreach ($getDtrSched as $row){
            if($weekDay==$row->day){
                if($row->time->date_from <= $iDate &&
                    $row->time->date_to >= $iDate){
                    $dtr[$i]['check'] = 'included';
                    $dtr[$i]['sched_time'][] = [
                        'in' => $row->time->time_from,
                        'out' => $row->time->time_to,
                        'is_rotation_duty' => $row->time->is_rotation_duty
                    ];
                }
            }
        }
        if($dtr[$i]['check'] == 'included'){
            $included_days[] = $i;
        }
        return [
            'dtr' => $dtr,
            'included_days' => $included_days
        ];
    }
    private function processScheduleTimes($row, $dtr, $day)
    {
        $in_am = $this->formatTime($row->time_in_am);
        $out_am = $this->formatTime($row->time_out_am);
        $in_pm = $this->formatTime($row->time_in_pm);
        $out_pm = $this->formatTime($row->time_out_pm);

        $time_type = $row->time_type;

        $dtr[$day]['check'] = 'time';
        $dtr[$day]['in_am'] = $in_am;
        $dtr[$day]['out_am'] = $out_am;
        $dtr[$day]['in_pm'] = $in_pm;
        $dtr[$day]['out_pm'] = $out_pm;
        $dtr[$day]['time_type'] = $time_type;
        $dtr[$day]['time_in_am_type'] = $row->time_in_am_type;
        $dtr[$day]['time_out_am_type'] = $row->time_out_am_type;
        $dtr[$day]['time_in_pm_type'] = $row->time_in_pm_type;
        $dtr[$day]['time_out_pm_type'] = $row->time_out_pm_type;

        if($row->time_type_){
            $dtr[$day]['time_type_name'] = $row->time_type_->name;
        }

        foreach($dtr[$day]['sched_time'] as $sched){
            if(strtotime($sched['in']) && strtotime($sched['out'])){
                $in_from = date('H:i',strtotime($sched['in']));
                $out_to = date('H:i',strtotime($sched['out']));
                if(!$time_type){

                    if($in_from<'12:00' && $out_to>='14:01'){
                        if(!$in_am){
                            $dtr[$day]['time_in_am_type'] = 0;
                        }
                        if(!$out_am){
                            $dtr[$day]['time_out_am_type'] = 0;
                        }
                        if(!$in_pm){
                            $dtr[$day]['time_in_pm_type'] = 0;
                        }
                        if(!$out_pm){
                            $dtr[$day]['time_out_pm_type'] = 0;
                        }
                    }elseif($in_from<'12:00' && $out_to<='14:00'){
                        if(!$in_am){
                            $dtr[$day]['time_in_am_type'] = 0;
                        }
                        if(!$out_am){
                            $dtr[$day]['time_out_am_type'] = 0;
                        }
                    }elseif($in_from>='12:00' && $out_to>'12:00'){
                        if(!$in_pm){
                            $dtr[$day]['time_in_pm_type'] = 0;
                        }
                        if(!$out_pm){
                            $dtr[$day]['time_out_pm_type'] = 0;
                        }
                    }elseif($in_from>='12:00' && $out_to<'12:00'){
                        if(!$out_am){
                            $dtr[$day]['time_out_am_type'] = 0;
                        }
                        if(!$out_pm){
                            $dtr[$day]['time_out_pm_type'] = 0;
                        }
                    }
                }
            }
        }
        return $dtr;
    }
    private function updateDtrInfo($data){
        $keys = $this->getKeys();
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
        foreach ($keys as $key) {
            $update->$key = $data[$key];
        }
        $update->updated_by = $data['updated_by'];
        $update->save();
    }
    private function processDtrRow($row, $dtr, $day, $option_id, $row_next, $days, $user_id, $id_no, $updated_by)
    {
        $time_in_am = $this->formatTime($row->time_in_am);
            $time_out_am = $this->formatTime($row->time_out_am);
            $time_in_pm = $this->formatTime($row->time_in_pm);
            $time_out_pm = $this->formatTime($row->time_out_pm);

            $total_minutes = $tardy_minutes = $ud_minutes = $hd_minutes = $abs_minutes = 0;
            $tardy_no = $ud_no = $hd_no = $abs_no = 0;
            $check_day = $sched_tardy = $sched_ud = 0;
            $total_time_diff = 0;

            $sched_count = count($dtr[$day]['sched_time']);

            foreach($dtr[$day]['sched_time'] as $sched){
                if(strtotime($sched['in']) && strtotime($sched['out'])){
                    $in_from = date('H:i',strtotime($sched['in']));
                    $out_to = date('H:i',strtotime($sched['out']));
                    $check_day = 1;

                    $in_from_ = Carbon::parse($in_from)->seconds(0);
                    $out_to_ = Carbon::parse($out_to)->seconds(0);

                    $total_time_diff = $this->totalTimeDiff($total_time_diff, $out_to, $in_from);

                    if($out_to>$in_from){

                        $is_am = $in_from >= '00:00' && $out_to <= '13:59';
                        $is_pm = $in_from >= '10:00' && $out_to <= '23:59';

                        if ($is_am || $is_pm) {
                            $time_in = $is_am ? $time_in_am : $time_in_pm;
                            $time_out = $is_am ? $time_out_am : $time_out_pm;
                            $type_in = $is_am ? 'am' : 'pm';
                            $type_in_value = $is_am ? $row->time_in_am_type : $row->time_in_pm_type;
                            $type_out_value = $is_am ? $row->time_out_am_type : $row->time_out_pm_type;

                            if ($time_in && $time_in > $in_from) {
                                $tardy_minutes += $this->calculateTimeDifference($in_from, $time_in);
                                $total_minutes += $tardy_minutes;
                                $tardy_no++;
                            }

                            if ($time_out && $time_out < $out_to) {
                                $ud_minutes += $this->calculateTimeDifference(($time_out < $in_from) ? $in_from : $time_out, $out_to);
                                $total_minutes += $ud_minutes;
                                $ud_no++;
                            }

                            if ($option_id == 2 && $type_in_value > 1 && $type_out_value > 1) {
                                $total_minutes += $total_time_diff;
                                switch ($type_in) {
                                    case 'am':
                                        $sched_tardy = 1;
                                        break;

                                    default:
                                        $sched_ud = 1;
                                        break;
                                }
                            }

                            if ($time_in == NULL && $time_out == NULL && $type_in_value == NULL && $type_out_value == NULL) {
                                if ($sched_count > 1) {
                                    $hd_minutes = $out_to_->diffInMinutes($in_from_);
                                    $hd_no = 1;
                                } else {
                                    $abs_minutes = $out_to_->diffInMinutes($in_from_);
                                    $abs_no = 1;
                                }
                                $total_minutes += $hd_minutes ?: $abs_minutes;
                            }
                        }else{
                            if($time_in_am && $time_in_am > $in_from){
                                $tardy_minutes += $this->calculateTimeDifference($in_from,$time_in_am);
                                $total_minutes += $tardy_minutes;
                                $tardy_no++;
                            }

                            if($time_out_pm && $time_out_pm < $out_to){
                                $ud_minutes += $this->calculateTimeDifference(($time_out_pm<$in_from) ? $in_from : $time_out_pm,$out_to);
                                $total_minutes += $ud_minutes;
                                $ud_no++;
                            }

                            if($option_id==2 && $row->time_in_am_type > 1 && $row->time_out_pm_type > 1){
                                $total_minutes += $total_time_diff;
                            }

                            if($time_in_am == NULL && $time_out_pm == NULL && $row->time_in_am_type == NULL && $row->time_out_pm_type == NULL){
                                if($sched_count > 1){
                                    $hd_minutes = $out_to_->diffInMinutes($in_from_);
                                    $hd_no = 1;
                                }else{
                                    $abs_minutes = $out_to_->diffInMinutes($in_from_);
                                    $abs_no = 1;
                                }
                                $total_minutes += $hd_minutes ?: $abs_minutes;
                            }
                        }
                    }else{
                        if($time_in_pm && $time_in_pm > $in_from){
                            $tardy_minutes += $this->calculateTimeDifference($in_from,$time_in_pm);
                            $total_minutes += $tardy_minutes;
                            $tardy_no++;
                        }

                        if($time_out_pm && $time_out_pm>$out_to){
                            $ud_minutes += $this->calculateTimeDifference($time_out_pm,'23:59');
                            $ud_minutes += $this->calculateTimeDifference('00:00',$out_to);
                            $total_minutes += $ud_minutes;
                            $ud_no++;

                        }elseif($row_next){
                            $time_out_am_next = $this->formatTime($row_next->time_out_am);
                            if($time_out_am_next && $time_out_am_next < $out_to){
                                $ud_minutes += $this->calculateTimeDifference($time_out_am_next,$out_to);
                                $total_minutes += $ud_minutes;
                                $ud_no++;
                            }
                        }
                    }
                }
            }

            if($row->time_type==1){
                $abs_minutes = $total_time_diff;
                $total_minutes += $abs_minutes;
                $abs_no = 1;
                $check_day = 0;
            }

            switch ($option_id) {
                case 2:
                    if($total_time_diff == $total_minutes && $total_minutes > 0 && $total_time_diff > 0){
                        $abs_minutes = $total_time_diff;
                        $abs_no = 1;
                    }else{
                        if($sched_count>1){
                            if($sched_tardy == 1){
                                $tardy_minutes = $total_time_diff;
                                $tardy_no = 1;
                            }
                            if($sched_ud == 1){
                                $ud_minutes = $total_time_diff;
                                $ud_no = 1;
                            }
                        }
                    }
                    break;

                default:
                    if($row->time_type == 2 || $row->time_type == 3){
                        $hd_minutes = $total_time_diff / 2;
                        $total_minutes += $hd_minutes;
                        if($total_minutes > 0){
                            $hd_no = 1;
                        }
                    }
                    break;
            }

            if($check_day==1){
                $days++;
            }

            $convertedTotal = $this->convertMinutes($total_minutes);
            $hours = $convertedTotal['hours'];
            $minutes = $convertedTotal['minutes'];

            $earned_minutes = $total_time_diff - $total_minutes;
            $convertedEarned = $this->convertMinutes($earned_minutes);
            $earned_hours = $convertedEarned['hours'];
            $earned_minutes = $convertedEarned['minutes'];

            $convertedTardy = $this->convertMinutes($tardy_minutes);
            $tardy_hr = $convertedTardy['hours'];
            $tardy_min = $convertedTardy['minutes'];

            $convertedUd = $this->convertMinutes($ud_minutes);
            $ud_hr = $convertedUd['hours'];
            $ud_min = $convertedUd['minutes'];

            $convertedHd = $this->convertMinutes($hd_minutes);
            $hd_hr = $convertedHd['hours'];
            $hd_min = $convertedHd['minutes'];

            $convertedAbs = $this->convertMinutes($abs_minutes);
            $abs_hr = $convertedAbs['hours'];
            $abs_min = $convertedAbs['minutes'];

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
            $this->updateDtrInfo($dtr_info);
        return $days;
    }
    private function totalTimeDiff($total_time_diff, $out_to, $in_from)
    {
        if($out_to>$in_from){
            $total_time_diff += $this->calculateTimeDifference($in_from, $out_to);
        }else{
            $total_time_diff += $this->calculateTimeDifference($in_from, '23:59');
            $total_time_diff += $this->calculateTimeDifference('00:00', $out_to);
        }
        return $total_time_diff;
    }
    private function processIncludedDay($dtr, $row, $user_id, $id_no, $year, $month, $updated_by, $option_id)
    {
            $total_time_diff = 0;
            foreach($dtr[$row]['sched_time'] as $sched){
                if(strtotime($sched['in']) && strtotime($sched['out'])){
                    $in_from = date('H:i',strtotime($sched['in']));
                    $out_to = date('H:i',strtotime($sched['out']));

                    $total_time_diff = $this->totalTimeDiff($total_time_diff, $out_to, $in_from);
                }
            }
            $abs_no = 1;

            $convertedTotal = $this->convertMinutes($total_time_diff);
            $hours = $convertedTotal['hours'];
            $minutes = $convertedTotal['minutes'];
            $abs_hr = $hours;
            $abs_min = $minutes;

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
                'earned_hours' => 0,
                'earned_minutes' => 0,
                'updated_by' => $updated_by,
                'option_id' => $option_id
            ];
            $this->updateDtrInfo($dtr_info);
    }
    private function dtrInfototal($data)
    {
        $updated_by = $data['updated_by'];
        $days = $data['days'];
        $holidays = $data['holidays'];
        $range = $data['range'];

        $keys = $this->getKeys();
        $variables = array_fill_keys($keys, 0);

        if($data['days']>=22){
            $days = 22;
        }

        $query = UsersDTRInfo::where('user_id',$data['user_id'])
            ->whereYear('date',date('Y',strtotime($data['date'])))
            ->whereMonth('date',date('m',strtotime($data['date'])))
            ->where('option_id',$data['option_id'])
            ->get();
        if($query->count()>0){
            foreach($query as $row){
                $day = date('j', strtotime($row->date));
                $include = ($range==2 && $day>15) || date('Y-m-d', strtotime($row->date))>date('Y-m-d') ? 'no' : 'yes';
                if($include=='yes'){
                    foreach ($keys as $key) {
                        $variables[$key] += $row->$key;
                    }
                }
            }
            foreach ($keys as $key) {
                switch ($key) {
                    case 'minutes':
                        $this->convertMinutesToHours($variables['hours'], $variables[$key]);
                        break;
                    case 'tardy_min':
                        $this->convertMinutesToHours($variables['tardy_hr'], $variables[$key]);
                        break;
                    case 'ud_min':
                        $this->convertMinutesToHours($variables['ud_hr'], $variables[$key]);
                        break;
                    case 'hd_min':
                        $this->convertMinutesToHours($variables['hd_hr'], $variables[$key]);
                        break;
                    case 'abs_min':
                        $this->convertMinutesToHours($variables['abs_hr'], $variables[$key]);
                        break;
                    case 'earned_minutes':
                        $this->convertMinutesToHours($variables['earned_hours'], $variables[$key]);
                        break;
                    default:
                        break;
                }
            }
        }

        $check = UsersDTRInfoTotal::where('user_id',$data['user_id'])
            ->where('date',$data['date'])
            ->where('option_id',$data['option_id'])
            ->first();
        if($check){
            $update = UsersDTRInfoTotal::find($check->id);
        }else{
            $update = new UsersDTRInfoTotal;
            $update->user_id = $data['user_id'];
            $update->id_no = $data['id_no'];
            $update->date = $data['date'];
            $update->option_id = $data['option_id'];
        }

        foreach ($variables as $key => $value) {
            $update->$key = $value;
        }
        $update->days = $days;
        $update->holidays = $holidays;
        $update->updated_by = $updated_by;
        $update->save();
    }
    private function formatTime($time)
    {
        return strtotime($time) ? date('H:i', strtotime($time)) : NULL;
    }
    private function calculateTimeDifference($start, $end)
    {
        $start_ = Carbon::parse($start)->seconds(0);
        $end_ = Carbon::parse($end)->seconds(0);
        return $end_->diffInMinutes($start_);
    }
    private function convertMinutes($totalMinutes)
    {
        return [
            'hours' => floor($totalMinutes / 60),
            'minutes' => $totalMinutes % 60
        ];
    }
    private function getKeys()
    {
        return [
            'hours', 'minutes', 'tardy_hr', 'tardy_min', 'tardy_no',
            'ud_hr', 'ud_min', 'ud_no', 'hd_hr', 'hd_min', 'hd_no',
            'abs_hr', 'abs_min', 'abs_no', 'earned_hours', 'earned_minutes'
        ];
    }
    private function convertMinutesToHours(&$hours, &$minutes)
    {
        if ($minutes >= 60) {
            $hours += floor($minutes / 60);
            $minutes %= 60;
        }
    }

}
