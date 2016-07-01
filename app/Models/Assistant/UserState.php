<?php

namespace App\Models\Assistant;


class UserState extends Base
{
    protected $table = 'user_states';

    public function user(){
        return $this->belongsTo(\App\Models\Assistant\User::class);
    }

    /**
     * 过去一小时的活跃数
     * @param $hourRange 日期时间区间 [2016-06-13 14:00:00,2016-06-13 14:59:59]
     * @return mixed
     */
    public function lastHourUsers($hourRange){
        return $this->hotCount($hourRange)->count();
    }

    /**
     * 活跃数
     * @param array $hotDay 日期时间区间 [2016-06-13 14:00:00,2016-06-13 14:59:59]
     * @return mixed
     */
    private function hotCount(array $hotDay){
        return $this->whereBetween('updated_at',$hotDay);
    }
}
