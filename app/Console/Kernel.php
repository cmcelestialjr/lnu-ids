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
       if ($now->isBetween('6:00', '10:00') || $now->isBetween('11:00', '13:30') || $now->isBetween('15:00', '20:30')) {
            $schedule->command('app:dtr-machine-check')->everyTwoMinutes();
            $schedule->command('app:sky-hr-import')->everyThreeMinutes();
            $schedule->command('app:link-dtr')->everyFiveMinutes();
        }
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
