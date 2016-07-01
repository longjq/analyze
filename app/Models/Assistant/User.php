<?php

namespace App\Models\Assistant;
use DB;

class User extends Base
{
    protected $table = 'users';

    // 返回用户指定列数据
    public function listByField(array $list, $field, $operateStr, ...$val){
        $opearateFun = \App\Libraries\DBOperate::queryFun($operateStr);
        if ($opearateFun == 'whereNull' || $opearateFun == 'whereNotNull'){
            return $this->{$opearateFun}($field)->get($list);
        }
        return $this->{$opearateFun}($field, $operateStr, $val)->get($list);
    }

    // 统计相关字段总人数
    public function groupCount($field){
        return $this->select($field, DB::raw('count('.$field.') as '.$field.'_count'))->groupBy($field)->get();
    }

    // 系统当前总用户数
    public function totalCount(){
        return $this->count();
    }


    /**
     * 过去一小时新增的用户
     * @param $hourRange 日期时间区间 [2016-06-13 14:00:00,2016-06-13 14:59:59]
     * @return mixed
     */
    public function lastHourUsers($hourRange){
        return $this->newUsers($hourRange)->count();
    }

    /**
     * 新建用户
     * @param array $dateRange 日期时间区间 [2016-06-13 14:00:00,2016-06-13 14:59:59]
     * @return mixed
     */
    private function newUsers(array $dateRange){
        return $this->whereBetween('created_at',$dateRange);
    }

    public function userLocation(){
        return $this->hasOne(\App\Models\Assistant\UserLocation::class);
    }

    public function userState(){
        return $this->hasOne(\App\Models\Assistant\UserState::class);
    }
}
