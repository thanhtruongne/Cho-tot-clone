<?php

namespace App\Console;

use App\Models\Cron;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    // protected function schedule(Schedule $schedule): void
    // {
    //     $crons = Cron::enable()->get();
    //     foreach ($crons as $index => $cron) {
    //         $schedule->command($cron->command)->cron($cron->expression)->withoutOverlapping()
    //             ->onFailure(function () use ($cron){
    //                \Log::info('Cron '.$cron->command.' bị fail '.date('d/m/Y H:i:s'));
    //             });
    //     }
    // }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
