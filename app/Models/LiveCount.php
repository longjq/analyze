<?php

namespace App\Models;

use App\Libraries\DateHelper;
use App\Traits\UserDaySave;
use Illuminate\Database\Eloquent\Model;

class LiveCount extends Model
{
    use UserDaySave;
    protected $table = 'live_count';
    public $timestamps = false;
    protected $fillable = ['row_date','year','month','day','live_count'];

    // 获取某日期区间的live的总数
    public function dayRangeCount(array $dateRange)
    {
        return $this->whereBetween('row_date', $dateRange)
            ->sum('live_count');
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
