<?php

namespace App\Models;

use App\Libraries\DateHelper;
use Illuminate\Database\Eloquent\Model;

class UsersLive extends Model
{
    protected $table = 'assistant_users_live';
    protected $guarded = [];
    public $primaryKey = 'row_date';
    public $timestamps = false;

    /**
     * 保存live数据
     * @param DateHelper $dateHelper
     * @param $data
     */
    public function saveLiveDate(DateHelper $dateHelper, $data)
    {
        $live = $this->firstOrNew([
            'year' => $dateHelper->getYear(),
            'month' => $dateHelper->getMonth(),
            'day' => $dateHelper->getDay(),
            'row_date' => $dateHelper->getDateFormat()
        ]);
        $live->live = $data['live'];
        $live->live_date = $data['live_date'];
        $live->d7 = $data['d7'];
        $live->d7_date = $data['d7_date'];
        $live->d15 = $data['d15'];
        $live->d15_date = $data['d15_date'];
        $live->d30 = $data['d30'];
        $live->d30_date = $data['d30_date'];
        $live->save();
    }

    public function usersByDateRange($start, $end)
    {
        return $this->whereBetween('row_date', [$start, $end])
            ->where('live','!=','0')
            ->whereNotNull('live')
            ->get();
    }
}
