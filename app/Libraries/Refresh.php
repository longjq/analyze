<?php
/**
 * Created by PhpStorm.
 * User: longjq
 * Date: 2016/7/8
 * Time: 11:14
 */

namespace App\Libraries;


use App\Models\UsersList;
use App\Models\Cache as CacheData;
class Refresh
{
    private $usersList;
    private $cache;
    public function __construct()
    {
        $this->usersList = new UsersList();
        $this->cache = new CacheData();
    }

    public function day()
    {
        // 今日新增、活跃
        $countNews = $this->usersList
            ->userDateRange('ctime', DateHelper::thisTodayTime())
            ->realUsers()
            ->count();
        $countHots = $this->usersList
            ->userDateRange('mtime', DateHelper::thisTodayTime())
            ->realUsers('mtime')
            ->count();
        $this->cache->updateValue('now_new_day', $countNews);
        $this->cache->updateValue('now_hot_day', $countHots);
    }

    public function week()
    {
        // 本周新增、活跃
        $countNews = $this->usersList
            ->userDateRange('ctime', DateHelper::thisWeekTime())
            ->realUsers()
            ->count();
        $countHots = $this->usersList
            ->userDateRange('mtime', DateHelper::thisWeekTime())
            ->realUsers('mtime')
            ->count();
        $this->cache->updateValue('now_new_week', $countNews);
        $this->cache->updateValue('now_hot_week', $countHots);
    }

    public function month()
    {
        // 本月新增、活跃
        $countNews = $this->usersList
            ->userDateRange('ctime', DateHelper::thisMonthTime())
            ->realUsers()
            ->count();
        $countHots = $this->usersList
            ->userDateRange('mtime', DateHelper::thisMonthTime())
            ->realUsers('mtime')
            ->count();
        $this->cache->updateValue('now_new_month', $countNews);
        $this->cache->updateValue('now_hot_month', $countHots);
    }
}