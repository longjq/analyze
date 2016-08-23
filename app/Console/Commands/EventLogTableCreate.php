<?php

namespace App\Console\Commands;

use App\Libraries\EventLogCreate;
use App\Libraries\EventLogTableManager;
use Illuminate\Console\Command;

class EventLogTableCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eventlog:create';

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
        $eventTable = new EventLogTableManager();
        $this->info($eventTable->createNewTable());
    }
}
