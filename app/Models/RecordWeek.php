<?php

namespace App\Models;

use App\Libraries\DateHelper;
use Illuminate\Database\Eloquent\Model;

class RecordWeek extends Model
{
    protected $table = 'record_week';
    public $timestamps = false;
    protected $fillable = ['year','week_of_year','new','hot'];

    public function lastWeek()
    {
        return $this->where('week_of_year', DateHelper::weekOfYear("-1 week"))->where('year', date('Y'))->first();
    }

    

    // 保存每周记录数
    public function saveCount($year,$week_of_year,$new,$hot)
    {
        $record = $this->firstOrNew([
            'year' => $year,
            'week_of_year' => $week_of_year
        ]);
        $record->new = $new;
        $record->hot= $hot;
        return $record->save();
    }
}
