<?php

namespace App\Models;

use App\Traits\UserHourSave;
use Illuminate\Database\Eloquent\Model;
use DB;
class UsersList extends Model
{
    use UserHourSave;
    protected $table = 'assistant_users_list';
    public $timestamps = false;
    protected $primaryKey = 'user_id';
    // 黑名单为空，所有字段都可以被操作
    protected $guarded = [];

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

}
