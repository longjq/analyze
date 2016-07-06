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
    private $package;
    private $liveUser;
    private $cache;
    private $liveCount;
    public function __construct()
    {
        $this->newUser = new \App\Models\UsersNew();

        $this->listUser = new \App\Models\UsersList();
        $this->liveUser = new \App\Models\UsersLive();
        $this->package = new \App\Models\Package();
        $this->analyze = new \App\Libraries\AnalyzeHelper();
        $this->cache = new \App\Models\Cache();
        $this->liveCount = new \App\Models\LiveCount();
    }

    // 记录每日用户活跃数
    public function liveCount()
    {
        $date = DateHelper::dateTimeRange(date('Y-m-d',strtotime('-1 days')));
        $usersCount = $this->listUser->whereBetween('mtime', $date)->count();
        $this->liveCount->saveLiveCount(new DateHelper(strtotime('-1 days')), $usersCount);
    }

    // 定时解包
    public function decodePackages($len)
    {
        $lists = DB::table('apps_user_list')->where('decode',0)->take($len)->get();
        DB::beginTransaction();
        foreach ($lists as $key => $value) {
            $this->userList->updatePackageItem((array)$value);
        }
        DB::commit();
    }

    /**
     * 计算每日用户活跃数据入库
     */
    public function syncUserLive()
    {
//        $userCount = $this->listUser->count();
//        if ($userCount == 0) return false;
//        $dayDate = DateHelper::fewDaysAgo(2);
//        $dateTime = DateHelper::dateTimeRange($dayDate);
//
//        $this->listUser->usersByTimeRangeCount('ctime', $dateTime[0],$dateTime[1]);
//
//        $usersCount['d1'] = $this->listUser->where('ctime','>=',$dateTime[0])
//            ->where('ctime','<=',$dateTime[1])
//            ->where('mtime', '>=', intval($dateTime[0]) + 86400)
//            ->count();
//        $usersCount['d7'] = $this->listUser->where('ctime','>=',$dateTime[0])
//            ->where('ctime','<=',$dateTime[1])
//            ->where('mtime', '>=', intval($dateTime[0]) + 86400 * 6)
//            ->count();
//        $usersCount['d30'] = $this->listUser->where('ctime','>=',$dateTime[0])
//            ->where('ctime','<=',$dateTime[1])
//            ->where('mtime', '>=', intval($dateTime[0]) + 86400 * 29)
//            ->count();
//	if (count($usersCount) == 0){ return false;}
//        $avgDay = round($usersCount['d1'] / $userCount, 2) * 100;
//        $data = [
//            'row_date' =>  $dayDate,
//            'year' => date('Y', strtotime($dayDate)),
//            'month' => date('m', strtotime($dayDate)),
//            'day' => date('d', strtotime($dayDate)),
//            'live' => $avgDay
//        ];
//        return DB::insert("insert into assistant_users_live(row_date,year,month,day,live)
//values('{$data['row_date']}','{$data['year']}','{$data['month']}','{$data['day']}','{$data['live']}') ON DUPLICATE KEY UPDATE live = values(`live`)");

    }


    /**
     * 更新用户，今日，本周，本月活跃数
     */
    public function nowLives()
    {
        $today = DateHelper::dateTimeRange(DateHelper::thisToday());
        $weekDateRange = DateHelper::thisWeekTime();
        $monthDateRange = DateHelper::thisMonthTime();
        
        // 过去一小时，计算用户活跃数，今日，本周，本月
        $todayCount = $this->listUser->userDateRangeCount('mtime',$today);
        $weekCount = $this->listUser->userDateRangeCount('mtime',$weekDateRange);
        $monthCount = $this->listUser->userDateRangeCount('mtime',$monthDateRange);

        $this->cache->updateValue('now_hot_day', $todayCount);
        $this->cache->updateValue('now_hot_week', $weekCount);
        $this->cache->updateValue('now_hot_month', $monthCount);
    }

    /**
     * 统计新增用户数
     * @return array
     */
    public function saveHourUserCount(){
        // 过去一小时的日期时间区间
        $dateHelper = new DateHelper(strtotime('-1 hour'));
        $hourRange = DateHelper::hourRange($dateHelper->getTimestamp());

        // 过去一小时，新增用户数保存
        $hourNewCount = $this->listUser->userDateRangeCount('ctime', $hourRange);
        $this->newUser->saveUserCount($hourNewCount, $dateHelper);
    }

    /**
     * 更新用户新增数，今日、本周、本月
     */
    public function nowNews()
    {
        // 今日
        $todayUsers= $this->newUser->usersBydate(DateHelper::thisToday());
        $todayCount = $this->analyze->anayzleMonthCount($todayUsers);
        $this->cache->updateValue('now_new_day', $todayCount[0]);

        // 本周
        $weekDateTemp = DateHelper::thisWeek();
        $weekDateRang[] = $weekDateTemp['start'];
        $weekDateRang[] = $weekDateTemp['end'];
        $weekUsers = $this->newUser->usersByDateRange($weekDateRang);
        $weekCount = $this->analyze->anayzleMonthCount($weekUsers);
        $this->cache->updateValue('now_new_week', array_sum($weekCount));

        // 本月
        $monthDateTemp = DateHelper::thisMonth(time());
        $monthDateRange[] = $monthDateTemp['start'];
        $monthDateRange[] = $monthDateTemp['end'];
        $monthUsers = $this->newUser->usersByDateRange($monthDateRange);
        $monthCount = $this->analyze->anayzleMonthCount($monthUsers);
        $this->cache->updateValue('now_new_month', array_sum($monthCount));

    }

    /**
     * 更新用户历史活跃数据，昨天、上周、上月
     */
    public function historyLives()
    {
        // 昨日活跃
        $countHotDayLast = $this->liveCount->dayCount(new DateHelper(strtotime('-1 day')));
        $this->cache->updateValue('last_hot_day', $countHotDayLast);

        // 上周活跃
        $countHotWeekLast = $this->liveCount->dayRangeCount(DateHelper::lastNWeek(time(), 1));
        $this->cache->updateValue('last_hot_week', $countHotWeekLast);

        // 上月活跃
        $countHotMonthLast = $this->liveCount->dayRangeCount(DateHelper::lastMonth(time()));
        $this->cache->updateValue('last_hot_month', $countHotMonthLast);
    }

    /**
     * 更新用户历史新增数，昨天、上周、上个月
     */
    public function historyNews()
    {
        // 昨日新增
        $yesterday = new DateHelper(strtotime('-1 day'));
        $yesterdayUsersNew = $this->newUser->where('row_date', $yesterday->getDateFormat())->get();
        $dayCount = $this->analyze->anayzleMonthCount($yesterdayUsersNew);
        $this->cache->updateValue('last_new_day', $dayCount[0]);

        // 上周新增
        $weekUsers = $this->newUser->usersByDateRange(DateHelper::lastNWeek(time(), 1));
        $weekCount = $this->analyze->anayzleMonthCount($weekUsers);
        $this->cache->updateValue('last_week_day', array_sum($weekCount));

        // 本月新增
        $monthUsers = $this->newUser->usersByDateRange(DateHelper::lastMonth(time()));
        $monthCount = $this->analyze->anayzleMonthCount($monthUsers);
        $this->cache->updateValue('last_month_day', array_sum($monthCount));
    }


    /**
     * 更新今日留存数
     */
    public function liveToday()
    {
        $data = [];
        // 次日新增用户的留存率
        $dayDate = DateHelper::fewDaysAgo(2);
        $dateTime = DateHelper::dateTimeRange($dayDate);
        $users = $this->listUser->userDateRange('ctime', $dateTime)->get();
        $avgDay = $this->analyze->liveByTimeGap($users, 86400);
        $data['live'] = $avgDay;
        $data['live_date'] = $dayDate;
        $this->cache->updateValue('now_avg_day', $avgDay);
        $this->cache->updateValue('now_avg_day_date', $dayDate);

        $weekDate = DateHelper::fewDaysAgo(7);
        $dateTime = DateHelper::dateTimeRange($weekDate);
        $users = $this->listUser->userDateRange('ctime', $dateTime)->get();
        $avgWeek = $this->analyze->liveByTimeGap($users, 86400 * 6);
        $data['d7'] = $avgWeek;
        $data['d7_date'] = $weekDate;
        $this->cache->updateValue('now_avg_week', $avgWeek);
        $this->cache->updateValue('now_avg_week_date', $weekDate);

        $fifteenDate = DateHelper::fewDaysAgo(15);
        $dateTime = DateHelper::dateTimeRange($fifteenDate);
        $users = $this->listUser->userDateRange('ctime', $dateTime)->get();
        $avgFifteen = $this->analyze->liveByTimeGap($users, 86400 * 14);
        $data['d15'] = $avgFifteen;
        $data['d15_date'] = $fifteenDate;

        // 30天前新增用户的留存率
        $monthDate = DateHelper::fewDaysAgo(30);
        $dateTime = DateHelper::dateTimeRange($monthDate);
        $users = $this->listUser->userDateRange('ctime', $dateTime)->get();
        $avgMonth = $this->analyze->liveByTimeGap($users, 86400 * 29);
        $data['d30'] = $avgMonth;
        $data['d30_date'] = $monthDate;
        $this->cache->updateValue('now_avg_month', $avgMonth);
        $this->cache->updateValue('now_avg_month_date', $monthDate);

        // DB::insert("INSERT into assistant_users_live (`row_date`,`year`,`month`,`day`,`live`) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE live=VALUES(`live`)",[date('Y-m-d', $dateTime[0]), date('Y', $dateTime[0]), date('m', $dateTime[0]), date('d', $dateTime[0]), $avgDay]);
        $this->liveUser->saveLiveDate(new DateHelper(time()), $data);
    }

    /**
     * 每天记录历史留存率
     */
    public function liveHistory()
    {
        $lastDay = DateHelper::fewDaysAgo(2);
        $last7day = DateHelper::fewDaysAgo(7);
        $last30day = DateHelper::fewDaysAgo(30);



        $usersList =  $this->liveUser->usersByDateRange('2016-07-01', date('Y-m-d'));
        $livePer = $this->analyze->liveAvg($usersList);


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