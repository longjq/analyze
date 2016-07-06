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
        Commands\UserTemp::class,              // 每日零点十分保存前一日的新增数和活跃数
        Commands\Count::class,                 // 更新总数数据

        Commands\UsersHourCount::class,
        Commands\UsersLive::class,
        Commands\UsersPackage::class,
        Commands\UsersLiveDateCount::class,
        Commands\LiveCount::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 每日零点十五分保存前一日的新增数和活跃数
        $schedule->command('users:temp')
            ->dailyAt('0:15');
        
        
        $schedule->command('users:hour')
            ->hourly();
        $schedule->command('users:live')
            ->daily();
        $schedule->command('users:package')
            ->everyFiveMinutes();
        $schedule->command('users:date_count')
            ->hourly();
        $schedule->command('users:live_count')
            ->daily();

        
    }
}
