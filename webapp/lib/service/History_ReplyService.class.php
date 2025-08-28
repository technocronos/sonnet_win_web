<?php

class History_ReplyService extends Service {

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された履歴に対するレスの数を取得する。
     *
     * @param int       履歴ID
     * @return int      レスの数
     */
    public function getReplyCount($historyId) {

        return $this->countRecord(array('reply_to'=>$historyId));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された履歴に対するすべてのレスのIDを配列として返す。
     *
     * @param int       履歴ID
     * @return array    すべてのレスのIDを収めた配列。レスがない場合はカラの配列。
     */
    public function getReplyIds($historyId) {

        $sql = '
            SELECT history_id
            FROM history_reply
            WHERE reply_to = ?
        ';

        return $this->createDao()->getCol($sql, $historyId);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された履歴がレスしている履歴のIDを配列として返す。
     *
     * @param int       履歴ID
     * @return array    レスしている履歴のIDを収めた配列。レスしていない場合はカラの配列。
     */
    public function getReplyTo($historyId) {

        $sql = '
            SELECT reply_to
            FROM history_reply
            WHERE history_id = ?
        ';

        return $this->createDao()->getCol($sql, $historyId);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された履歴の返信データを削除する。
     *
     * @param int       履歴ID。配列での複数指定も可能。
     */
    public function deleteReply($historyIds) {

        $this->createDao()->delete(array('history_id'=>$historyIds));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('history_id', 'reply_to');
}
