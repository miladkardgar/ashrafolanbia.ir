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
        'App\Console\Commands\notifyPeriodLate',
        'App\Console\Commands\removeOldQuickPayLinks'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->command('Create:NextDateIfInactive')->hourlyAt(30)->withoutOverlapping();

        $schedule->command('Create:charityPeriod')->twiceDaily(1,12)->withoutOverlapping();
        $schedule->command('Create:NextDateIfNull')->dailyAt('04:00')->withoutOverlapping();
        $schedule->command('notify:periodCreation')->dailyAt('11:00')->withoutOverlapping();
        $schedule->command('notify:periodLate')->dailyAt('11:01')->withoutOverlapping();
        $schedule->command('send:systemLogFile')->dailyAt('00:05')->withoutOverlapping();

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
