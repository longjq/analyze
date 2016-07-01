<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/17
 * Time: 10:40
 */

namespace App\Libraries;

use RedisServer;

class Cache
{
    const SEPARATE = '_';
    private static $instance;
    public $redis;

    public function __construct()
    {
        $this->redis = RedisServer::connection('assistant');
    }

    private function getIndexs($key, $len)
    {
        return $this->redis->lrange($key, 0, $len);
    }

    private function getItems($keys){
        $len = count($keys);
        $result = [];
        for ($i=0; $i < $len; $i++) {
            $result[$keys[$i]] = $this->getItem($keys[$i]);
        }
        return $result;
    }

    public function getItem($key){
        return $this->redis->hgetall($key);
    }


    private function hmdel($key, $fields)
    {
        foreach ($fields as $k => $field) {
            $this->redis->hdel($key, $k);
        }
    }

    private function getId($items)
    {
        $items = (object)$items;
        if(count($items) > 1){
            return isset(   $items[0]->id) ? 'id':'user_id';
        }
        return isset($items->id) ? 'id':'user_id';
    }

    private function hmset($key, $id, $items)
    {
        foreach ($items as $item) {
            if(isset($item['updated_at'])){
                // 16位md5密文
                $unique = substr(md5($item['updated_at']), 8, 16);
                $itemUnique = $key . self::SEPARATE . $item[$id] . self::SEPARATE . $unique;

                $this->redis->lpush($key, $itemUnique);
                $this->redis->hmset($itemUnique, $item);
            }
        }
    }

    public static function getInstance(){
        if( !(self::$instance instanceof self) ){
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function setString($key, $str)
    {
        $this->redis->set($key, $str);
    }

    public function getString($key)
    {
        return $this->redis->get($key);
    }
    public function remove($index, $item)
    {
        $this->hmdel($index, $item);
    }

    public function listsAndRemove($key, $len = 100)
    {
        $llen = $index = $this->redis->llen($key);
        $len = $llen > $len ? $len : $llen;
        $result = [];
        for ($i=0; $i < $len; $i++) { 
            $index = $this->redis->lpop($key);
            $item = $this->getItem($index);
            $result[$index] = $item;
            $this->remove($index, $item);
        }
        return $result;
    }


    public function setListsPipeline($key, $id, $items)
    {
        // Executes a pipeline inside the given callable block:
        return $this->redis->pipeline(function ($pipe) use($key,$id ,$items) {
            $len = count($items);
            for ($i = 0; $i < $len; $i++) {
                $unique = substr(md5($items[$i]['updated_at']), 8, 16);
                $index = $key.'_'.$id.'_'.$unique;
                $pipe->lpush($key, $index);
                $pipe->hmset($index, $items[$i]);
            }
        });

    }

    public function lists($key, $len = 100)
    {
        if(empty($key)){
            return null;
        }
        return $this->getItems($this->getIndexs($key, $len), $len);
    }

    public function getSetAll($key)
    {
        return $this->redis->smembers($key);
    }

    public function getSetSDiff($keys)
    {
        return $this->redis->sdiff($keys);
    }

    public function getSetSinter($keys)
    {
        return $this->redis->sinter($keys);
    }

    public function getHashItem($key, $item)
    {
        return $this->redis->hget($key, $item);
    }

    public function getSetLen($key)
    {
        return $this->redis->scard($key);
    }

    public function setSet($key, $item)
    {
        $this->redis->sadd($key, $item);
    }

    public function setHash($key, $index, $item)
    {
        $this->redis->hset($key, $index, $item);
    }


    // 建立管道，实现大批量操作
    public function listsAndRemovePipeline($key, $len)
    {
        // Executes a pipeline inside the given callable block:
        $indexs = $this->redis->pipeline(function ($pipe) use($key, $len) {
            for ($i = 0; $i < $len; $i++) {
                $pipe->lpop($key);
            }
        });

        $indexs = array_filter($indexs);

        // Executes a pipeline inside the given callable block:
        $items = $this->redis->pipeline(function ($pipe) use($indexs){
            foreach($indexs as $index){
                $pipe->hgetall($index);
                // $pipe->del($index);
            }
        });
        $items = array_filter($items);

        // Executes a pipeline inside the given callable block:
        $this->redis->pipeline(function ($pipe) use($indexs){
            foreach($indexs as $index){
                $pipe->del($index);
            }
        });
        return [ 'indexs' => $indexs, 'items' => $items ];
    }

    public function getIndex($items)
    {
        if(count($items) > 0){
            return isset($items[0]['id']) ? 'id':'user_id';
        }
        return false;
    }

    /**
     * 新增及修改
     * key规则：前缀_用户id_16位md5值（updated_at）
     * user_status_45_cd24sd1274112
     * @param $key 前缀 [user|user_status|user_location|user_snapshot]
     * @param $items 数据
     * @return int
     */
    public function load($key , $items){
        $id = $this->getId($items);
        if(count($items) > 1){
             $this->hmset($key, $id, $items->toArray());
        }else{
            $this->hmset($key, $id, $items->toArray());
        }
    }
}