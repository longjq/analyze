<?php
namespace App\Libraries;
use DB;
class LoadCache
{

    private $cache;
    private $userList;
    private $map = [
        'user' => 'addItem',
        'user_location' => 'updateLocationItem',
        'user_state' => 'updateStateItem',
        'user_snapshot' => 'updatePackageItem',
        'user_event' => 'updateEventItem',
        'package' => 'package',
    ];

    public function __construct()
    {
        $this->cache = \App\Libraries\Cache::getInstance();
        $this->userList = new \App\Models\UsersList();
        $this->package = new \App\Models\Package();
    }

    // 定时解包
    public function decodePackages($len)
    {

        $lists = DB::table('apps_user_list')->where('decode',0)->take($len)->get();
        DB::beginTransaction();
        foreach ($lists as $key => $value) {
            $this->userList->updatePackageItem((array)$value);
        }
        DB::commit();
    }

    public function runPackages()
    {
        // 更新包数据
        $items = $this->cache->lists('user_snapshot');
        $redis = $this->cache;
        foreach ($items as $item) {
            $this->package->updatePackageItem($item, function ($key, $package) use ($redis) {
                $redis->setHash('user_link_packages', $key, $package);
            }, function ($key, $userId) use ($redis) {
                $redis->setSet('package_' . $key, $userId);
            });
        }
        unset($items);
        unset($redis);
    }

    public function runUserState()
    {
        // 更新用户活跃状态
        $items = $this->cache->lists('user_state', 2000);
        foreach ($items as $item) {
            $this->userList->updateStateItem($item);
        }
        unset($items);
    }

    public function runLocations()
    {
        // 更新用户地区信息
        $items = $this->cache->lists('user_location', 20000);
        foreach ($items as $index => $item) {
            $this->userList->updateLocationItem($item);
        }
        unset($items);
    }

    public function runNews()
    {
        // 新增用户数
        $items = $this->cache->lists('user', 20000);
        foreach ($items as $item) {
            $this->userList->addItem($item);
        }
    }

    // 数据入库
    public function cacheToDB($key, $count, $len)
    {
        for($i=0;$i<$count;$i++){
            // 获取缓存数据，并删除
            $response = $this->cache->listsAndRemovePipeline($key, $len);
            $indexCount = count($response['indexs']);
            if($indexCount == 0) break;

            $err = 1;
            DB::beginTransaction();
            for($j=0;$j<$indexCount;$j++){
                $err = $this->userList->{$this->map[$key]}($response['items'][$j]);
                if($err == 0) break;
            }

            if($err == 0){
                $id = $this->cache->getIndex($response['items']);
                $this->cache->setListsPipeline($key, $id, $response['items']);
                DB::rollBack();
                if($id === false) break;
            }else{
                DB::commit();
            }
            unset($indexCount);
            unset($response);
            unset($err);
            unset($id);
        }
    }
}