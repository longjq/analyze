<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/17
 * Time: 10:40
 */

namespace App\Core;

use Predis\Client as RedisServerClient;

class RedisCache
{
    private static $instance;
    private $redis;

    public function __construct()
    {

        try{
            $this->redis = new RedisServerClient([
                'scheme'   => 'tcp',
                'host'     => '127.0.0.1',
                'password' => 'zl!@#advert$%^ltbl',
                'port'     => 6379,
                'database' => 1
            ]);
        }catch(\Exception $e){
            $this->redis = null;
        }
    }


    public function set($tabelName, $item)
    {
        if(is_null($this->redis)){
            return false;
        }

        if(empty($tabelName) || empty($item)){
            return false;
        }

        $item = json_encode($item);
        if ($tabelName == 'users') {
            $item['self_type'] = $item['type'];
            unset($item['type']);
        }

        return $this->redis->lpush($tabelName, $item);
    }

    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }
        return self::$instance;
    }


}