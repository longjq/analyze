<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/24
 * Time: 10:12
 */

namespace App\Repositories;


use DB;
class EventLogCalcRepository
{
    private $model;

    public function __construct(\App\Models\EventLogCalc $model)
    {
        $this->model = $model;
    }

    public function todayList()
    {
        return $this->model->select(DB::raw('package,title,inst_count as sum_inst_count'))
            ->where('add_date', date('Y-m-d'))
            ->orderby('inst_count','desc')->paginate(15);
    }

    public function listByDateRange(array $dateRange)
    {
        return $this->model->select(DB::raw('package,title,SUM(inst_count) as sum_inst_count'))
            ->whereBetween('add_date', $dateRange)
            ->groupBy('package','title')
            ->orderBy('sum_inst_count',`desc`)->paginate(15);
    }
}