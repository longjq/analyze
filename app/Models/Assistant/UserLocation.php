<?php

namespace App\Models\Assistant;

use DB;

class UserLocation extends Base
{
    protected $table = 'user_locations';

    public function user(){
        return $this->belongsTo(\App\Models\Assistant\User::class);
    }

    // 统计相关字段总人数
    public function groupCount($field){
        return $this->select($field, DB::raw('count('.$field.') as '.$field.'_count'))->groupBy($field)->get();
    }

    // 返回用户指定列数据
    public function listByField(array $list, $field, $operateStr, ...$val){
        $opearateFun = \App\Libraries\DBOperate::queryFun($operateStr);
        if ($opearateFun == 'whereNull' || $opearateFun == 'whereNotNull'){
            return $this->{$opearateFun}($field)->get($list);
        }
        return $this->{$opearateFun}($field, $operateStr, $val)->get($list);
    }
}
