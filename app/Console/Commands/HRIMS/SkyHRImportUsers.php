<?php

namespace App\Console\Commands\HRIMS;

use App\Models\DTRlogs;
use App\Models\DTRlogsCopy;
use App\Models\Users;
use App\Models\UsersDTR;
use App\Services\TokenServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Rats\Zkteco\Lib\ZKTeco;

class SkyHRImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sky-hr-import-users';

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
        $users = Users::where('id_no','!=', NULL)->pluck('id_no');

        $employees = DB::connection('skyhr')->table('tblEmployees')
            ->get();

        $filteredEmployees = $employees->filter(function ($employee) use ($users) {
            return !$users->contains($employee->IdNo);
        });

        $filteredEmployees->all();
        if($filteredEmployees->count()>0){
            $token = new TokenServices;
            foreach($filteredEmployees as $row){
                $check = Users::where('id_no',$row->IdNo)->first();
                if($check==NULL){
                    $token1 = $token->token(4);
                    $token2 = $token->token(4);
                    $password = Crypt::encryptString($token1.Hash::make(str_replace(' ','',mb_strtolower($row->LastName))).$token2);

                    $check_users = Users::where('username',$row->IdNo)->first();

                    if($check_users!=NULL){

                    }
                }
            }
        }

    }
}
