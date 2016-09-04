<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/25
 * Time: 12:08
 */

namespace App\Libraries;


class ElasticsearchAgent
{
    private $es;

    private static $instance;

    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->es = \Elasticsearch\ClientBuilder::create()->build();
    }

    public function bulk($tableName, $items)
    {
        $map = ['user_events' => 'id', 'user_snapshots' => 'user_id'];
        $type = str_replace('analysis_','',$tableName);
        $primary = $map[$type];
        $params = [];

        foreach($items as $item){
            $params['body'][] = $this->head($type, $item, $primary);
            $params['body'][] = $item;
        }
        if(count($params) > 0){
            return $this->es->bulk($params);
        }
        return false;
    }

    private function head($type,$item, $primary){

        return [
            'index' => [
                '_index' => 'analysis',
                '_type' => $type,
                '_id'   => $item->{$primary}
            ]
        ];

//        if($tableName == 'analysis_user_snapshots'){
//            return [
//                'index' => [
//                    '_index' => 'analysis_'.$tableName,
//                    '_type' => $tableName,
//                    '_id'   => $item->user_id
//                ]
//            ];
//        }
//
//        return [
//            'index' => [
//                '_index' => 'analysis_'.$tableName,
//                '_type' => $tableName,
//                '_id'   => $item->id
//            ]
//        ];

    }

    public function index($item)
    {
        if(isset($item->created_at)){
            $createdAt = $item->created_at;
            $indexDate = date('Y_m', strtotime($createdAt));
        }else{
            $indexDate = date('Y_m');
        }

        if($item->getTable() == 'user_snapshots'){
            $data = $item->toArray();
            $data['snapshot'] = json_decode($data['snapshot']);
            $param = [
                'index' => 'demo_' . $indexDate,
                'type' => $item->getTable(),
                'body' => $data
            ];

        }else {
            $param = [
                'index' => 'demo_' . $indexDate,
                'type' => $item->getTable(),
                'body' => $item->toArray()
            ];
        }
        $this->es->index($param);

    }
}