<?php

class Flag_LogService extends Service {

    // flag_group の値。
    const PARAM_UP = 1;         // パラメータアップアイテムの使用回数。
    const CLEAR = 2;            // クエストのクリア回数、あるいはストーリー上の通過フラグ
    const TRY_COUNT = 3;        // クエスト挑戦回数
    const MISSION = 4;          // ミッション達成回数
    const DISTRIBUTION = 5;     // 外部キャンペーン
    const FLAG = 6;             // フィールドクエストで使用するフラグ
    const FIRST_TRY = 7;        // クエストの初回突入レベル

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
     * 引数で指定されたグループのフラグを、flag_idごとに集計する。
     *
     * @param int       flag_group の値。このクラスの定数を使用する。
     * @return array    以下の列を含む結果セット。
     *                      flag_id     flag_idの値
     *                      count       レコードの数
     *                      sum         flag_valueの合計値
     */
    public function sumupFlagGroup($groupId) {

        $sql = "
            SELECT flag_id
                 , COUNT(*) AS count
                 , SUM(flag_value) AS sum
            FROM flag_log inner join user_info ON flag_log.owner_id = user_info.user_id
            WHERE flag_group = ? and user_info.last_access_date  > '" . RELEASE_DATE . "'
            GROUP BY flag_id
        ";
        return $this->createDao(true)->getAll($sql, $groupId);
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
