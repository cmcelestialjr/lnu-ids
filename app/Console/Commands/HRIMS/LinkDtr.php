<?php

namespace App\Console\Commands\HRIMS;

use App\Models\DTRlogs;
use App\Models\Users;
use App\Models\UsersDTR;
use Illuminate\Console\Command;

class LinkDtr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:link-dtr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = DTRlogs::where('link',0)
            ->orderBy('dateTime','ASC')
            ->get();
        if($query->count() > 0){
            $dtr_log_ids = [];
            foreach($query as $row){
                $id_no = $row->id_no;
                $check = Users::where('id_no',$id_no)->first();
                if($check){
                    $dtr_log_id = $row->id;
                    $dateTime = $row->dateTime;
                    $state = $row->state;
                    $type = $row->type;
                    $ipaddress = $row->ipaddress;
                    $link = 2;
                    $time = date('H:i',strtotime($dateTime));
                    $date = date('Y-m-d',strtotime($dateTime));

                    $check = UsersDTR::where('id_no',$id_no)
                        ->where('date',$date)->first();
                    if($time<'12:00'){
                        if($check){
                            if($check->time_out_am!=NULL && $dateTime>=$check->time_out_am){
                                $column = 'time_in_pm';
                                $state_column = 'state_in_pm';
                                $ip_column = 'ipaddress_in_pm';
                                $check_where = 1;
                            }else{
                                if($type==0 || $type==3){
                                    $column = 'time_in_am';
                                    $state_column = 'state_in_am';
                                    $ip_column = 'ipaddress_in_am';
                                    $check_where = 2;
                                }else{
                                    $column = 'time_out_am';
                                    $state_column = 'state_out_am';
                                    $ip_column = 'ipaddress_out_am';
                                    $check_where = 3;
                                }
                            }
                        }else{
                            if($type==0 || $type==3){
                                $column = 'time_in_am';
                                $state_column = 'state_in_am';
                                $ip_column = 'ipaddress_in_am';
                                $check_where = 4;
                            }else{
                                $column = 'time_out_am';
                                $state_column = 'state_out_am';
                                $ip_column = 'ipaddress_out_am';
                                $check_where = 5;
                            }

                        }
                    }elseif($time>='12:00' && $time<='13:00'){
                        if($type==0 || $type==3){
                            $column = 'time_in_pm';
                            $state_column = 'state_in_pm';
                            $ip_column = 'ipaddress_in_pm';
                            $check_where = 6;
                        }else{
                            $column = 'time_out_am';
                            $state_column = 'state_out_am';
                            $ip_column = 'ipaddress_out_am';
                            $check_where = 7;
                        }
                    }else{
                        if($type==0 || $type==3){
                            $column = 'time_in_pm';
                            $state_column = 'state_in_pm';
                            $ip_column = 'ipaddress_in_pm';
                            $check_where = 8;
                        }else{
                            $column = 'time_out_pm';
                            $state_column = 'state_out_pm';
                            $ip_column = 'ipaddress_out_pm';
                            $check_where = 9;
                        }
                    }
                    //$this->info($check_where);
                    if($check==NULL){
                        $insert = new UsersDTR();
                        $insert->id_no = $id_no;
                        $insert->date = $date;
                        $insert->$column = $dateTime;
                        $insert->$state_column = $state;
                        $insert->$ip_column = $ipaddress;
                        $insert->ipaddress = $ipaddress;
                        $insert->dateTime = $dateTime;
                        $insert->save();
                        $link = 1;
                    }else{
                        if($time>='12:00' && $dateTime>$check->time_in_pm && $check->time_out_pm==NULL && $check->time_out_am==NULL && $check->time_in_pm!=NULL && $type==1){
                            $column = 'time_out_pm';
                            $state_column = 'state_out_pm';
                            $check_where = 10;
                        }
                        $this->info($column.'-'.$check_where.'-'.$time.'-'.date('H:i',strtotime($check->$column)).'-'.$check->$column);
                        if($check->$column==NULL){
                            UsersDTR::where('id_no',$id_no)
                                    ->where('date',$date)
                                    ->update([$column => $dateTime,
                                            $state_column => $state,
                                            $ip_column => $ipaddress,
                                            'ipaddress' => $ipaddress,
                                            'dateTime' => $dateTime,
                                            'updated_at' => date('Y-m-d H:i:s')]);
                            $link = 1;
                        }
                        UsersDTR::where('id_no',$id_no)
                                    ->where('date',$date)
                                    ->update(['ipaddress' => $ipaddress,
                                            'dateTime' => $dateTime,
                                            'time_type' => NULL,
                                            'updated_at' => date('Y-m-d H:i:s')]);
                    }
                    //$this->info($column);
                    $check = UsersDTR::where('id_no',$id_no)
                        ->where('date',$date)->first();
                    if($check){
                        if($check->time_out_am<=$check->time_in_am && $check->time_in_am!=NULL && $check->time_out_am!=NULL){
                            UsersDTR::where('id_no',$id_no)
                                ->where('date',$date)
                                ->update(['time_out_am' => NULL,
                                        'state_out_am' => NULL,
                                        'ipaddress_out_am' => NULL]);
                        }
                        if($check->time_in_pm<=$check->time_out_am && $check->time_in_pm!=NULL && $check->time_out_am!=NULL){
                            UsersDTR::where('id_no',$id_no)
                                ->where('date',$date)
                                ->update(['time_in_pm' => NULL,
                                        'state_in_pm' => NULL,
                                        'ipaddress_in_pm' => NULL]);
                        }
                        if($check->time_out_pm<=$check->time_in_pm && $check->time_out_pm!=NULL && $check->time_in_pm!=NULL){
                            UsersDTR::where('id_no',$id_no)
                                ->where('date',$date)
                                ->update(['time_out_pm' => NULL,
                                        'state_out_pm' => NULL,
                                        'ipaddress_out_pm' => NULL]);
                        }
                    }
                    $dtr_log_ids[] = $dtr_log_id;
                }
            }
            if($dtr_log_ids){
                DTRlogs::whereIn('id',$dtr_log_ids)
                        ->update(['link' => $link,
                                'updated_at' => date('Y-m-d H:i:s')]);
            }
        }
        $this->info('Command executed successfully!');
    }
}
