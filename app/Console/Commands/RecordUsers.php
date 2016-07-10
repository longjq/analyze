<?php

namespace App\Console\Commands;

use App\Libraries\Record;

use Illuminate\Console\Command;

class RecordUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'record:users';

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
        // 上月
        if (date('d') == 1){
            $record->recordMonth();
        }
        //$record->recordMonth();
        // $record->recordWeek();
        // 上周
        if (date('w') == 1){
            $record->recordWeek();
        }
        // 昨天
        $record->recordDay();
        
    }
}
