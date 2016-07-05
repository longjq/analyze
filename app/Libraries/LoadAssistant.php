<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/3
 * Time: 15:12
 */

namespace App\Libraries;
use DB;

class LoadAssistant
{
    private $listUser;
    private $newUser;
    private $hotUser;
    private $package;
    private $liveUser;
    public function __construct()
    {
        $this->newUser = new \App\Models\UsersNew();
        $this->hotUser = new \App\Models\UsersHot();
        $this->listUser = new \App\Models\UsersList();
        $this->liveUser = new \App\Models\UsersLive();
        $this->package = new \App\Models\Package();
        $this->analyze = new \App\Libraries\AnalyzeHelper();
    }

    /**
     * 计算每日用户活跃数据入库
     */
    public function syncUserLive()
    {
        $userCount = $this->listUser->count();
        if ($userCount == 0) return false;
        $dayDate = DateHelper::fewDaysAgo(2);
        $dateTime = DateHelper::dateTimeRange($dayDate);

        $users = $this->listUser->where('ctime','>=',$dateTime[0])
            ->where('ctime','<=',$dateTime[1])
            ->where('mtime', '>=', intval($dateTime[0]) + 86400)
            ->get();
	if (count($users) == 0){ return false;}
        $avgDay = round(count($users) / $userCount, 2) * 100;
        $data = [
            'row_date' =>  $dayDate,
            'year' => date('Y', strtotime($dayDate)),
            'month' => date('m', strtotime($dayDate)),
            'day' => date('d', strtotime($dayDate)),
            'live' => $avgDay
        ];
        return DB::insert("insert into assistant_users_live(row_date,year,month,day,live)
values('{$data['row_date']}','{$data['year']}','{$data['month']}','{$data['day']}','{$data['live']}') ON DUPLICATE KEY UPDATE live = values(`live`)");

    }

    // 用户，今日，本周，本月活跃数定时任务
    public function userLive()
    {
        $today = DateHelper::dateTimeRange(DateHelper::thisToday());
        $week = DateHelper::thisWeekTime();
        $mouth = DateHelper::thisMonthTime();
        
        // 过去一小时，计算用户活跃数，今日，本周，本月
        $todayCount = $this->listUser->usersByTimeRangeCount('mtime',$today[0],$today[1]);
        $weekCount = $this->listUser->usersByTimeRangeCount('mtime',$week[0],$week[1]);
        $mouthCount = $this->listUser->usersByTimeRangeCount('mtime',$mouth[0],$mouth[1]);

        \App\Models\Cache::where('key', 'now_hot_day')->update([
            'value' => $todayCount
        ]);

        \App\Models\Cache::where('key', 'now_hot_week')->update([
            'value' => $weekCount
        ]);

        \App\Models\Cache::where('key', 'now_hot_month')->update([
            'value' => $mouthCount
        ]);

        // $dbHots = $this->hotUser->saveUserCount($hourHotCount, $dateHelper);
    }


    /**
     * 同步新增
     * @return array
     */
    public function saveHourUserCount(){
        // 过去一小时的日期时间区间
        $dateHelper = new DateHelper(strtotime('-1 hour'));
        $hourRange = DateHelper::hourRange($dateHelper->getTimestamp());
        $start = strtotime($hourRange[0]);
        $end = strtotime($hourRange[1]);

        // 过去一小时，新增用户数
        $hourNewCount = $this->listUser->usersByTimeRangeCount('ctime',$start,$end);
        $dbNews = $this->newUser->saveUserCount($hourNewCount, $dateHelper);

        return [
            'dbNews' => $dbNews,
            'newCount' => $hourNewCount,
            //'dbHots' => $dbHots,
            //'hotCount' => $hourHotCount
        ];
    }

    // 活跃数据
    public function userHotRefresh()
    {
        // 今日活跃
        $today = DateHelper::thisToday();
        $countHotDay = $this->hotUser->where('row_date', $today)->get();
        $countHotDay = $this->analyze->anayzleMonthCount($countHotDay);
        \App\Models\Cache::where('key', 'now_hot_day')->update([
            'value' => $countHotDay[0],
        ]);

        // 本周活跃
        $thisWeek = DateHelper::thisWeek();
        $countHotWeek = $this->hotUser->where('row_date', '>=', $thisWeek['start'])
            ->where('row_date', '<=', $thisWeek['end'])->get();
        $countHotWeek = $this->analyze->anayzleMonthCount($countHotWeek);
        \App\Models\Cache::where('key', 'now_hot_week')->update([
            'value' => array_sum($countHotWeek),
        ]);

        // 本月活跃
        $thisMonth = DateHelper::thisMonth(time());
        $countHotMonth = $this->hotUser->where('row_date', '>=', $thisMonth['start'])
            ->where('row_date', '<=', $thisMonth['end'])->get();
        $countHotMonth = $this->analyze->anayzleMonthCount($countHotMonth);
        \App\Models\Cache::where('key', 'now_hot_month')->update([
            'value' => array_sum($countHotMonth),
        ]);

        // 昨日活跃
        $yesterday = new DateHelper(strtotime('-1 day'));
        $yesterdayUsersHot = $this->hotUser->where('row_date', $yesterday->getDateFormat())->get();
        $countHotDay = $this->analyze->anayzleMonthCount($yesterdayUsersHot);
        \App\Models\Cache::where('key', 'last_hot_day')->update([
            'value' => $countHotDay[0],
        ]);

        // 上周活跃
        $lastWeekRange = DateHelper::lastNWeek(time(), 1);
        $lastWeekUsersHot = $this->hotUser->usersByDateRange($lastWeekRange[0], $lastWeekRange[1]);
        $countHotWeek = $this->analyze->anayzleMonthCount($lastWeekUsersHot);
        \App\Models\Cache::where('key', 'last_hot_week')->update([
            'value' => array_sum($countHotWeek),
        ]);

        // 上月活跃
        $lastMonth = new DateHelper(strtotime('-1 month'));
        $lastMonthUsers = $this->hotUser->where('month', $lastMonth->getMonth())->where('year', $lastMonth->getYear())->get();
        $countMonthUsersHot = $this->analyze->anayzleMonthCount($lastMonthUsers);
        \App\Models\Cache::where('key', 'last_hot_month')->update([
            'value' => array_sum($countMonthUsersHot),
        ]);
    }

    // 新增数据
    public function userNewRefresh()
    {
        // 今日新增
        $today = DateHelper::thisToday();
        $countNewDay = $this->newUser->where('row_date', $today)->get();
        $countNewDay = $this->analyze->anayzleMonthCount($countNewDay);
        \App\Models\Cache::where('key', 'now_new_day')->update([
            'value' => $countNewDay[0],
        ]);

        // 本周新增
        $thisWeek = DateHelper::thisWeek();
        $countNewWeek = $this->newUser->where('row_date', '>=', $thisWeek['start'])
            ->where('row_date', '<=', $thisWeek['end'])->get();
        $countNewWeek = $this->analyze->anayzleMonthCount($countNewWeek);
        \App\Models\Cache::where('key', 'now_new_week')->update([
            'value' => array_sum($countNewWeek),
        ]);

        // 本月新增
        $thisMonth = DateHelper::thisMonth(time());
        $countNewMonth = $this->newUser->where('row_date', '>=', $thisMonth['start'])
            ->where('row_date', '<=', $thisMonth['end'])->get();
        $countNewMonth = $this->analyze->anayzleMonthCount($countNewMonth);
        \App\Models\Cache::where('key', 'now_new_month')->update([
            'value' => array_sum($countNewMonth),
        ]);

        // 昨日新增
        $yesterday = new DateHelper(strtotime('-1 day'));
        $yesterdayUsersNew = $this->newUser->where('row_date', $yesterday->getDateFormat())->get();
        $countNewDay = $this->analyze->anayzleMonthCount($yesterdayUsersNew);
        \App\Models\Cache::where('key', 'last_new_day')->update([
            'value' => $countNewDay[0],
        ]);

        // 上周新增
        $lastWeekRange = DateHelper::lastNWeek(time(), 1);
        $lastWeekUsersNew = $this->newUser->usersByDateRange($lastWeekRange[0], $lastWeekRange[1]);
        $countNewWeek = $this->analyze->anayzleMonthCount($lastWeekUsersNew);
        \App\Models\Cache::where('key', 'last_new_week')->update([
            'value' => array_sum($countNewWeek),
        ]);

        // 上月新增
        $lastMonth = new DateHelper(strtotime('-1 month'));

        $lastMonthUsersNew = $this->newUser->where('month', $lastMonth->getMonth())->where('year', $lastMonth->getYear())->get();

        $countMonth = $this->analyze->anayzleMonthCount($lastMonthUsersNew);

        \App\Models\Cache::where('key', 'last_new_month')->update([
            'value' => array_sum($countMonth),
        ]);
    }

    // 今日留存数
    public function liveToday()
    {
        // 次日新增用户的留存率
        $dayDate = DateHelper::fewDaysAgo(2);
        $dateTime = DateHelper::dateTimeRange($dayDate);
        $users = $this->listUser->where('ctime', '>=', $dateTime[0])->where('ctime', '<=', $dateTime[1])->get();
        $avgDay = $this->analyze->liveByTimeGap($users, 86400);

        DB::insert("INSERT into assistant_users_live (`row_date`,`year`,`month`,`day`,`live`) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE live=VALUES(`live`)",
            [date('Y-m-d', $dateTime[0]), date('Y', $dateTime[0]), date('m', $dateTime[0]), date('d', $dateTime[0]), $avgDay]);
        \App\Models\Cache::where('key', 'now_avg_day')->update([
            'value' => $avgDay,
        ]);
        \App\Models\Cache::where('key', 'now_avg_day_date')->update([
            'value' => $dayDate,
        ]);
        // 7天前新增用户的留存率
        $weekDate = DateHelper::fewDaysAgo(7);
        $dateTime = DateHelper::dateTimeRange($weekDate);
        $users = $this->listUser->where('ctime', '>=', $dateTime[0])->where('ctime', '<=', $dateTime[1])->get();
        $avgWeek = $this->analyze->liveByTimeGap($users, 86400 * 6);
        \App\Models\Cache::where('key', 'now_avg_week')->update([
            'value' => $avgWeek,
        ]);
        \App\Models\Cache::where('key', 'now_avg_week_date')->update([
            'value' => $weekDate,
        ]);
        // 30天前新增用户的留存率
        $monthDate = DateHelper::fewDaysAgo(30);
        $dateTime = DateHelper::dateTimeRange($monthDate);
        $users = $this->listUser->where('ctime', '>=', $dateTime[0])->where('ctime', '<=', $dateTime[1])->get();
        $avgMonth = $this->analyze->liveByTimeGap($users, 86400 * 29);
        \App\Models\Cache::where('key', 'now_avg_month')->update([
            'value' => $avgMonth,
        ]);
        \App\Models\Cache::where('key', 'now_avg_month_date')->update([
            'value' => $monthDate,
        ]);
    }

    // 历史留存平均
    public function liveHistory()
    {
        // 历史留存率平均值
        $total = $this->listUser->count();
        // 次日
        $lastAvgDay = $this->listUser->where('mtime', '>', DB::raw("(UNIX_TIMESTAMP(FROM_UNIXTIME(ctime, '%Y-%m-%d')) + 86400)"))->count();

        // 7日
        $lastAvg7Day = $this->listUser->where('mtime', '>', DB::raw('(UNIX_TIMESTAMP(FROM_UNIXTIME(ctime, \'%Y-%m-%d\')) + 86400 * 6)'))->count();
        // 30日
        $lastAvg30Day = $this->listUser->where('mtime', '>', DB::raw('(UNIX_TIMESTAMP(FROM_UNIXTIME(ctime, \'%Y-%m-%d\')) + 86400 * 29)'))->count();
        if( $lastAvgDay != 0 && $total != 0){
            \App\Models\Cache::where('key', 'last_avg_day')->update([
                'value' => round($lastAvgDay / $total, 3) * 100,
            ]);
        }
        if ($lastAvg7Day != 0 && $total != 0){
            \App\Models\Cache::where('key', 'last_avg_seven_day')->update([
                'value' => round($lastAvg7Day / $total, 3) * 100,
            ]);
        }
        if ($lastAvg30Day !=0 && $total != 0) {
            \App\Models\Cache::where('key', 'last_avg_thirty_day')->update([
                'value' => round($lastAvg30Day / $total, 3) * 100,
            ]);
        }
    }

    // 系统总用数
    public function userTotal()
    {
        $userCount = $this->listUser->count();
        \App\Models\Cache::where('key', 'total')->update(['value' => $userCount]);
    }
}