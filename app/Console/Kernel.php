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
       if ($now->isBetween('6:00', '10:00') || $now->isBetween('11:00', '13:30') || $now->isBetween('14:30', '20:30')) {
            $schedule->command('app:dtr-machine-check')->everyThirtySeconds();
            $schedule->command('app:sky-hr-import')->everyFourMinutes();
            $schedule->command('app:link-dtr')->everyThreeMinutes();
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
