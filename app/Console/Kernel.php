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
        // Hourly reminders from 8 AM for unexecuted orders
        $schedule->command('orders:notify-unexecuted')->hourly();

        // Hourly reminders from 8 AM for unreturned characters
        $schedule->command('orders:notify-unreturned')->hourly();

        // Daily at 6 PM: warn about tomorrow's orders with unreturned characters
        $schedule->command('orders:notify-conflicts')->dailyAt('18:00');
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
