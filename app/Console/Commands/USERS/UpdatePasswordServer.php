<?php

namespace App\Console\Commands\USERS;

use App\Models\Users;
use App\Services\TokenServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-password-server';

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
        $connectionName = 'server';
        DB::connection($connectionName)->getPdo();

        $users = Users::get();
        foreach($users as $user){
            DB::connection($connectionName)->table('users')->where('id', $user->id)
                ->update([
                    'password' => $user->password
                ]);
        }
    }
}
