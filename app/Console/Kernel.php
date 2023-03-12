<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SendTodoReminders;
use App\Console\Commands\AddBirthdayPoints;
use App\Console\Commands\ExpireRewardPoints;
use App\Console\Commands\CurrencyExchangeRates;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SendTodoReminders::class,
        AddBirthdayPoints::class,
        ExpireRewardPoints::class,
        CurrencyExchangeRates::class
   ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('todoreminder:send')
            ->everyMinute();
        $schedule->command('sitemap:update')
            ->dailyAt('1:00');
        $schedule->command('birthdayrewardpoints:add')
            ->dailyAt('1:00');
        $schedule->command('rewardpoints:expire')
            ->dailyAt('1:00');
        $schedule->command('currencyexchangerates:get')->hourly();
        $schedule->command('orderstatus:update')
            ->dailyAt('1:00');
        $schedule->command('order:complete')
            ->dailyAt('1:00');
        $schedule->command('queue:work --queue=high,normal,default,low --stop-when-empty')->everyMinute(); //->withoutOverlapping()
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
