<?php

namespace App\Models;

use App\Libraries\DateHelper;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UserHourSave;
// 新增用户model
class UsersNew extends Model
{
    use UserHourSave;
    protected $table = 'assistant_users_new';
    public $timestamps = false;
    protected $guarded = ['id'];

    /**
     * 指定日期的用户数
     * @param $date
     * @return mixed
     */
    public function usersBydate($date)
    {
        return $this->where('row_date', $date)->get();
    }

    /**
     * 日期区间的用户数
     * @param $dateRange
     * @return mixed
     */ 
    public function usersByDateRange($dateRange)
    {
        return $this->whereBetween('row_date', $dateRange)->get();
    }
}
