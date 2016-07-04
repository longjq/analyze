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
        $s = time();
        $d['memory_before'] = memory_get_usage();
        // 记录每小时新增用户数和活跃数
        $load = new LoadAssistant();
        $result = $load->saveHourUserCount();
        $this->info('Hour News Count:'.$result['newCount']);
        $this->info('Hour Hots Count:'.$result['hotCount']);
        $this->info('DB News '.$result['dbNews']);
        $this->info('DB Hots:'.$result['dbHots']);
        $d['Time'] = intval(time()) - intval($s);
        $d['memory_after'] = memory_get_usage();
        \App\Libraries\LogInfo::info('每小时新增和活跃(saveHourUserCount),',$d);
        // \App\Libraries\Cache::getInstance()->setString('last', '最后一次执行时间：'.date('Y-m-d H:i:s').', 操作：每小时记录新增和活跃(saveHourUserCount)');
        \App\Models\Cache::where('key','last_query')->update([
            'value' => '最后一次执行时间：'.date('Y-m-d H:i:s').', 操作：每小时记录新增和活跃(saveHourUserCount)'
        ]);
    }
}
