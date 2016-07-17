<?php

namespace App\Console\Commands;

use App\Libraries\Refresh;
use App\Models\Cache as CacheData;
use App\Models\UsersList;
use Illuminate\Console\Command;

class RefreshUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $cache = new CacheData();
        $cache->updateValue('total', UsersList::realUsers()->count());
        $refresh = new Refresh();
        $refresh->day();
        $refresh->week();
        $refresh->month();

        $d['Time'] = intval(time()) - intval($s);
        $cache->updateValue('last_query', '最后一次执行时间：'.date('Y-m-d H:i:s').', 
        操作：每30分钟更新，今日、本周、本月, 耗时：'.$d['Time'].'s');

    }
}
