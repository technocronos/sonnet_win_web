<?php

class Quest_MasterService extends Service {

    // 地点による制限がないことを表す地点ID
    const WILD_PLACE = 0;

    // イベントクエストであることを表す地点ID
    const EVENT_QUEST = 98;

    // モンスターの洞窟のID
    const MONSTER_DUNGEON = 99999;

    // チーム対戦クエストのID
    const TEAM_BATTLE = 99998;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された地点に設定されているクエストのIDをすべて返す。
     *
     * @param mixed     クエストを取得したい地点ID。配列で複数指定することもできる。
     * @param string    クエスト種別の値。限定しないなら省略可能。
     * @return array    指定された地点のクエストレコードの一覧。
     */
    public function onPlace($placeId, $type = null) {

        $condition = array(
            'place_id' => $placeId,
            'ORDER BY' => 'sort_order',
        );

        if($type)
            $condition['type'] = $type;

        return $this->selectResultset($condition);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * イベントクエストのリストを返す。98000以上、98999以下をイベントクエストとする。
     *
     * @param string    クエスト種別の値。限定しないなら省略可能。
     * @return array    指定された地点のクエストレコードの一覧。
     */
    public function onEvent($type = null, $sort = "sort_order") {
        $condition = array(
            'quest_id:1' => array('sql' => '>= ?', 'value' => 98000),
            'quest_id:2' => array('sql' => '<= ?', 'value' => 98999),
            'ORDER BY' => $sort,
        );

        if($type)
            $condition['type'] = $type;

        return $this->selectResultset($condition);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたクエストの "consume_pt" の値を返す。
     * ここだけよく参照するので、メソッドにしてみた…
     */
    public function getConsumePt($questId) {

        $record = $this->needRecord($questId);
        return $record['consume_pt'];
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 翻訳をする
     */
    public function getTransText($record){
        $columns = ["quest_name", "flavor_text"];

        foreach($columns as $column){
            $data = AppUtil::getText("quest_master_" . $column . "_" . $record[$this->primaryKey]);

            if($data == "")
                $data = $record[$column];

            $record[$column] = $data;
        }

        return $record;
    }

    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'quest_id';

    protected $isMaster = true;

    /**
     * getRecordをオーバーライドして多言語対応
     */
    public function getRecord(/* 可変引数 */) {
        $args = func_get_args();
        $record = parent::getRecord($args[0]);

        $record = $this->getTransText($record);

        return $record;
    }

    /**
     * selectResultsetをオーバーライドして多言語対応
     */
    public function selectResultset($where) {
        $record = parent::selectResultset($where);

        foreach($record as &$row){
            $row = $this->getTransText($row);
        }

        return $record;
    }
}
