<?php

namespace App\Console;

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
        Commands\GetPayments::class,
        Commands\FailedQuickbooksPayments::class,
        Commands\PendingRevokation::class,
        Commands\ImportPayments::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('membership:getPayments 5')
            ->dailyAt('07:30');

        $schedule->command('membership:FailedQuickbooksPayments')
            ->dailyAt('08:30');

        $schedule->command('membership:PendingRevokation')
            ->dailyAt('17:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
