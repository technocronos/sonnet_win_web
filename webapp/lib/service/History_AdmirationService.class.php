<?php

class History_AdmirationService extends Service {

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された履歴の称賛の数を返す。
     *
     * @param int       履歴ID
     * @return int      称賛の数
     */
    public function getGoodnessCount($historyId) {

        return $this->countRecord(array('history_id'=>$historyId));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された履歴を称賛したレコードをページ分けして取得する。
     *
     * @param int       履歴ID
     * @param int       1ページあたりの件数。
     * @param int       何ページ目か。0スタート。
     * @return array    DataAccessObject::getPage と同様。
     */
    public function getAdmirerList($historyId, $showOnPage, $page) {

        $condition = array(
            'history_id' => $historyId,
            'ORDER BY' => 'create_at',
        );

        return $this->selectPage($condition, $showOnPage, $page);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された履歴の称賛のログを削除する。
     *
     * @param int       履歴ID。配列での複数指定も可能。
     */
    public function deleteAdmiration($historyIds) {

        $this->createDao()->delete(array('history_id'=>$historyIds));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('history_id', 'admirer_id');
}
