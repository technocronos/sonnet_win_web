<?php

class Help_MasterService extends Service {

    // キーをgroup_id、値をグループ名とする配列。
    public static $GROUPS = array(
        'about' => 'HELP_GROUP_ABOUT',
        'status' => 'HELP_GROUP_STATUS',
        'he' => 'HELP_GROUP_HE',
        'battle' => 'HELP_GROUP_BATTLE',
        'quest' => 'HELP_GROUP_QUEST',
        'item' => 'HELP_GROUP_ITEM',
        'other' => 'HELP_GROUP_OTHER',
    );


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたレベルで開放されているヘルプの一覧を取得する。
     *
     * @param int       レベル。
     * @return array    結果セット。
     */
    public function getList($level) {

        return $this->selectResultset(array(
            'unlock_level' => array('sql'=>'<= ?', 'value'=>$level),
            'ORDER BY' => 'sort_order'
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたレベル範囲でリリースされたヘルプの一覧を取得する。
     *
     * @param int       レベル範囲下限。ここで指定されたレベルそのものは含まれない( > )。
     * @param int       レベル範囲上限。ここで指定されたレベルそのものは含まれる( <= )。
     * @return array    結果セット。
     */
    public function checkRelease($fromLevel, $toLevel) {

        return $this->selectResultset(array(
            'unlock_level' => array('sql'=>'BETWEEN ? + 1 AND ?', 'value'=>array($fromLevel, $toLevel)),
            'ORDER BY' => 'sort_order'
        ));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'help_id';

    protected $isMaster = true;


    //-----------------------------------------------------------------------------------------------------
    /**
     * processRecordをオーバーライド。
     * 擬似列 group_id を加えるようにする。
     */
    protected function processRecord(&$record) {

        $record['group_id'] = strstr($record['help_id'], '-', true);
    }
}
