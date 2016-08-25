<?php
/**
 * Created by PhpStorm.
 * User: longjq
 * Date: 2016/7/1
 * Time: 13:49
 */

namespace App\Core;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LogstashFormatter;
use RedisServer;
class ReportAgent
{
    private static $instance;
    private $basePath = 'transfers/';
    private $path;
    private $es;
    private $redis;

    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    public function __construct()
    {
        $this->path = $this->basePath.DIRECTORY_SEPARATOR.date('Y-m-d').DIRECTORY_SEPARATOR;

        // $this->es = \Elasticsearch\ClientBuilder::create()->build();

        $this->redis = RedisServer::connection();
    }

    private function logFile($item)
    {
        $tableName = $item->getTable();
        $this->logger = new Logger($tableName);

        $handler = new StreamHandler(storage_path($this->path.$tableName.'.log'));
        $handler->setFormatter(new LogstashFormatter($item->getTable()));
        $this->logger->pushHandler($handler);

        $this->logger->info($tableName, $item->toArray());
    }

    private function logEasticsearch($item){
        if(isset($item->created_at)){
            $createdAt = $item->created_at;
            $indexDate = date('Y_m', strtotime($createdAt));
        }else{
            $indexDate = date('Y_m');
        }

        if($item->getTable() == 'user_snapshots'){
            $data = $item->toArray();
            $data['snapshot'] = json_decode($data['snapshot']);
            $param = [
                'index' => 'demo_' . $indexDate,
                'type' => $item->getTable(),
                'body' => $data
            ];

        }else {
            $param = [
                'index' => 'demo_' . $indexDate,
                'type' => $item->getTable(),
                'body' => $item->toArray()
            ];
        }
        $this->es->index($param);
    }

    public function info($item)
    {
        if (empty($item) || is_null($item)) {
            return false;
        }
        // $this->logFile($item);
        // $this->logEasticsearch($item);
         $this->logRedis($item);
    }

    private function logRedis($item)
    {
        $this->redis->lpush($item->getTable(), json_encode($item));
    }
}