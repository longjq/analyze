<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/7
 * Time: 1:26
 */

namespace App\Libraries;


use App\Models\CountHot;
use App\Models\CountLive;
use App\Models\CountNew;
use App\Models\UserTempHot;
use App\Models\UserTempImei;

class UserTempBrige
{
    private $imei;
    private $userHot;
    private $countNew;
    private $countHot;

    public function __construct()
    {
        $this->imei = new UserTempImei();
        $this->userHot = new UserTempHot();

        $this->countNew = new CountNew();
        $this->countHot = new CountHot();
    }

    public function load2DB()
    {
        $lastDayTime = strtotime('-1 days');
        $dateHelper = new DateHelper($lastDayTime);

        $imeiCount = $this->imei->countByDate($dateHelper->getDateFormat());
        $hotCount  = $this->userHot->countByDate($dateHelper->getDateFormat());

        $this->countNew->saveDayData($dateHelper, $imeiCount);
        $this->countHot->saveDayData($dateHelper, $hotCount);

        $this->imei->deleteByDate($dateHelper->getDateFormat());
        $this->userHot->deleteByDate($dateHelper->getDateFormat());
    }
}