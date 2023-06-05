<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\HRIMS\DtrMachineCheck::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('app:dtr-machine-check')->everyMinute();
        
        //$schedule->command('app:dtr-machine-check')->cron('* * * * 1-5');
        //$schedule->command('app:dtr-machine-check')->everySecond()->until(now()->addSeconds(60));
        //$schedule->command('app:dtr-machine-check')->daily('13:04')->timezone('Asia/Manila');
        //$schedule->command('app:dtr-machine-check')->daily('13:03:59')->timezone('Asia/Manila');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
