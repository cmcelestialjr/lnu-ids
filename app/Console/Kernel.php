<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\HRIMS\DtrMachineCheck::class,
        \App\Console\Commands\HRIMS\SkyHRImport::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $now = now();
       // if ($now->isBetween('6:59', '10:01') || $now->isBetween('11:29', '13:31') || $now->isBetween('14:59', '19:31')) {
            // $schedule->command('app:dtr-machine-check')->everyThirtySeconds();
            // $schedule->command('app:sky-hr-import')->everyMinute();
            $schedule->command('app:dtr-machine-check')->everyTwoMinutes();
            $schedule->command('app:sky-hr-import')->everyThreeMinutes();
            // $schedule->command('app:dtr-machine-check')->everyThreeMinutes();
            // $schedule->command('app:sky-hr-import')->everyTwoMinutes();

        //}
        //$schedule->command('app:dtr-machine-check')->everyTwoSeconds();
        //$schedule->command('app:sky-hr-import')->everyMinute();
        
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
