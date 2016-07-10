<?php

namespace App\Console;

use Illuminate\Console\Command;
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
        Commands\RecordUsers::class,           // 每日凌晨5分更新，昨日、上周、上月数据
        Commands\RecordLives::class,           // 每日凌晨10分，更新当日的次日、7天、15天、39天存活率
        Commands\RecordLivesAvg::class,        // 每日凌晨15分，更新至历史的7天、15天、39天平均存活率
        Commands\RefreshUsers::class,          // 每30分钟更新，今日、本周、本月
        Commands\UsersPackage::class,          // 
        Commands\UnpackPackages::class,        // 新版本解包
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 每日凌晨5分更新，昨日、上周、上月数据
        $schedule->command('record:users')
            ->dailyAt('0:5');

        // 每日凌晨10分，更新当日的次日、7天、15天、39天存活率
        $schedule->command('record:lives')
            ->dailyAt('0:10');

        // 每日凌晨15分，更新至历史的7天、15天、39天平均存活率
        $schedule->command('record:lives_avg')
            ->dailyAt('0:15');

        // 每30分钟更新，今日、本周、本月
        $schedule->command('refresh:users')
            ->everyThirtyMinutes();

        // 每五分钟解一次包数据
        $schedule->command('unpack:packages')
            ->everyFiveMinutes();
        
    }
}
