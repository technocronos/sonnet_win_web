<?php

class Vcoin_Payment_LogService extends Service {

    // statusの値。
    const STATUS_INITIAL = 0;
    const STATUS_RECEIVE = 10;
    const STATUS_COMPLETE = 20;
    const STATUS_CANCEL = 30;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 配信の一覧を、ページを指定して取得する。
     *
     * @param int       1ページあたりの件数。
     * @param int       何ページ目か。0スタート。
     * @return array    DataAccessObject::getPage と同様。
     */
    public function getList($numOnPage, $page) {

        $where = array();
        $where ['ORDER BY'] = 'status_update_at DESC';

        return $this->selectPage($where, $numOnPage, $page);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザーIDで検索する
     *
     * @param user_id     ユーザーID。
     */
    public function getStatus($status) {

        // SQL作成＆実行
        $sql = "
            SELECT *
            FROM vcoin_payment_log
            WHERE status = ?
            ORDER BY create_at DESC
        ";

        return $this->createDao(true)->getAll($sql, array($status));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザーIDで検索する
     *
     * @param user_id     ユーザーID。
     */
    public function getUserList($user_id) {

        // SQL作成＆実行
        $sql = "
            SELECT *
            FROM vcoin_payment_log
            WHERE user_id = ?
            ORDER BY create_at DESC
        ";

        return $this->createDao(true)->getAll($sql, array($user_id));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 一番最後に申請したのはいつか検索する
     *
     * @param user_id     ユーザーID。
     */
    public function getLatestApplyDate($user_id) {

        // SQL作成＆実行
        $sql = "
          SELECT MAX(vcoin_payment_log.create_at)
          FROM vcoin_payment_log
          WHERE status = ? AND user_id = ?";

         return $this->createDao(true)->getOne($sql, array(self::STATUS_COMPLETE, $user_id));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された決済情報を受信状態にする。
     * 結果コードが "ok":決済完了 ならstatus列を10に、"cancel":キャンセル ならばstatus列を30にする。
     *
     * @param string    決済ID
     * @param string    結果コード。"ok":決済完了  "cancel":キャンセル  のいずれか。
     * @param array     完了通知で送られている情報全体。
     * @return array    成功時は対象の決済レコード。すでに受信状態である場合はnull。
     */
    public function receivePayment($logId, $transaction) {

        // 指定の決済レコードを取得。
        $record = $this->needRecord($logId);

        // すでに受信状態である場合はnullリターン。
        if($record['status'] != self::STATUS_INITIAL)
            return null;

        // 完了状態にする。
        $this->updateRecord($logId, array(
            'status' => self::STATUS_RECEIVE,
            'transaction' => $transaction,
            'status_update_at' => array('sql'=>'NOW()'),
        ));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された決済情報を完了状態にする。
     *
     * @param string    決済ID
     * @param array     reference_idにセットしたい値。
     */
    public function succeedPayment($logId) {

        // 完了状態にする。
        $this->updateRecord($logId, array(
            'status' => self::STATUS_COMPLETE,
            'status_update_at' => array('sql'=>'NOW()'),
        ));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'payment_id';
}
