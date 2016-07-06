<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use DB;
class UsersList extends Model
{
  
    protected $table = 'user_lists';
    public $timestamps = false;
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'row_date','year','month','day',
        'live_date','live','d7_date','d7',
        'd15_date','d15','d30_date','d30'
    ];

    /**
     * 指定日期获取用户总数
     * @param $field
     * @param $dateRange
     * @return mixed
     */
    public function userDateRangeCount($field, $dateRange){
        return $this->userDateRange($field, $dateRange)->count();
    }

    /**
     * 时间范围内
     * @param $field
     * @param $dateRange
     * @return mixed
     */
    public function userDateRange($field, $dateRange){
        return $this->whereBetween($field,$dateRange);
    }

    /**
     * 统计相关字段总人数
     * @param $field
     * @return mixed
     */
    public function groupCount($field){
        return $this->select($field, DB::raw('count('.$field.') as '.$field.'_count,COUNT(1) as null_count'))->groupBy($field)->get();
    }

    /**
     * 存活率比较
     * @param $user
     * @param $timeGap
     * @return bool
     */
    public function isLive($user, $timeGap){
        if (!empty($user->mtime)) {
            if ($user->mtime > ($user->ctime + $timeGap) )  {
                return true;
            }
        }
        return false;
    }

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
}
