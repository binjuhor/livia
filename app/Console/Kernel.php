<?php

namespace App\Console;

use App\Jobs\SyncJiraIssuesForProject;
use App\Jobs\UpsertInvoice;
use App\Projects\InteractsWithProjectModel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    use InteractsWithProjectModel;

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
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(
            (new SyncJiraIssuesForProject(
                $this->findProjectByKey('PM')
            ))
        )->daily()->emailOutputTo('son@chillbits.com');

        $schedule->job(
            (new UpsertInvoice(
                $this->findProjectByKey('PM')
            ))
        )->daily()->emailOutputTo('son@chillbits.com');
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
