<?php
/**
 * Created by PhpStorm.
 * User: longjq
 * Date: 2016/7/8
 * Time: 9:27
 */

namespace App\Libraries;


use App\Models\RecordDay;
use App\Models\RecordGrid;
use App\Models\RecordMonth;
use App\Models\RecordWeek;
use App\Models\UsersList;
use App\Models\Cache as CacheData;
use App\Models\UsersLive;
use DB;
class Record
{
    private $usersList;
    private $cache;
    private $usersLive;
    private $analyze;

    private $recordDay;
    private $recordWeek;
    private $recordMonth;
    private $recordGrid;
    public function __construct()
    {
        $this->usersList = new UsersList();
        $this->cache = new CacheData();
        $this->usersLive = new UsersLive();
        $this->analyze = new AnalyzeHelper();

        $this->recordDay = new RecordDay();
        $this->recordWeek = new RecordWeek();
        $this->recordMonth = new RecordMonth();
        $this->recordGrid = new RecordGrid();
    }

    // 记录至昨日的历史平均存活率
    public function liveAvg()
    {
        $lives = $this->usersLive->all();
        $avg = $this->analyze->caclLiveAvg($lives);
        $this->cache->updateValue('last_avg_day', $avg['d1']);
        $this->cache->updateValue('last_avg_seven_day',  $avg['d7']);
        $this->cache->updateValue('last_avg_thirty_day',  $avg['d30']);
    }


    // 记录昨日数据入库
    public function recordDay()
    {
        $countNews = $this->usersList
            ->userDateRange('ctime', DateHelper::yesterday())
            ->realUsers()
            ->count();
        $countHots = $this->usersList
            ->userDateRange('mtime', DateHelper::yesterday())
            ->realUsers('mtime')
            ->count();
        if ($this->recordDay->saveCount(DateHelper::lastDay(), $countNews, $countHots)) {
            $this->cache->updateValue('last_new_day', $countNews);
            $this->cache->updateValue('last_hot_day', $countHots);
        }
    }

    // 记录上一周数据入库
    public function recordWeek()
    {
        $week = DateHelper::lastNWeekTime(time(), 1);
        $countNews = $this->usersList
            ->userDateRange('ctime', $week)
            ->realUsers()
            ->count();
        $countHots = $this->usersList
            ->userDateRange('mtime', $week)
            ->realUsers('mtime')
            ->count();
        if ($this->recordWeek->saveCount(date('Y'), DateHelper::weekOfYear("-1 week"), $countNews, $countHots)) {
            $this->cache->updateValue('last_new_week', $countNews);
            $this->cache->updateValue('last_hot_week', $countHots);
        }
    }

    // 记录上一月数据入库
    public function recordMonth()
    {
        $month = DateHelper::lastMonthTime(time());
        $month[0] = strtotime($month[0]);
        $month[1] = strtotime($month[1]);
        $countNews = $this->usersList
            ->userDateRange('ctime', $month)
            ->realUsers()
            ->count();
        $countHots = $this->usersList
            ->userDateRange('mtime', $month)
            ->realUsers('mtime')
            ->count();
        if ($this->recordMonth->saveCount(date('Y'), date('m'), $countNews, $countHots)) {
            $this->cache->updateValue('last_new_month', $countNews);
            $this->cache->updateValue('last_hot_month', $countHots);
        }
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
        $users = $this->usersList->userDateRange('ctime', $dateTime)->realUsers()->get();
        $avgDay = $this->analyze->liveByTimeGap($users, 86400);
        $data['live'] = $avgDay;
        $data['live_date'] = $dayDate;
        $this->cache->updateValue('now_avg_day', $avgDay);
        $this->cache->updateValue('now_avg_day_date', $dayDate);

        $weekDate = DateHelper::fewDaysAgo(7);
        $dateTime = DateHelper::dateTimeRange($weekDate);
        $users = $this->usersList->userDateRange('ctime', $dateTime)->realUsers()->get();
        $avgWeek = $this->analyze->liveByTimeGap($users, 86400 * 6);
        $data['d7'] = $avgWeek;
        $data['d7_date'] = $weekDate;
        $this->cache->updateValue('now_avg_week', $avgWeek);
        $this->cache->updateValue('now_avg_week_date', $weekDate);

        $fifteenDate = DateHelper::fewDaysAgo(15);
        $dateTime = DateHelper::dateTimeRange($fifteenDate);
        $users = $this->usersList->userDateRange('ctime', $dateTime)->realUsers()->get();
        $avgFifteen = $this->analyze->liveByTimeGap($users, 86400 * 14);
        $data['d15'] = $avgFifteen;
        $data['d15_date'] = $fifteenDate;

        // 30天前新增用户的留存率
        $monthDate = DateHelper::fewDaysAgo(30);
        $dateTime = DateHelper::dateTimeRange($monthDate);
        $users = $this->usersList->userDateRange('ctime', $dateTime)->realUsers()->get();
        $avgMonth = $this->analyze->liveByTimeGap($users, 86400 * 29);
        $data['d30'] = $avgMonth;
        $data['d30_date'] = $monthDate;
        $this->cache->updateValue('now_avg_month', $avgMonth);
        $this->cache->updateValue('now_avg_month_date', $monthDate);

        $this->usersLive->saveLiveDate(new DateHelper(time()), $data);
    }

    /**
     * 存活率计算保存至表格
     */
    public function recordGrid()
    {
        // 1. 昨日新增用户保存数据库
        $usersCount = $this->usersList->whereBetween('ctime', DateHelper::yesterday())->realUsers()->count();

        $yesterday = new DateHelper(strtotime(DateHelper::lastDay()));
        RecordGrid::create([
            'row_date' => $yesterday->getDateFormat(),
            'year'     => $yesterday->getYear(),
            'month'    => $yesterday->getMonth(),
            'day'      => $yesterday->getDay(),
            'users'    => $usersCount
        ]);
        // 2. 昨日活跃用户
        $yesterdayHots = $this->usersList->realUsers('mtime')->whereBetween('mtime', DateHelper::yesterday())
            ->where('mtime','>', DB::raw('UNIX_TIMESTAMP(FROM_UNIXTIME(ctime,\'%Y-%m-%d 0:0:0\')) + 86400'))->get();
        // 2.1 遍历昨日活跃用户
        try{
            DB::beginTransaction();
            foreach ($yesterdayHots as $item){
                if ($item->isUserLive(29)){
                    RecordGrid::where('row_date', date('Y-m-d', $item->ctime))->increment('d30');
                    continue;
                }else if ($item->isUserLive(14)){
                    RecordGrid::where('row_date', date('Y-m-d', $item->ctime))->increment('d15');
                    continue;
                }else if ($item->isUserLive(6)){
                    RecordGrid::where('row_date', date('Y-m-d', $item->ctime))->increment('d7');
                    continue;
                }else if ($item->isUserLive(2)){
                    RecordGrid::where('row_date', date('Y-m-d', $item->ctime))->increment('d3');
                    continue;
                }else{
                    RecordGrid::where('row_date', date('Y-m-d', $item->ctime))->increment('d1');
                }
            }
            DB::commit();
        }catch (\Exception $e){
            DB::rollback();
        }

        // 2.2 昨日活跃时间戳 > ( date('Y-m-d', 新增时间戳) + 86400 )
        // 2.3 真，满足次日活跃条件，以新增时间戳格式化出新增日期Y-m-d，并通过Y-m-d查询出表格记录行，在次日活跃字段上+1，跳过此次循环操作，避免其他日也满足的状况
        // 2.4 假，跳过此次循环

    }

}