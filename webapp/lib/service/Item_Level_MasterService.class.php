<?php

class Item_Level_MasterService extends Service {

    //進化MAXレベル
    const EVOL_MAX = 5;
    //進化必要マグナ定数
    const EVOL_MAGNA = 6000;
    //進化経験値（固定でここから始まる。あとはレベルUPごとに＋１）
    const EVOL_EXP = 10001;

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された装備アイテムのレベルレコードをすべて返す。
     *
     * @param int       アイテムID
     * @return array    該当するレベルレコードの配列
     */
    public function getLevels($itemId, $evolution = 0) {

        return $this->selectResultset(array(
            'item_id' => $itemId,
            'evolution' => $evolution,
            'ORDER BY' => 'level',
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された装備アイテムの最高レベルを返す。
     *
     * @param int       アイテムID
     * @return int      最高レベル
     */
    public function getMaxLevel($itemId, $evolution = 0) {

        $sql = '
            SELECT MAX(level)
            FROM item_level_master
            WHERE item_id = ? and evolution = ?
        ';

        return $this->createDao(true)->getOne($sql, array($itemId, $evolution));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された装備アイテムのレベルレコードを、指定されたレコードで置き換える。
     *
     * @param int       アイテムID
     * @param array     置き換え後のレベルレコード。
     */
    public function replaceLevels($itemId, $levels) {

        // 既存のレコードを削除。
        $this->createDao()->delete(array(
            'item_id' => $itemId
        ));

        // 指定されたレコードをINSERT。
        foreach($levels as $level) {
            $level['item_id'] = $itemId;
            $this->insertRecord($level);
        }
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('item_id', 'level', 'evolution');

    protected $isMaster = true;
}
