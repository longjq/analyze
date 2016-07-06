<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/7
 * Time: 2:04
 */

namespace App\Libraries;


use App\Models\Cache;
use App\Models\CountHot;
use App\Models\CountNew;
use App\Models\UserTempHot;
use App\Models\UserTempImei;

class CountBrige
{
    private $imei;
    private $userHot;
    private $countNew;
    private $countHot;

    private $cache;
    public function __construct()
    {
        $this->imei = new UserTempImei();
        $this->userHot = new UserTempHot();

        $this->countNew = new CountNew();
        $this->countHot = new CountHot();

        $this->cache = new Cache();
    }

    // 昨日新增
    public function lastDayNew()
    {
        $count = $this->countNew->itemByDate(DateHelper::lastDay());
        $this->cache->updateValue('last_new_day', $count);
    }

    // 上周新增
    public function lastWeekNew()
    {
        $count = $this->countNew->itemByDateRange(DateHelper::lastNWeek(time(),1));
        $this->cache->updateValue('last_new_week', $count);
    }

    // 上月新增
    public function lastMonthNew()
    {
        $count = $this->countNew->itemByDateRange(DateHelper::lastMonth(time()));
        $this->cache->updateValue('last_new_month', $count);
    }

    // 今日新增
    public function thisDayNew()
    {
        $count = $this->imei->countByDate(DateHelper::thisToday());
        $this->cache->updateValue('now_new_day', $count);
    }

    // 本周新增
    public function thisWeekNew()
    {
        $todayCount = $this->imei->countByDate(DateHelper::thisToday());
        $weekCount = $this->countNew->itemByDateRange(DateHelper::thisWeek());
        $this->cache->updateValue('now_new_week', intval($todayCount) + intval($weekCount));
    }

    // 本月新增
    public function thisMonthNew()
    {
        $todayCount = $this->imei->countByDate(DateHelper::thisToday());
        $monthCount = $this->countNew->itemByDateRange(DateHelper::thisMonth(time()));
        $this->cache->updateValue('now_new_month', intval($todayCount) + intval($monthCount));
    }

    // 昨日活跃
    public function lastDayHot()
    {
        $count = $this->countHot->itemByDate(DateHelper::lastDay());
        $this->cache->updateValue('last_hot_day', $count);
    }

    // 上周活跃
    public function lastWeekHot()
    {
        $count = $this->countHot->itemByDateRange(DateHelper::lastNWeek(time(),1));
        $this->cache->updateValue('last_hot_week', $count);
    }

    // 上月活跃
    public function lastMonthHot()
    {
        $count = $this->countHot->itemByDateRange(DateHelper::lastMonth(time()));
        $this->cache->updateValue('last_hot_month', $count);
    }

    // 今日活跃
    public function thisDayHot()
    {
        $count = $this->userHot->countByDate(DateHelper::thisToday());
        $this->cache->updateValue('now_hot_day', $count);
    }

    // 本周活跃
    public function thisWeekHot()
    {
        $todayCount = $this->userHot->countByDate(DateHelper::thisToday());
        $weekCount = $this->countHot->itemByDateRange(DateHelper::thisWeek());
        $this->cache->updateValue('now_hot_week', intval($todayCount) + intval($weekCount));
    }

    // 本月活跃
    public function thisMontHot()
    {
        $todayCount = $this->userHot->countByDate(DateHelper::thisToday());
        $monthCount = $this->countHot->itemByDateRange(DateHelper::thisMonth(time()));
        $this->cache->updateValue('now_hot_month', intval($todayCount) + intval($monthCount));
    }
}