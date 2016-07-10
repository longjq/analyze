<?php

namespace App\Models;

use App\Libraries\DateHelper;
use Illuminate\Database\Eloquent\Model;

class RecordDay extends Model
{
    protected $table = 'record_day';
    public $timestamps = false;
    protected $fillable = ['row_date','new','hot'];

    // 指定日期区间数据行列表
    public function dataByDateRange(array $dateRange)
    {
        return $this->whereBetween('row_date', $dateRange)->get();
    }

    // 昨日
    public function yesterday()
    {
        return $this->where('row_date', date('Y-m-d', strtotime('-1 days')))->first();
    }

    // 保存昨日记录数
    public function saveCount($rowDate,$new,$hot)
    {
        $record = $this->firstOrNew([
            'row_date' => $rowDate
        ]);
        $record->new = $new;
        $record->hot= $hot;
        return $record->save();
    }
}
