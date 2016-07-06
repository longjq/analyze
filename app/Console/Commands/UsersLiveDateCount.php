<?php

namespace App\Console\Commands;

use App\Libraries\LoadAssistant;
use Illuminate\Console\Command;

class UsersLiveDateCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:date_count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'users date_count';

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
        $load = new LoadAssistant();
        // 每小时记录
        $load->userLiveHot();
    }
}
