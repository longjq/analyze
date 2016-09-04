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
use RedisClient;

class ReportAgent
{
    private static $instance;
    private $basePath = 'transfers/web_100';
    private $path;
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
        $this->redis = RedisClient::connection('assistant');
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

    public function info($item)
    {
        if (empty($item) || is_null($item)) {
            return false;
        }
        // $this->logFile($item);
        $this->logRedis($item);
    }
    private function logRedis($item)
    {
        $map = ['user_events'=>0,'user_snapshots'=>1];
        if(isset($map[$item->getTable()])){
            $this->redis->lpush('analysis_'.$item->getTable(), json_encode($item));
        }
    }

}