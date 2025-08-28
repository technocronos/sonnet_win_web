<?php

class Equippable_MasterService extends Service {

	//合成用レア度重みテーブル。大体レア度がどれくらいのレベルで手に入るのが適正か、みたいな感じで数字を入れる。
	//以前はunlocklevelでやっていたがアイテムによっては強いのに設定されてないものもあるのでレア度別に変更したかった。
	public $rear_weight_table = array(
        1=>1,
        2=>10,
        3=>25,
        4=>35,
        5=>50,
        6=>60,
        7=>70,
        8=>80,
        9=>90,
        10=>100,
    );


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された種族、装備箇所における、装備可能アイテムの一覧を返す。
     *
     * @param string    種族。
     * @param int       装備箇所を表す mount_id。
     * @return array    item_master レコードを格納している結果セット。
     */
    public function getEquipList($race, $mountId) {

        $resultset = $this->selectResultset(array(
            'race' => $race,
            'mount_id' => $mountId,
        ));

        // 装備可能な item_id を配列で取得。
        $itemIds = ResultsetUtil::colValues($resultset, 'item_id');

        // item_master レコードを取得。item_id列で並べ替えてリターン。
        $result = Service::create('Item_Master')->getRecordsIn($itemIds, false);
        return ResultsetUtil::sort($result, 'item_id');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された種族が、指定されたアイテムをどこに装備できるかを返す。
     *
     * @param string    種族。
     * @param int       アイテムID
     * @return int      装備可能な箇所を表す mount_id。可能箇所がない場合は 0。
     */
    public function getMount($race, $itemId) {

        $record = $this->selectRecord(array(
            'race' => $race,
            'item_id' => $itemId,
        ));

        return $record ? $record['mount_id'] : 0;
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('race', 'mount_id', 'item_id');

    protected $isMaster = true;
}
