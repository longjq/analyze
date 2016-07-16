<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UnpackPackages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unpack:packages';

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
        $unpack = new \App\Libraries\UnpackPackages();
        
        $unpack->unpack(1000);
        $d['Time'] = intval(time()) - intval($s);
        $cache = new \App\Models\Cache();
        $cache->updateValue('last_query', '最后一次执行时间：'.date('Y-m-d H:i:s').', 
        操作：每五分钟解一次包数据),耗时：'.$d['Time'].'s');
        
    }
}
