<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

class UsersDash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:dash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'users dash';
    private $loadAssistant;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->loadAssistant = new \App\Libraries\LoadAssistant();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $s = time();
        $d['memory_before'] = memory_get_usage();
        $load = new \App\Libraries\LoadCache();

        // 系统总用数
        $this->loadAssistant->userTotal();
        // 今日
        $this->loadAssistant->userNewRefresh();
        // 历史
        $this->loadAssistant->userHotRefresh();
        // 历史留存
        $this->loadAssistant->liveHistory();
        // 今日留存
        $this->loadAssistant->liveToday();

        $d['Time'] = intval(time()) - intval($s);
        $d['memory_after'] = memory_get_usage();
        \App\Libraries\LogInfo::info('Dash 首页刷新,',$d);
        // \App\Libraries\Cache::getInstance()->setString('last', '最后一次执行时间：'.date('Y-m-d H:i:s').', 操作：Dash入库');
        \App\Models\Cache::where('key','last_query')->update([
            'value' => '最后一次执行时间：'.date('Y-m-d H:i:s').', 操作：Dash 首页刷新'
        ]);
    }
}
