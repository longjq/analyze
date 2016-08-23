<?php
/**
 * Created by PhpStorm.
 * User: longjq
 * Date: 2016/8/5
 * Time: 10:56
 */

namespace App\Libraries;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;


class LoggerHelper
{
    private $dir;
    private $logger;
    public function __construct($dir)
    {
        $this->logger = new Logger("process");

        if ($this->dir == "") {
            $this->dir = app()->storagePath().'/process/'.$dir.'.log';
        }

        $this->logger->pushHandler(
            $handler = new RotatingFileHandler(
                $this->dir, 0, Logger::DEBUG)
        );

        $handler->setFormatter(new LineFormatter(null, null, true, true));
    }

    public function info($message, $context){
        $this->logger->info($message,$context);
    }
}