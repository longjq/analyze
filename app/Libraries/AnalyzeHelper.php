<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/3
 * Time: 15:12
 */

namespace App\Libraries;


class AnalyzeHelper
{
    public function liveAvg($users)
    {
        $count = count($users);
        $sum = $users->sum('live');

        $rs = [];
        if($count >= 1){
            $rs['d1'] = $sum / 1 ;
        }
        if($count >= 7){
            $rs['d7'] = $sum / 7 ;
        }
        if($count >= 15){
            $rs['d15'] = $sum / 15;
        }
        if($count >= 30){
            $rs['d30'] = $sum / 30;
        }
        return $rs;
    }
    
    public function liveByTimeGap($users, $timeGap){
        $liveCount = 0;
        foreach($users as $user){
            if($user->isLive($user, $timeGap)){
                $liveCount++;
            }
        }
        if($liveCount){
            return round($liveCount / $users->count(), 2) * 100;
        }

        return 0;
    }

    public function live($usersList){
        $nextDayLiveCount = 0;
        $sevenDayLiveCount = 0;
        $fifteenDayLiveCount = 0;
        $thirtyDayLiveCount = 0;
        foreach ($usersList as $user){
            if ( $user->isLive($user, 86400) ) {
                $nextDayLiveCount = $nextDayLiveCount + 1;
            }
            if ( $user->isLive($user, 86400 * 6) ) {
                $sevenDayLiveCount = $sevenDayLiveCount + 1;
            }
            if ( $user->isLive($user, 86400 * 14) ) {
                $fifteenDayLiveCount = $fifteenDayLiveCount + 1;
            }
            if ( $user->isLive($user, 86400 * 29) ) {
                $thirtyDayLiveCount = $thirtyDayLiveCount + 1;
            }
        }

        $count = $usersList->count();
        return [
            'count' => $count,
            'd1' => round(($nextDayLiveCount / $count ) * 100),
            'd7' => round(($sevenDayLiveCount / $count ) * 100),
            'd15' => round(($fifteenDayLiveCount / $count ) * 100),
            'd30' => round(($thirtyDayLiveCount / $count ) * 100)
        ];
    }

    public function anayzleTitles($rows){
        // return array_column($rows, 'row_date');
        $titles = '';
        foreach($rows as $row){
            $titles .= '\''.$row['row_date'].'\',';
        }
        $titles = substr($titles,0,strlen($titles)-1);
        return $titles;
    }

    public function anayzleMonthCount($rows){
        $countDays = [];
        foreach($rows as $row){
            $countDays[] = $this->anayzleDayCount($row);
        }

        return count($countDays) > 0 ? $countDays : [0];
    }

    public function anayzleDayCount($row){

        $count = 0;
        for($i=0;$i<=23;$i++){
            $count = $count + intval( $row['hour'.$i]);
        }
        return $count;
    }
}