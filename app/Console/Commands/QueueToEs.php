<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class QueueToES extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analysis:queue:es {tbName} {count}';

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
        $tbName = $this->argument('tbName');
        $count = $this->argument('count');
        $start = microtime(true);
        \App\Libraries\Queue::getInstance()->run($tbName,$count);
        $timeSignature = json_encode([
            'datetime' => date('Y-m-d H:i:s'),
            'expend_time' => round((microtime(true) - $start), 3)
        ]);
        \App\Libraries\Queue::getInstance()->expendTime($tbName.'_expend_time', $timeSignature);
    }
}
