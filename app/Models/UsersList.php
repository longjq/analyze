<?php

namespace App\Models;

use App\Traits\UserHourSave;
use Illuminate\Database\Eloquent\Model;
use DB;
class UsersList extends Model
{
    use UserHourSave;
    protected $table = 'assistant_users_list';
    public $timestamps = false;
    protected $primaryKey = 'user_id';
    // 黑名单为空，所有字段都可以被操作
    protected $guarded = [];

    // 插入事件记录
    public function updateEventItem($item)
    {
        return DB::insert("INSERT INTO assistant_users_event(id,event,user_id,`name`,package) VALUES(?,?,?,?,?)",
            [$item['id'],$item['event'],$item['user_id'],$item['name'],$item['package']]);
    }

    // 更新包名
    public function updatePackageItem($item)
    {
        $packages = json_decode($item['packages']);
        foreach($packages as $package){
            $md5 = isset($package[3]) ? $package[3] : null;
           // 插入包名列表和包名=》用户id
           DB::insert("INSERT into apps (`package`,`name`,`users`,`md5`,`user_count`) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE users= CONCAT(VALUES(`users`),',',`users`),md5=VALUES(`md5`),user_count = user_count+1",
               [$package[1],$package[0],$item['user_id'],$md5,1]);
            //  DB::insert("REPLACE INTO apps(`package`,`name`,`users`,`user_count`) VALUES(?,?,?,?)",[$package[1],$package[0],$item['user_id'],1]);
        }
        // 建立用户id=>包名关系
        return DB::update("update apps_user_list set decode = 1 where user_id = ?",[$item['user_id']]);
    }

    // 更新状态
    public function updateStateItem($item){
        return DB::update("update assistant_users_list set
          mtime = ".strtotime($item['updated_at'])."
          where user_id = ?", [$item['user_id']]);

        /*$sourceItem = $this->where('user_id', $item['user_id'])->first();
        if (isset($sourceItem)) {
            $sourceItem = $this->updateStateField($sourceItem, $item);
            $sourceItem->save();
        }
        unset($sourceItem);
        unset($item);*/
    }

    // 更新地址
    public function updateLocationItem($item)
    {
            return DB::update("update assistant_users_list set
              country_id = '{$item['country_id']}',
              country = '{$item['country']}',
              area_id = '{$item['area_id']}',
              area = '{$item['area']}',
              region_id = '{$item['region_id']}',
              region = '{$item['region']}',
              city_id = '{$item['city_id']}',
              city = '{$item['city']}',
              isp = '{$item['isp']}',
              isp_id = '{$item['isp_id']}'
              where user_id = ?",[$item['user_id']]);

        /*$sourceItem = $this->where('user_id', $item['user_id'])->first();
        if (isset($sourceItem)) {
            $sourceItem = $this->updateLocationField($sourceItem, $this->locationMap, $item);
            $sourceItem->save();
        }
        unset($sourceItem);
        unset($item);*/
    }

    // 新增
    public function addItem($item)
    {
        return DB::insert("replace into assistant_users_list
            (user_id,device,imei,v,lang,brand,ov,ctime)
            values(?,?,?,?,?,?,?,?)",
            [
                $item['id'],
                $item['device'],
                $item['imei'],
                $item['v'],
                $item['lang'],
                $item['brand'],
                $item['ov'],
                strtotime($item['created_at'])
            ]);
//        if ($this->where('imei', $item['imei'])->count() == 0) {
//            $this->create($this->addField($item));
//        }
    }

    public function usersByTimeRange($start, $end)
    {
        return $this->where('ctime', '>=', $start)
            ->where('ctime', '<=', $end)
            ->get();
    }

    // 时间戳内的用户总数
    public function usersByTimeRangeCount($field,$start, $end)
    {
      return $this->whereBetween($field, [$start, $end])->count();
    }



    /**
     * 过去一小时新增|活跃的用户
     * @param $hourRange 日期时间区间 [2016-06-13 14:00:00,2016-06-13 14:59:59]
     * @return mixed
     */
    public function lastHourUsers($hourRange, $field){
        return $this->userDateRange($hourRange, $field)->count();
    }

    /**
     * 用户列表
     * @param array $dateRange 日期时间区间 [2016-06-13 14:00:00,2016-06-13 14:59:59]
     * @return mixed
     */
    private function userDateRange(array $dateRange, $field){
        return $this->whereBetween($field,$dateRange);
    }

    // 统计相关字段总人数
    public function groupCount($field){
        return $this->select($field, DB::raw('count('.$field.') as '.$field.'_count'))->groupBy($field)->get();
    }

    public function apps()
    {
        return $this->belongsToMany(\App\Models\Package::class, 'apps_users', 'user_id', 'app_id');
    }

    // 存活率比较
    public function isLive($user, $timeGap){
        if (!empty($user->mtime)) {
            if ($user->mtime > ($user->ctime + $timeGap) )  {
                return true;
            }
        }
        return false;
    }
}
