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
        Commands\UsersHourCount::class,
        Commands\UsersLive::class,
        Commands\UsersPackage::class,
        Commands\UsersRemoveData::class,
        Commands\UsersDash::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('users:hour')
            ->hourly();
        $schedule->command('users:live')
            ->daily();
        $schedule->command('users:package')
            ->hourly();
        $schedule->command('users:remove')
            ->daily();
        // todo ... dash 首页刷新
    }
}
