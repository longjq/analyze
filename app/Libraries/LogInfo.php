<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/8
 * Time: 16:03
 */

namespace App\Libraries;


use Illuminate\Support\Facades\Storage;

class LogInfo
{
    private static function init()
    {
        if(!is_dir(storage_path('app/run_logs'))){
            Storage::makeDirectory('run_logs');
        }
    }

    public static function info($log, $data = []){
        self::init();
        $str = '['.date('Y-m-d H:i:s').'] '. $log.' ';

        if(count($data) > 0){
            $str.='参数列表: ';
            foreach($data as $key => $item){
                $str.= $key.' => '. $item.', ';
            }
            $str = substr($str, 0 ,strlen($str) -1);
        }
        Storage::append('/run_logs/'.date('Y-m-d').'.log',$str);
        return $str;
    }

    public static function lastInfo()
    {
        if(Storage::has('/run_logs/'.date('Y-m-d').'.log')){
            self::lastRow(date('Y-m-d'));
        }else{
            self::lastRow(date('Y-m-d', strtotime('-1 days')));
        }
    }
}