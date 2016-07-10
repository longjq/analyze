<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordMonth extends Model
{
    protected $table = 'record_month';
    public $timestamps = false;
    protected $fillable = ['year','month','new','hot'];

    public function lastMonth()
    {
        return $this->where('year',date('Y'))->where('month', date('m'))->first();
    }

    // 保存每周记录数
    public function saveCount($year,$month,$new,$hot)
    {
        $record = $this->firstOrNew([
            'year' => $year,
            'month' => $month
        ]);
        $record->new = $new;
        $record->hot= $hot;
        return $record->save();
    }
}
