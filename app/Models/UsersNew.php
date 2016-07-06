<?php

namespace App\Models;

use App\Libraries\DateHelper;
use Illuminate\Database\Eloquent\Model;
// 新增用户model
class UsersNew extends Model
{
    protected $table = 'user_news';
    public $timestamps = false;
    protected $guarded = ['id'];

    /**
     * 保存小时用户数[新增]
     * @param $count 用户数
     * @param DateHelper $dateHelper 自定义日期对象
     * @return mixed
     */
    public function saveUserCount($count, DateHelper $dateHelper){
        $user = $this->firstOrNew([
            'year' => $dateHelper->getYear(),
            'month' => $dateHelper->getMonth(),
            'day' => $dateHelper->getDay(),
            'row_date' => $dateHelper->getDateFormat()
        ]);
        $field = 'hour'.intval($dateHelper->getHour());
        $user->{$field} = $count;
        return $user->save();
    }
    
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
