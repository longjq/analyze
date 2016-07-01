<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/14
 * Time: 3:44
 */

namespace App\Traits;


use App\Libraries\DateHelper;

trait UserHourSave
{
    /**
     * 保存小时用户数[新增|活跃]
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