<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/6
 * Time: 0:11
 */

namespace App\Traits;


use App\Libraries\DateHelper;

Trait UserDaySave
{
    public function usersByDateRange(DateHelper $dateHelper)
    {
        return $this->where('year', $dateHelper->getYear())
            ->where('month', $dateHelper->getMonth())
            ->get();
    }
}