<?php

namespace App\Models;

use App\Libraries\DateHelper;

use Illuminate\Database\Eloquent\Model;

class CountHot extends Model
{

    protected $table = 'count_hots';
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
    // =====================================================
    // 获取某日期区间的live的总数
    public function dayRangeCount(array $dateRange)
    {
        return $this->whereBetween('row_date', $dateRange)
            ->sum('live_count');
    }

    public function usersByDateRange(DateHelper $dateHelper)
    {
        return $this->where('year', $dateHelper->getYear())
            ->where('month', $dateHelper->getMonth())
            ->get();
    }

    // 获取某天的live数
    public function dayCount(DateHelper $dateHelper)
    {
        $dayCount = $this->where('row_date', $dateHelper->getDateFormat())->select('live_count')->first();
        return isset($dayCount) ? $dayCount->live_count : 0;
    }

    // 保存每日用户活跃数
    public function saveLiveCount(DateHelper $dateHelper, $liveCount)
    {
        $live = $this->firstOrNew([
            'row_date' => $dateHelper->getDateFormat(),
            'year' => $dateHelper->getYear(),
            'month' => $dateHelper->getMonth(),
            'day' => $dateHelper->getDay(),
        ]);
        $live->live_count = $liveCount;
        return $live->save();
    }
}
