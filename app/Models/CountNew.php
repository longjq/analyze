<?php

namespace App\Models;

use App\Libraries\DateHelper;
use Illuminate\Database\Eloquent\Model;

class CountNew extends Model
{
    protected $table = 'count_news';
    public $timestamps = false;
    protected $fillable = ['row_date','year','month','day','count'];

    // 指定日期区间数据行列表
    public function dataByDateRange(array $dateRange)
    {
        return $this->whereBetween('row_date', $dateRange)->get();
    }

    // 指定日期数据行
    public function itemByDate($date)
    {
        return $this->where('row_date', $date)->sum('count');
    }

    // 指定日期区间数据行列表
    public function itemByDateRange(array $dateRange)
    {
        return $this->whereBetween('row_date', $dateRange)->sum('count');
    }

    // 指定日期用户总数
    public function countByDate($date)
    {
        $this->where('row_date', $date)->count();
    }

    // 日期区间用户总数
    public function countByDateRange(array $dateRange)
    {
        $this->whereBetween('row_date', $dateRange)->count();
    }

    // 保存日期新增用户数
    public function saveDayData(DateHelper $dateHelper, $count)
    {
        $model = $this->firstOrNew([
            'row_date' => $dateHelper->getDateFormat(),
            'year' => $dateHelper->getYear(),
            'month' => $dateHelper->getMonth(),
            'day' => $dateHelper->getDay()
        ]);
        
        $model->count = $count;
        $model->save();
    }
}
