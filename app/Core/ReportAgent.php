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
class ReportAgent
{
    private static $instance;
    private $basePath = 'transfers/';
    private $path;
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
    }

    private function log($item)
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
        $this->log($item);
    }
}