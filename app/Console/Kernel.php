<?php

namespace App\Console;

use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserSecurityLogs;
use Carbon\Carbon;
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
        $schedule->call(function () {
            PasswordReset::where('email', '!=', '')->where('created_at', '<=', Carbon::now()->subMinutes(20)->toDateTimeString())->delete();
            UserSecurityLogs::where('user_id', '!=', '')->where('created_at', '<=', Carbon::now()->subDays(7)->toDateTimeString())->delete();
        })->everyMinute();
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