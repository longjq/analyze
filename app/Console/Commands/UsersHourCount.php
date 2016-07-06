<?php

namespace App\Console\Commands;

use App\Libraries\LoadAssistant;
use Illuminate\Console\Command;

class UsersHourCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:hour';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'users hour';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $load = new LoadAssistant();
        // 记录每小时新增用户数
        $load->saveHourUserCount();
        // 更新今日、本周、本月的用户数
        $load->nowNews();

        \App\Models\Cache::where('key','last_query')->update([
            'value' => '最后一次执行时间：'.date('Y-m-d H:i:s').', 操作：每小时记录新增和活跃(saveHourUserCount)'
        ]);
    }
}
