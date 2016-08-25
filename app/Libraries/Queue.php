<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/25
 * Time: 12:03
 */

namespace App\Libraries;

use App\Core\ReportAgent;
use RedisServer;

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
        $this->redis = RedisServer::connection();
    }

    public function run($tableName, $count)
    {
        $items = $this->getItemsByRedis($tableName, $count);

        $indexs = [];
        foreach ($items as $item) {
            $indexs = array_merge($indexs, $this->decodeJson($tableName, $item));
        }

        return \App\Libraries\ElasticsearchAgent::getInstance()->bulk($tableName, $indexs);
    }

    private function decodeJson($tableName, $item){
        // 1. prase string to json
        $item = json_decode($item);

        // 2. if json have 'user_snapshots' item , let's json_decode that item
        if($tableName == 'user_snapshots' && isset($item->snapshot)){
            $item = $this->decodeSnapshot($item);
        }

        return $item;
    }

    private function decodeSnapshot($item)
    {
        $snapshots = json_decode($item->snapshot);

        foreach($snapshots as $snapshot){
            $doc[] = array_merge([
                'user_id' => $item->user_id,
                'md5' => $item->md5,
                'snapshot_time' => $item->snapshot_time,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ],
                array_combine(['title', 'package', 'ver', 'md5'], $snapshot)
            );
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