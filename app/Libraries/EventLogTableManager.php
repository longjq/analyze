<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/23
 * Time: 15:04
 */

namespace App\Libraries;

use App\Models\EventLogCalc;
use DB;

class EventLogTableManager
{

    private $newTalebName;
    private $newTableSql;
    private $newTag;

    private $oldTableName;
    private $oldTableSql;
    private $oldTag;

    private $nowTableName;
    private $nowTableSql;
    private $nowTag;


    private function genertorNewTableInfo()
    {
        $this->newTag = date('Y_m_d', strtotime('+1 days'));
        $this->newTalebName = 'user_events_temp_' . $this->newTag;
    }

    private function genertorOldTableInfo()
    {
        $this->oldTag = date('Y_m_d', strtotime('-1 days'));
        $this->oldTableName = 'user_events_temp_' . $this->oldTag;
    }

    private function genertorNowTableInfo()
    {
        $this->nowTag = date('Y_m_d');
        $this->nowTableName = 'user_events_temp_' . $this->nowTag;
    }

    private function getOldTableSql()
    {
        $sql = "DROP TABLE IF EXISTS `{$this->oldTableName}`";
        $this->oldTableSql = $sql;
        return $sql;
    }

    private function getNewTableSql()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `" . $this->newTalebName . "` (
`id`  int(10) UNSIGNED NOT NULL ,
`user_id`  int(11) NOT NULL ,
`event`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`time`  bigint(11) NOT NULL ,
`name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' ,
`package`  varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' ,
`version`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' ,
`p1`  varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' ,
`p2`  varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' ,
`created_at`  timestamp NULL DEFAULT NULL ,
`updated_at`  timestamp NULL DEFAULT NULL
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci
CHECKSUM=0
ROW_FORMAT=DYNAMIC
DELAY_KEY_WRITE=0;";
        $this->newTableSql = $sql;
        return $sql;
    }

    private function getNowTableSql()
    {
        $sql = "SELECT `package`,`name` as `title`,COUNT(`event`) as `inst_count`
FROM {$this->nowTableName} WHERE `event`='inst'
GROUP BY `package`,`title`
HAVING `inst_count` > :inst_count
ORDER BY `inst_count` desc";
        $this->nowTableSql = $sql;
        return $sql;
    }

    private function dropNewTableIfExists()
    {
        DB::statement('DROP TABLE IF EXISTS ' . $this->newTalebName);
    }

    public function createNewTable()
    {
        $this->genertorNewTableInfo();
        DB::statement($this->getNewTableSql());
    }

    public function dropOldTable()
    {
        $this->genertorOldTableInfo();
        DB::statement($this->getOldTableSql());
    }

    public function calc()
    {
        $this->genertorNowTableInfo();
        $results = DB::select($this->getNowTableSql(), ['inst_count' => 50]);
        return $this->saveCalcResults($results);
    }

    private function saveCalcResults($items)
    {
        try {
            DB::beginTransaction();
            foreach ($items as $item) {
                EventLogCalc::create([
                    'package' => $item['package'],
                    'title' => $item['title'],
                    'inst_count' => $item['inst_count'],
                    'row_add' => date('Y-m-d')
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $logger = new LoggerHelper('event_calc');
            $logger->info('存储过程出错Message：', $e->getMessage());
            return false;
        }

        return true;
    }
}