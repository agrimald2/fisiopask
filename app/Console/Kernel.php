<?php

namespace App\Console;

use App\Jobs\CheckAssistance;
use App\Jobs\SendReminderBefore;
use App\Jobs\SendSurvey;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new CheckAssistance)->everyMinute();
        $schedule->job(new SendReminderBefore)->twiceDaily(10, 18)->timezone('America/Lima');;
        $schedule->job(new SendSurvey)->hourly();
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
