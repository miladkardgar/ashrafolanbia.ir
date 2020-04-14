<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */

    protected $commands = [
        'App\Console\Commands\CreateCharityPeriod',
        'App\Console\Commands\CreateNextDateIfNull',
        'App\Console\Commands\CreateNextDateIfInactive',
        'App\Console\Commands\notifyPeriodCration',
        'App\Console\Commands\notifyPeriodLate'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('Create:NextDateIfNull')->hourly()->withoutOverlapping();
        $schedule->command('Create:NextDateIfInactive')->hourly()->withoutOverlapping();
        $schedule->command('Create:charityPeriod')->hourly()->withoutOverlapping();
        $schedule->command('notify:periodCreation')->dailyAt('10:00')->withoutOverlapping();
        $schedule->command('notify:periodLate')->dailyAt('10:00')->withoutOverlapping();


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
