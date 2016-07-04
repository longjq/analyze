<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

class UsersRemoveData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'users remove';

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
        $load = new \App\Libraries\LoadCache();
        // $load->removeLastDayData();
        $d['Time'] = intval(time()) - intval($s);
        $d['memory_after'] = memory_get_usage();
        // \App\Libraries\LogInfo::info('删除昨日文件夹(removeLastDayData),',$d);
//        \App\Models\Cache::where('key','last_query')->update([
//            'value' => '最后一次执行时间：'.date('Y-m-d H:i:s').', 操作：删除昨日文件夹(removeLastDayData)'
//        ]);
    }
}
