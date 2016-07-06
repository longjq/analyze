<?php

namespace App\Console\Commands;

use App\Libraries\UserTempBrige;
use Illuminate\Console\Command;

class UserTemp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:temp';

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
        // 记录每日新增用户总数和每日活动用户总数
        $load = new UserTempBrige();
        $load->load2DB();
    }
}
