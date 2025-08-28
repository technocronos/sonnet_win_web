<?php

class Cache_InfoService extends Service {

    // group_id の値を表す定数。
    const GRADE = 1;            // 階級ごとのキャラ人数
    const SOCIALITY = 2;        // 階級ごとのキャラ人数


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたグループの指定されたデータIDのvalueの値を返す。
     *
     * @param int       グループID
     * @param int       データID
     * @return mixed    "value"列の値。レコードがなかった場合は false。
     */
    public function getValue($groupId, $dataId) {

        $record = $this->getRecord($groupId, $dataId);

        return $record ? $record['value'] : false;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたグループのキャッシュを返す。
     *
     * @param int       グループID
     * @param array     レコードを絞り込む条件がある場合は指定する。
     *                  指定の仕方は DataAccessObject::buildWhere() に準ずる。
     * @return array    "data_id"列の値をインデックス、"value"列の値を値とする配列
     */
    public function getGroup($groupId, $condition = array()) {

        // グループIDを条件に追加。
        $condition['group_id'] = $groupId;

        // 指定されたグループのレコードを取得。
        $resultset = $this->selectResultset($condition);

        // "data_id"列の値をインデックス、"value"列の値を値とする配列を作成してリターン。
        return ResultsetUtil::colValues($resultset, 'value', 'data_id');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたグループのレコード数を返す。
     *
     * @param int       グループID
     * @return int      レコード数
     */
    public function countEntries($groupId) {

        $sql = '
            SELECT COUNT(*)
            FROM cache_info
            WHERE group_id = ?
        ';

        return $this->createDao(true)->getOne($sql, $groupId);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたグループに指定のキャッシュを追加する。
     *
     * @param int       グループID
     * @param array     "data_id"列の値をインデックス、"value"列の値を値とする配列。
     *                  すでにレコードが存在するものは無視される。
     */
    public function insertGroup($groupId, $data) {

        // 追加分がカラなら即リターン。
        if(!$data)
            return;

        $inserter = new BulkInserter('cache_info', $this->createDao());

        foreach($data as $index => $value)
            $inserter->insert($groupId, $index, $value);

        $inserter->flush();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたグループのキャッシュを削除する。
     *
     * @param int       グループID
     */
    public function clearGroup($groupId) {

        $sql = '
            DELETE FROM cache_info
            WHERE group_id = ?
        ';

        $this->createDao()->execute($sql, $groupId);
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('group_id', 'data_id');

    protected $isMaster = true;
}
