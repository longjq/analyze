<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/1
 * Time: 13:31
 */

namespace App\Libraries;


class DateHelper
{
    /**
     * @return bool|string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return bool|string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return bool|string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @return bool|string
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @return bool|string
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @return bool|string
     */
    public function getSec()
    {
        return $this->sec;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return bool|string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    public function getDateTimeFormat()
    {
        return $this->dateTimeFormat;
    }

    private $year;
    private $month;
    private $day;
    private $hour;
    private $min;
    private $sec;
    private $dateFormat;
    private $dateTimeFormat;
    private $timestamp;

    public function __construct($time)
    {
        $this->year = date('Y', $time);
        $this->month = date('m', $time);
        $this->day = date('d', $time);
        $this->hour = date('H', $time);
        $this->min = date('i', $time);
        $this->sec = date('s', $time);
        $this->dateFormat = date('Y-m-d', $time);
        $this->dateTimeFormat = date('Y-m-d H:i:s', $time);
        $this->timestamp = $time;
    }


    /**
     * 指定日期的起止时间，秒为单位
     * @param $timeStamp
     * @return array
     */
    public static function hourRange($timeStamp)
    {
        return [
            strtotime(date('Y-m-d H:00:00', $timeStamp)),
            strtotime(date('Y-m-d H:59:59', $timeStamp)),
        ];
    }

    /**
     * 半小时日期时间区间
     * @return array
     */
    public static function halfHourRange()
    {
        $runTime = strtotime('-30 min'); // 半小时之前的时间戳
        if (date('i', $runTime) < 30) {
            return [
                date('Y-m-d H:00:00', $runTime),
                date('Y-m-d H:29:59', $runTime),
            ];
        }
        return [
            date('Y-m-d H:30:00', $runTime),
            date('Y-m-d H:59:59', $runTime),
        ];
    }

    public static function monthRange($date)
    {
        $firstday = date('Y-m-01', strtotime($date));
        $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        return array($firstday, $lastday);
    }

    public static function dayDiff($start, $end)
    {
        $cle = $end - $start; //得出时间戳差值
        return floor($cle / 3600 / 24); //得出一共多少天
    }

    // 昨天
    public static function yesterday()
    {
        $t = strtotime('-1 day');
        $s = date('Y-m-d 00:00:00', $t);
        $e = date('Y-m-d 23:59:59', $t);
        return ['start' => $s, 'end' => $e];
    }


    /**
     *  * 获取上n周的开始和结束，每周从周一开始，周日结束日期
     *  * @param int $ts 时间戳
     *  * @param int $n 你懂的(前多少周)
     *  * @param string $format 默认为'%Y-%m-%d',比如"2012-12-18"
     *  * @return array 第一个元素为开始日期，第二个元素为结束日期
     *  */
    public static function lastNWeek($ts, $n, $format = '%Y-%m-%d'){
        $ts = intval($ts);
        $n = abs(intval($n));

        // 周一到周日分别为1-7
        $dayOfWeek = date('w', $ts);
        if(0 == $dayOfWeek)
        {
            $dayOfWeek = 7;
        }

        $lastNMonday = 7 * $n + $dayOfWeek - 1;
        $lastNSunday = 7 * ($n - 1) + $dayOfWeek;
        return [
            strftime($format, strtotime("-{$lastNMonday} day", $ts)),
            strftime($format, strtotime("-{$lastNSunday} day", $ts))
        ];
    }


    //上月
    public static function lastMonth($t)
    {
        $timestamp = $t;
        $firstday = date('Y-m-01', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) - 1) . '-01'));
        $lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        return array($firstday, $lastday);
    }

    // 今日
    public static function thisToday()
    {
        return date('Y-m-d');
    }

    public static function  dateTimeRange($date)
    {
        $t = strtotime($date);
        $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
        return [ $start, $end];
    }

    public static function thisTodayTime()
    {
        $t = time();
        $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        $end = mktime(23,59,59,date("m",$t),date("d",$t),date("Y",$t));
        return [ $start, $end];
    }

    public static function thisWeekTime()
    {
        $t = self::thisWeek();
        $s = strtotime($t['start']);
        $e = strtotime($t['end']);
        $start = mktime(0,0,0,date("m",$s),date("d",$s),date("Y",$s));
        $end = mktime(23,59,59,date("m",$e),date("d",$e),date("Y",$e));
        return [$start,$end];
    }

    // 本周
    public static function  thisWeek()
    {
        //当前日期
        $sdefaultDate = date("Y-m-d");
        //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $first = 1;
        //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $w = date('w', strtotime($sdefaultDate));
        //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $week_start = date('Y-m-d', strtotime("$sdefaultDate -" . ($w ? $w - $first : 6) . ' days'));
        //本周结束日期
        $week_end = date('Y-m-d', strtotime("$week_start +6 days"));
        return ['start' => $week_start, 'end' => $week_end];
    }

    public static function thisMonthTime()
    {
        $t = self::thisMonth(time());
        $s = strtotime($t['start']);
        $e = strtotime($t['end']);
        $start = mktime(0,0,0,date("m",$s),date("d",$s),date("Y",$s));
        $end = mktime(23,59,59,date("m",$e),date("d",$e),date("Y",$e));
        return [$start,$end];
    }

    // 本月
    public static function thisMonth($timestamp)
    {
        $firstday = date('Y-m-01');
        $lastday = date('Y-m-d', strtotime("{$firstday} +1 month -1 day"));
        return ['start'=>$firstday, 'end'=>$lastday];
    }

    // 前几日
    public static function fewDaysAgo($day){
        return date('Y-m-d', strtotime('-'.$day.' days'));
    }
}