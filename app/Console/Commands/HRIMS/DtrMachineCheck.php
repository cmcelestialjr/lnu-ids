<?php

namespace App\Console\Commands\HRIMS;

use App\Models\DTRlogs;
use App\Models\UsersDTR;
use Illuminate\Console\Command;
use Rats\Zkteco\Lib\ZKTeco;

class DtrMachineCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dtr-machine-check';

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
        $start_time = time();

        while (time() < $start_time + 60) {
            // $insert = new DTRlogs();
            // $insert->user_id = '230209';
            // $insert->state = 1;
            // $insert->dateTime = date('Y-m-d H:i:s');
            // $insert->type = 1;
            // $insert->save();        
            $ipaddress = '10.5.201.72';
            $zk = new ZKTeco($ipaddress,4370);   
            if ($zk->connect()){
                
                    //$zk->clearAdmin();
                    //$zk->setUser(5,5,'cesar','',1);
                    //$user_details123 = $zk->specificUser(5);                
                $attendace = $zk->getAttendance();
                foreach($attendace as $row){
                    $user_id = $row['id'];
                    $state = $row['state']; //1 finger, 15 face
                    $timestamp = $row['timestamp'];
                    $type = $row['type']; //0 in, 1 out

                    $insert = new DTRlogs();
                    $insert->id_no = $user_id;
                    $insert->state = $state;
                    $insert->dateTime = $timestamp;
                    $insert->type = $type;
                    $insert->ipaddress = $ipaddress;
                    $insert->save();

                    $time = date('H:i',strtotime($timestamp));
                    $date = date('Y-m-d',strtotime($timestamp));

                    $check = UsersDTR::where('id_no',$user_id)
                        ->where('date',$date)->first();
                    if($time<'12:00'){
                        if($type==0 || $type==3){
                            $column = 'time_in_am';
                            $state_column = 'state_in_am';
                            $ip_column = 'ipaddress_in_am';
                        }else{
                            $column = 'time_out_am';
                            $state_column = 'state_out_am';
                            $ip_column = 'ipaddress_out_am';
                        }
                    }elseif($time>='12:00' && $time<='13:00'){
                        if($type==0 || $type==3){
                            $column = 'time_in_pm';
                            $state_column = 'state_in_pm';
                            $ip_column = 'ipaddress_in_pm';
                        }else{
                            $column = 'time_out_am';
                            $state_column = 'state_out_am';
                            $ip_column = 'ipaddress_out_am';
                        }
                    }else{
                        if($type==0 || $type==3){
                            $column = 'time_in_pm';
                            $state_column = 'state_in_pm';
                            $ip_column = 'ipaddress_in_pm';
                        }else{
                            $column = 'time_out_pm';
                            $state_column = 'state_out_pm';
                            $ip_column = 'ipaddress_out_pm';
                        }
                    }
                    if($check==NULL){
                        $insert = new UsersDTR();
                        $insert->id_no = $user_id;
                        $insert->date = $date;
                        $insert->$column = $timestamp;
                        $insert->$state_column = $state;
                        $insert->$ip_column = $ipaddress;
                        $insert->ipaddress = $ipaddress;
                        $insert->dateTime = $timestamp;
                        $insert->save();
                    }else{
                        if($time>='12:00' && $check->time_in_pm>$timestamp && $check->time_out_am==NULL && $check->time_in_pm!=NULL && $type==1){
                            $column = 'time_out_pm';
                            $state_column = 'state_out_pm';
                        }elseif($time<'12:00' && $check->time_in_am>=$timestamp && $check->time_out_am==NULL && $check->time_in_am!=NULL && $type==1){
                            $column = 'time_in_am';
                            $state_column = 'state_in_am';
                        }
                        if($time!=date('H:i',strtotime($check->$column)) && $check->$column==NULL){
                            UsersDTR::where('id_no',$user_id)
                                    ->where('date',$date)
                                    ->update([$column => $timestamp,
                                            $state_column => $state,
                                            $ip_column => $ipaddress,
                                            'ipaddress' => $ipaddress,
                                            'dateTime' => $timestamp,
                                            'updated_at' => date('Y-m-d H:i:s')]);
                        }
                        UsersDTR::where('id_no',$user_id)
                                    ->where('date',$date)
                                    ->update(['ipaddress' => $ipaddress,
                                            'dateTime' => $timestamp,
                                            'time_type' => NULL,
                                            'updated_at' => date('Y-m-d H:i:s')]);
                    }
                    $check = UsersDTR::where('id_no',$user_id)
                            ->where('date',$date)->first();
                    if($check!=NULL){
                        if($check->time_out_am<=$check->time_in_am && $check->time_in_am!=NULL && $check->time_out_am!=NULL){
                            UsersDTR::where('id_no',$user_id)
                                    ->where('date',$date)
                                    ->update(['time_out_am' => NULL,
                                            'state_out_am' => NULL,
                                            'ipaddress_out_am' => NULL]);
                        }
                        if($check->time_in_pm<=$check->time_out_am && $check->time_in_pm!=NULL && $check->time_out_am!=NULL){
                            UsersDTR::where('id_no',$user_id)
                                    ->where('date',$date)
                                    ->update(['time_in_pm' => NULL,
                                            'state_in_pm' => NULL,
                                            'ipaddress_in_pm' => NULL]);
                        }
                        if($check->time_out_pm<=$check->time_in_pm && $check->time_out_pm!=NULL && $check->time_in_pm!=NULL){
                            UsersDTR::where('id_no',$user_id)
                                    ->where('date',$date)
                                    ->update(['time_out_pm' => NULL,
                                            'state_out_pm' => NULL,
                                            'ipaddress_out_pm' => NULL]);
                        }
                    }
                }
                $zk->clearAttendance();
                    //$attendace = $zk->getAttendanceSpecific();
                    //$zk->setUser(7,7,'abc','abc',0,0);
            }
            sleep(1);
        }
    }
}
