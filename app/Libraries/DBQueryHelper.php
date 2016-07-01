<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/2
 * Time: 16:14
 */

namespace App\Libraries;


class DBQueryHelper
{
    public static function explodeIds($items){
        $ids = '';
        foreach($items as $item){
            $ids .= $item['users'].',';
        }
        $ids = substr($ids, 0, (strlen($ids) -1) );
        return $ids;
    }

    public static function inOperator($items, $operator){
        $fields = [];
        foreach($items as $item){
            if(isset($item['condition']) && $item['condition'] == $operator){
                $fields[] = $item['col'];
            }
        }
        return $fields;
    }


    public static function joinOperator($operator){
        $operatorMaps = [
            'and' => 'where',
            'or' => 'orWhere'
        ];
        return $operatorMaps[$operator];
    }
    public static function queryFun($operateStr){
        $arrOperate = [
            'IN' => 'whereIn',
            'NOT IN' => 'whereNotIn',
            'BETWEEN' => 'whereBetween',
            'NOT BETWEEN' => 'whereNotBetween',
            'IS NULL' => 'whereNull',
            'IS NOT NULL' => 'whereNotNull'
        ];
        if (array_key_exists($operateStr, $arrOperate)){
            return $arrOperate[$operateStr];
        }
        // 不存在，返回通用类型where条件
        return 'where';
    }
}