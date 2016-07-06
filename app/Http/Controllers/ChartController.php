<?php

namespace App\Http\Controllers;

use App\Libraries\AnalyzeHelper;
use App\Libraries\DateHelper;
use App\Libraries\DBQueryHelper;

use App\Models\CountHot;
use App\Models\CountNew;
use App\Models\LiveCount;
use App\Models\UsersList;
use App\Models\UsersLive;
use App\Models\UsersNew;
use App\Models\UserTempHot;
use App\Models\UserTempImei;
use Illuminate\Http\Request;

use App\Http\Requests;

class ChartController extends Controller
{
    private $countNew;
    private $countHot;
    private $imei;
    private $userHot;

    private $usersNew;  // 新增用户
    private $usersHot;  // 活跃用户
    private $usersList; // 存活率
    private $anayzle; // 分析
    private $usersLive; // 平均存活率

    public function __construct()
    {
        $this->countNew = new CountNew();
        $this->countHot = new CountHot();
        $this->imei = new UserTempImei();
        $this->userHot = new UserTempHot();

        $this->usersNew = new UsersNew();
        $this->usersList = new UsersList;
        $this->anayzle = new AnalyzeHelper;
        $this->usersLive = new UsersLive;
    }

    // 新增用户统计数据
    public function usersNew(Request $request){
        $data = [];
        if($request->isMethod('get')){
            $d = DateHelper::monthRange(date('Y-m-d'));
            $rows = $this->countNew->dataByDateRange($d);
            if (count($rows) > 0) {
                $today = $this->imei->countByDate(DateHelper::thisToday());
                $rows->push(['row_date' => DateHelper::thisToday(), 'count' => $today]);
            }
            $data['titles'] = $this->anayzle->anayzleTitles($rows->toArray());
            $data['datas'] = $rows->pluck('count')->toArray();
            return view('charts/users_new', compact('data'));
        }

        $year = $request->input('year');
        $month = $request->input('month');

        $d = DateHelper::monthRange("{$year}-{$month}-1");
        $rows = $this->countNew->dataByDateRange($d);
        if (count($rows) > 0) {
            $today = $this->imei->countByDate(DateHelper::thisToday());
            $rows->push(['row_date' => DateHelper::thisToday(), 'count' => $today]);
        }
        $data['titles'] = $this->anayzle->anayzleTitles($rows->toArray());
        $data['datas'] = $rows->pluck('count')->toArray();
        return view('charts/users_new', compact('data'));
    }

    // 活跃用户统计分析
    public function usersHot(Request $request){
        $data = [];
        if($request->isMethod('get')){
            $d = DateHelper::monthRange(date('Y-m-d'));
            $rows = $this->countHot->dataByDateRange($d);
            if (count($rows) > 0) {
                $today = $this->userHot->countByDate(DateHelper::thisToday());
                $rows->push(['row_date' => DateHelper::thisToday(), 'count' => $today]);
            }
            $data['titles'] = $this->anayzle->anayzleTitles($rows->toArray());
            $data['datas'] = $rows->pluck('count')->toArray();

            return view('charts/users_hot', compact('data'));
        }

        $year = $request->input('year');
        $month = $request->input('month');

        $d = DateHelper::monthRange("{$year}-{$month}-1");
        $rows = $this->countHot->dataByDateRange($d);

        if (count($rows) > 0){
            $today = $this->userHot->countByDate(DateHelper::thisToday());
            $rows->push(['row_date' => DateHelper::thisToday(),'count'=>$today]);
        }

        $data['titles'] = $this->anayzle->anayzleTitles($rows->toArray());
        $data['datas'] = $rows->pluck('count')->toArray();

        return view('charts/users_hot', compact('data'));
    }

    // 留存率
    public function usersList(Request $request){
        if ($request->isMethod('get')) {
            return view('charts/users_list');
        }

        $this->validate($request, [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ],[
            'start_date.required' => '日期格式不能为空',
            'end_date.required' => '日期格式不能为空',
        ]);

        $startTime = strtotime($request->input('start_date'));
        $endTime = strtotime($request->input('end_date'));

//        if($startTime == $endTime){
//            $dayTime = DateHelper::dateTimeRange($request->input('start_date'));
//            $startTime = $dayTime[0];
//            $endTime = $dayTime[1];
//        }
        $usersList =  $this->usersLive->usersByDateRange(date('Y-m-d', $startTime), date('Y-m-d',$endTime));

        if ($usersList->count() == 0) {
            return view('charts/users_list', compact('startTime','endTime'));
        }

        $livePer = $this->anayzle->liveAvg($usersList);

        return view('charts/users_list', compact('livePer', 'startTime', 'endTime'));
//        // 次日平均留存率
//        $users = $this->usersLive->dateRange($startDateHelper->getDateFormat(), $endDateHelper->getDateFormat());
//        $counts = $this->usersLive->liveDayAvg($users);
//        $dayAvg = $this->usersLive->calcDayAvg($counts['sumLive'], $counts['sumCount']);

//         return view('charts/users_list', compact('livePer', 'liveCount', 'startDate', 'endDate', 'dayAvg'));
    }



}
