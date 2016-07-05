<?php

namespace App\Http\Controllers;

use App\Libraries\LoadAssistant;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RefreshController extends Controller
{
    private $userList;
    private $loadAssistant;
    private $cache;
    public function __construct()
    {
        $this->userList = new \App\Models\UsersList();
        $this->loadAssistant = new \App\Libraries\LoadAssistant();
        $this->cache = new \App\Models\Cache();
    }

    // 刷新用户新增数
    public function userNews()
    {
        // 记录每小时新增用户数和活跃数
        $this->loadAssistant->saveHourUserCount();
        // 用户总数
        $this->loadAssistant->userTotal();
        // 刷新用户全局缓存
        $this->loadAssistant->userNewRefresh();
        return redirect('/dash');
    }
    // 刷新用户活跃数
    public function userHots()
    {
        $this->loadAssistant->userHotRefresh();
        return redirect('/dash');
    }

    // 刷新用户存活率
    public function userLives()
    {
        // 每日计算前一次的留存率
        $this->loadAssistant->syncUserLive();
        // 历史
        $this->loadAssistant->liveHistory();
        // 今日
        $this->loadAssistant->liveToday();
        return redirect('/dash');
    }
}
