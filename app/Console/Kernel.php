<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('inspire')->hourly();
        $schedule->command('make:dailyStudyStatusAnalysis')->daily();
        $schedule->command('make:dailyActivityStatusAnalysis')->daily();
        //$schedule->command('make:pmDailyActivitiesList')->dailyAt('10:00');
        //$schedule->command('make:pmDailyDelayActivities')->dailyAt('10:05');
        //$schedule->command('make:dailyDepartmentActivities')->dailyAt('10:10');
        //$schedule->command('make:weeklyPreStudyPlannedData')->mondays()->at('09:00');
        //$schedule->command('make:hodDailyDelayActivitiesList')->mondays()->at('09:05');
        //$schedule->command('make:checkCronosData')->everyTwoHours();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
