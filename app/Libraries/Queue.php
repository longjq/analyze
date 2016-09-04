<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/25
 * Time: 12:03
 */

namespace App\Libraries;


use RedisClient;

class Queue
{
    private $redis;
    private static $instance;

    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->redis = RedisClient::connection('assistant');
    }

    public function expendTime($key, $timestr)
    {
        $this->redis->set($key, $timestr);
    }

    public function run($tableName, $count)
    {
        $items = $this->getItemsByRedis($tableName, $count);

        $indexs = [];
        foreach ($items as $item) {
            if( is_null($item) ) continue;

            // prase $item string to json
            $item = json_decode($item);
            if($packJson = $this->decodePackageJson($tableName, $item)){
                $item->snapshot = array_merge([], $packJson);
            }

            $indexs[] = $item;
        }

//        if($tableName == 'analysis_user_events'){
//            $tableName = $tableName.'_'.date('Y_m');
//        }

        return \App\Libraries\ElasticsearchAgent::getInstance()->bulk($tableName, $indexs);
    }

    private function renameEventsLog($tableName){
        return $tableName.'_'.date('Y_m');
    }

    private function decodePackageJson($tableName, $item){

        // if json have 'analysis_user_snapshots' item , let's json_decode that item
        if($tableName == 'analysis_user_snapshots' && isset($item->snapshot)){
            return $this->decodeSnapshot($item);
        }
        return [];
    }

    private function decodeSnapshot($item)
    {
        $snapshots = json_decode($item->snapshot);

        foreach($snapshots as $snapshot){
            $doc[] = array_combine(['title', 'package', 'ver', 'md5'], $snapshot);
        }
        return $doc;
    }

    private function getItemsByRedis($tableName, $count)
    {
        // connection redis by pipeline and get data
        return $this->redis->pipeline(function ($pipe) use ($tableName, $count) {
            for ($i = 0; $i < $count; $i++) {
                $pipe->lpop($tableName);
            }
        });
    }
}