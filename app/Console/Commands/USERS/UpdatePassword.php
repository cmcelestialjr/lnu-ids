<?php

namespace App\Console\Commands\USERS;

use App\Models\Users;
use App\Services\TokenServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdatePassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-password';

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
        $token = new TokenServices;

        //$user = Users::where('username','230211')->first();

        // $token1 = $token->token(4);
        // $token2 = $token->token(4);
        // $password = Crypt::encryptString($token1.Hash::make(str_replace(' ','',mb_strtolower($users->lastname))).$token2);

        $users = Users::get();
        foreach($users as $user){
            $password = Hash::make(str_replace(' ','',ucwords(mb_strtolower($user->lastname))));
            Users::where('id', $user->id)
                ->update([
                    'password' => $password
                ]);
        }
    }
}
