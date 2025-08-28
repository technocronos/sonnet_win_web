<?php

class Vcoin_Flag_LogService extends Service {

    // flag_group の値。
    const MONSTER = 1;                  //　モンスター討伐
    const BATTLE_RANKING = 2;           //　バトルランキング
    const FIELD = 3;                    //　クエスト
    const RAID = 4;                     //　レイド
    const RAID_0 = 5;                     //　レイド
    const RAID_1 = 6;                     //　レイド
    const RAID_2 = 7;                     //　レイド
    const RAID_3 = 8;                     //　レイド
    const RAID_4 = 9;                     //　レイド
    const RAID_5 = 10;                     //　レイド

    const INVITE = 100;                     //　友達招待
    const INVITED = 101;                     //　友達招待された


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたレコードの値を返す。レコードがない場合は 0 を返す。
     *
     * @param int   flag_group の値。このクラスの定数を使用する。
     * @param int   owner_id の値
     * @param int   flag_id の値。
     * @return int  flag_valueの値。レコードがなかった場合は 0。
     */
    public function getValue($groupId, $ownerId, $flagId) {

        $record = $this->getRecord($groupId, $ownerId, $flagId);

        return $record ? $record['flag_value'] : 0;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたフラグの数を返す。
     *
     * @param int       flag_group の値。このクラスの定数を使用する。
     * @param int       owner_id の値
     * @param array     取得対象の flag_id の値を配列で。
     * @return int      指定されたもののうち、flag_value列が有効な値を持っているレコードの数。
     */
    public function flagCount($groupId, $ownerId, $flagIds) {

        return $this->countRecord(array(
            'flag_group' => $groupId,
            'owner_id' => $ownerId,
            'flag_id' => $flagIds,
            'flag_value' => array('sql'=>'!= 0'),
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたレコードの値をセットする。レコードがない場合は作成する。
     *
     * @param int   flag_group の値。このクラスの定数を使用する。
     * @param int   owner_id の値
     * @param int   flag_id の値。
     * @param int   flag_value の値。
     */
    public function setValue($groupId, $ownerId, $flagId, $value) {

        // キー重複無視でインサート
        $record = array(
            'flag_group' => $groupId,
            'owner_id' => $ownerId,
            'flag_id' => $flagId,
            'flag_value' => $value,
        );
        if($this->createDao()->insert($record, false, true))
            return;

        // INSERT されていないなら UPDATE
        $this->updateRecord(array($groupId, $ownerId, $flagId), array(
            'flag_value' => $value
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたレコードでフラグをONにする。
     * setValue() を値1で呼び出しているのと同じ。
     */
    public function flagOn($groupId, $ownerId, $flagId) {

        return $this->setValue($groupId, $ownerId, $flagId, 1);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたレコードのflag_valueの値を 1 増加させる。
     * レコードがない場合は作成する。
     *
     * @param int   flag_group の値。このクラスの定数を使用する。
     * @param int   owner_id の値
     * @param int   flag_id の値。
     */
    public function countUp($groupId, $ownerId, $flagId) {

        $record = array(
            'flag_group' => $groupId,
            'owner_id' => $ownerId,
            'flag_id' => $flagId,
            'flag_value' => 1,
            'update_at' => array('sql'=>'NOW()'),
        );

        $onUpdate = array(
            'flag_value' => array('sql'=>'flag_value + 1')
        );

        $this->saveRecord($record, $onUpdate);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたフラグを削除する。
     *
     * @param int   flag_group の値。このクラスの定数を使用する。
     * @param int   owner_id の値
     */
    public function clearFlag($groupId, $ownerId) {

        $where = array();
        $where['flag_group'] = $groupId;
        $where['owner_id'] = $ownerId;

        $this->createDao()->delete($where);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたフラグを持っている人数をカウントする。
     *
     * @param int   flag_group の値。このクラスの定数を使用する。
     * @param int   flag_id の値。
     * @return int  フラグを持っている人数。
     */
    public function countFlagHolders($groupId, $flagId) {

        return $this->countRecord(array(
            'flag_group' => $groupId,
            'flag_id' => $flagId,
        ));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * ログを取得する
     */
    public function getBtcLog($condition) {

        $dao = $this->createDao(true);

        // 固定の条件を作成。
        $where = array();

        // 指定された条件を組み込む。
        if(strlen($condition['id']))  $where['owner_id'] = $condition['id'];
        if(strlen($condition['update_at_from']))  $where['update_at:1'] = array('sql'=>'>= ?', 'value'=>$condition['update_at_from']);
        if(strlen($condition['update_at_to']))    $where['update_at:2'] = array('sql'=>'< ?', 'value'=>$condition['update_at_to']);
        $where['ORDER BY'] = 'update_at desc';

        $sql = '
            SELECT *
            FROM vcoin_flag_log
        ' . DataAccessObject::buildWhere($where, $params);

        // 検索。
        $ret = $dao->getAll($sql, $params);

        // リターン。
        return $ret;
    }

    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('flag_group', 'owner_id', 'flag_id');


    //-----------------------------------------------------------------------------------------------------
    /**
     * updateRecordをオーバーライド。
     * "update_at" 列も更新するようにする。
     */
    public function updateRecord($pk, $update) {

        if( !array_key_exists('update_at', $update) )
            $update['update_at'] = array('sql'=>'NOW()');

        return parent::updateRecord($pk, $update);
    }
}
