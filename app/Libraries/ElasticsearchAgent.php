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
        $indexDate = date('Y_m');
        foreach($items as $item){
            $params['body'][] = [
                'index' => [
                    '_index' => 'analysis_'.$indexDate,
                    '_type' => $tableName
                ]
            ];

            $params['body'][] = $item;
        }
        return $this->es->bulk($params);
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