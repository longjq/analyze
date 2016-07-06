<?php

namespace App\Console\Commands;

use App\Libraries\CountBrige;
use Illuminate\Console\Command;

class Count extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:count';

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
        $load = new CountBrige();
        $load->lastDayNew();
        $load->lastWeekNew();
        $load->lastMonthNew();
        $load->thisDayNew();
        $load->thisWeekNew();
        $load->thisMonthNew();
        $load->lastDayHot();
        $load->lastWeekHot();
        $load->lastMonthHot();
        $load->thisDayHot();
        $load->thisWeekHot();
        $load->thisMontHot();
    }
}
