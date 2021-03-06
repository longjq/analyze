<?php

namespace App\Console\Commands;

use App\Libraries\LoadAssistant;

use Illuminate\Console\Command;
class UsersPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:package';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'users package';

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

        $load = new LoadAssistant();
        $load->decodePackages(10);

        $d['Time'] = intval(time()) - intval($s);
        $d['memory_after'] = memory_get_usage();

        $cache = new \App\Models\Cache();
        $cache->updateValue('last_query', '最后一次执行时间：'.date('Y-m-d H:i:s').', 
        操作：每五分钟解一次包数据),耗时：'.$d['Time'].'s');
       
    }
}
