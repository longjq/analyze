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
        $rows = $load->decodePackages(100);
        $this->info('Rows:' . $rows);

        $d['Time'] = intval(time()) - intval($s);
        $d['memory_after'] = memory_get_usage();
        \App\Libraries\LogInfo::info('解析包名(packages),',$d);
        // \App\Libraries\Cache::getInstance()->setString('last', '最后一次执行时间：'.date('Y-m-d H:i:s').', 操作：解析包名(packages)');
        \App\Models\Cache::where('key','last_query')->update([
            'value' => '最后一次执行时间：'.date('Y-m-d H:i:s').', 操作：解析包名(packages)'
        ]);
    }
}
