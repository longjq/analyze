<?php

namespace App\Console\Commands;

use App\Libraries\Record;
use App\Models\Cache;
use Illuminate\Console\Command;

class RecordGrid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'record:grid';

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
        $record = new Record();
        $s = time();
        $runTime = date('Y-m-d H:i:s');
        $record->recordGrid();
        $cache = new Cache();
        $d['Time'] = intval(time()) - intval($s);
        $cache->updateValue('run_lives', '执行时间：'.$runTime.', 执行时间耗时：'.$d['Time']);
    }
}
