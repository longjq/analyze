<?php
namespace App\Libraries;

use App\Models\UsersHot;
use App\Models\UsersList;
use App\Models\UsersNew;
use App\Models\Cache;
use DB;
class LoadSchedule
{
    private $userNew;
    private $userHot;
    private $analyze;

    public function __construct()
    {
        $this->userNew = new UsersNew();
        $this->userHot = new UsersHot();
        $this->userList = new UsersList();
        $this->analyze = new AnalyzeHelper();
    }

    public function userTotal()
    {
        $total = $this->userList->count();
        Cache::where('key', 'total')->update([
            'value' => $total
        ]);
    }

    // 今日计算最后存活
    public function liveLast()
    {
        // 次日留存率
        $dayDate = DateHelper::fewDaysAgo(2);
        $users = $this->userList->where('cdate', '>=', $dayDate)
            ->where('cdate', '<=', date('Y-m-d'))->get();
        $avgDay = $this->analyze->liveByTimeGap($users, 86400);
        Cache::where('key', 'now_avg_day')->update([
            'value' => $avgDay
        ]);
        Cache::where('key', 'now_avg_day_date')->update([
            'value' => $dayDate
        ]);
        // 7日留存率
        $weekDate = DateHelper::fewDaysAgo(7);
        $users = $this->userList->where('cdate','>=' , $weekDate)
            ->where('cdate', '<=', date('Y-m-d'))->get();
        $avgWeek = $this->analyze->liveByTimeGap($users, 86400 * 6);
        Cache::where('key', 'now_avg_week')->update([
            'value' => $avgWeek
        ]);
        Cache::where('key', 'now_avg_week_date')->update([
            'value' => $weekDate
        ]);
        // 30日留存率
        $monthDate = DateHelper::fewDaysAgo(30);
        $users = $this->userList->where('cdate', $monthDate)->get();
        $avgMonth = $this->analyze->liveByTimeGap($users, 86400 * 29);
        Cache::where('key', 'now_avg_month')->update([
            'value' => $avgMonth
        ]);
        Cache::where('key', 'now_avg_month_date')->update([
            'value' => $monthDate
        ]);
    }

    // 历史留存率平均值
    public function liveAvg()
    {
        $total = $this->userList->count();
        // 次日
        $lastAvgDay = $this->userList->where('mtime', '>', DB::raw('(UNIX_TIMESTAMP(cdate)+86400)'))->count();
        // 7日
        $lastAvg7Day = $this->userList->where('mtime', '>', DB::raw('(UNIX_TIMESTAMP(cdate)+86400 * 6)'))->count();
        // 30日
        $lastAvg30Day = $this->userList->where('mtime', '>', DB::raw('(UNIX_TIMESTAMP(cdate)+86400 * 29)'))->count();
        Cache::where('key', 'last_avg_day')->update([
            'value' => round($lastAvgDay / $total, 3) * 100
        ]);
        Cache::where('key', 'last_avg_seven_day')->update([
            'value' => round($lastAvg7Day / $total, 3) * 100
        ]);
        Cache::where('key', 'last_avg_thirty_day')->update([
            'value' => round($lastAvg30Day / $total, 3) * 100
        ]);
    }

    // 本月
    public function thisMonth()
    {
        $thisMonth = DateHelper::thisMonth(time());
        // 新增
        $countNewMonth = $this->userNew->where('row_date', '>=', $thisMonth['start'] )
            ->where('row_date', '<=', $thisMonth['end'])->get();
        $count = $this->analyze->anayzleMonthCount($countNewMonth);
        Cache::where('key', 'now_new_month')->update([
            'value' => array_sum($count)
        ]);
        // 活跃
        $countHotMonth = $this->userHot->where('row_date', '>=', $thisMonth['start'] )
            ->where('row_date', '<=', $thisMonth['end'])->get();
        $count = $this->analyze->anayzleMonthCount($countHotMonth);
        Cache::where('key', 'now_hot_month')->update([
            'value' => array_sum($count)
        ]);
    }

    // 本周
    public function thisWeek()
    {
        $thisWeek = DateHelper::thisWeek();
        // 本周新增
        $countNewWeek = $this->userNew->where('row_date', '>=', $thisWeek['start'] )
            ->where('row_date', '<=', $thisWeek['end'])->get();
        $count = $this->analyze->anayzleMonthCount($countNewWeek);
        $count = isset($count[0]) ? $count[0] : 0;
        Cache::where('key', 'now_new_week')->update([
            'value' => $count
        ]);
        // 本周活跃
        $countHotWeek = $this->userHot->where('row_date', '>=', $thisWeek['start'] )
            ->where('row_date', '<=', $thisWeek['end'])->get();
        $count = $this->analyze->anayzleMonthCount($countHotWeek);
        $count = isset($count[0]) ? $count[0] : 0;
        Cache::where('key', 'now_hot_week')->update([
            'value' => $count
        ]);
    }

    // 今日
    public function today()
    {
        $today = DateHelper::thisToday();
        // 今日新增
        $countNewDay = $this->userNew->where('row_date', $today)->get();
        $count = $this->analyze->anayzleMonthCount($countNewDay);
        $count = isset($count[0]) ? $count[0] : 0;
        Cache::where('key', 'now_new_day')->update([
            'value' => $count
        ]);
        // 今日活跃
        $countHotDay = $this->userHot->where('row_date', $today)->get();
        $count = $this->analyze->anayzleMonthCount($countHotDay);
        $count = isset($count[0]) ? $count[0] : 0;
        Cache::where('key', 'now_hot_day')->update([
            'value' => $count
        ]);
    }

    // 昨日
    public function yesterDay()
    {
        $yesterday = new DateHelper(strtotime('-1 day'));
        // 新增
        $yesterdayUsersNew = $this->userNew->where('row_date', $yesterday->getDateFormat())->get();
        $count = $this->analyze->anayzleMonthCount($yesterdayUsersNew);
        $count = isset($count[0]) ? $count[0] : 0;
        Cache::where('key', 'last_new_day')->update([
            'value' => $count,
        ]);
        // 活跃
        $yesterdayUsersHot = $this->userHot->where('row_date', $yesterday->getDateFormat())->get();
        $count = $this->analyze->anayzleMonthCount($yesterdayUsersHot);
        $count = isset($count[0]) ? $count[0] : 0;
        Cache::where('key', 'last_hot_day')->update([
            'value' => $count,
        ]);
    }

    // 上周
    public function lastWeek()
    {
        $lastWeekRange = DateHelper::lastNWeek(time(), 1);
        // 新增
        $lastWeekUsersNew = $this->userNew->usersByDateRange($lastWeekRange[0], $lastWeekRange[1]);
        $countNewWeek = $this->analyze->anayzleMonthCount($lastWeekUsersNew);
        Cache::where('key', 'last_new_week')->update([
            'value' => array_sum($countNewWeek),
        ]);
        // 活跃
        $lastWeekUsersHot = $this->userHot->usersByDateRange($lastWeekRange[0], $lastWeekRange[1]);
        $countHotWeek = $this->analyze->anayzleMonthCount($lastWeekUsersHot);
        Cache::where('key', 'last_hot_week')->update([
            'value' => array_sum($countHotWeek),
        ]);
    }

    // 上月新增
    public function lastMonth()
    {
        $lastMonth = new DateHelper(strtotime('-1 month'));
        // 上月新增
        $lastMonthUsersNew = $this->userNew->where('month', $lastMonth->getMonth())->where('year', $lastMonth->getYear())->get();
        $countMonth = $this->analyze->anayzleMonthCount($lastMonthUsersNew);
        $countMonth = isset($countMonth[0]) ? $countMonth[0] : 0;
        Cache::where('key', 'last_new_month')->update([
            'value' => $countMonth,
        ]);
        // 上月活跃
        $lastMonthUsers = $this->userHot->where('month', $lastMonth->getMonth())->where('year', $lastMonth->getYear())->get();
        $countMonth = $this->analyze->anayzleMonthCount($lastMonthUsers);
        $countMonth = isset($countMonth[0]) ? $countMonth[0] : 0;
        Cache::where('key', 'last_hot_month')->update([
            'value' => $countMonth,
        ]);
    }
}