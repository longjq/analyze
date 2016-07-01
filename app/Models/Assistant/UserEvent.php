<?php

namespace App\Models\Assistant;

use DB;
class UserEvent extends Base
{
    // 事件人总数
    public function userEventCount($userEventList){
        return count($userEventList);
    }

    // 事件人user_id
    public function userEventList($package, $event){
        return $this->events($package, $event)->select(DB::raw('user_id'))->groupBy('user_id')->get();
    }

    /**
     * 指定包查询
     * @param $package
     * @param $event
     * @return mixed
     */
    private function events($package, $event){
        return $this->where('package', $package)->where('event', $event);
    }
}
