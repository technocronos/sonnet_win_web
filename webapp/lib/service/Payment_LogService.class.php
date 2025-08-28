<?php

class Payment_LogService extends Service {

    // statusの値。
    const STATUS_INITIAL = 0;
    const STATUS_RECEIVE = 10;
    const STATUS_COMPLETE = 20;
    const STATUS_CANCEL = 30;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された日付範囲で売上を集計する。
     *
     * @param mixed     集計範囲開始日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @param mixed     集計範囲終了日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @return array    以下の列を含む結果セット
     *                      sale_date   売上日
     *                      item        アイテム種別(payment_log.item_type)に
     *                                  アイテムID(payment_log.item_id)を連結したもの
     *                      unit_price  単価
     *                      amount      個数
     *                      sales       売上額 (単価 x 個数)
     */
    public function sumupPayment($startDate, $endDate) {

        // SQL作成＆実行
        $sql = "
            SELECT DATE_FORMAT(status_update_at,'%Y-%m-%d') AS sale_date
                 , CONCAT(item_type, item_id) AS item
                 , unit_price
                 , SUM(amount) AS amount
                 , SUM(amount) * unit_price AS sales
                 , CASE WHEN ready_data like '%AppleAppStore%' THEN 'ios' ELSE 'android' END as os
            FROM payment_log
            WHERE status = ?
              AND status_update_at >= ?
              AND status_update_at < ?
            GROUP BY sale_date, item_type, item_id, unit_price, os
        ";

        return $this->createDao(true)->getAll($sql, array(self::STATUS_COMPLETE, $startDate, $endDate));

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザーの売上を集計する。
     */
    public function sumupUserPayment($user_id) {

        // SQL作成＆実行
        $sql = "
            SELECT SUM(unit_price) as sales
            FROM payment_log
            WHERE status = ?
            AND user_id = ? 
        ";

        return $this->createDao(true)->getAll($sql, array(self::STATUS_COMPLETE, $user_id));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザーの売上を集計する。
     */
    public function sumupUserPaymentWithDate($user_id, $date) {

        // SQL作成＆実行
        $sql = "
            SELECT SUM(unit_price) as sales
            FROM payment_log
            WHERE status = ?
            AND user_id = ? 
            AND status_update_at > ?
        ";

        return $this->createDao(true)->getAll($sql, array(self::STATUS_COMPLETE, $user_id, $date));
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
    public function receivePayment($paymentId, $resultCode, $receiveData) {

        // 指定の決済レコードを取得。
        $record = $this->needRecord($paymentId);

        // すでに受信状態である場合はnullリターン。
        if($record['status'] != self::STATUS_INITIAL)
            return null;

        // 受信状態にする。
        $this->updateRecord($paymentId, array(
            'status' => ($resultCode == 'ok') ? self::STATUS_RECEIVE : self::STATUS_CANCEL,
            'receive_data' => json_encode($receiveData),
            'status_update_at' => array('sql'=>'NOW()'),
        ));

        // リターン。
        return $record;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された決済情報を完了状態にする。
     *
     * @param string    決済ID
     * @param array     reference_idにセットしたい値。
     */
    public function succeedPayment($paymentId, $completeData) {

        // 完了状態にする。
        $this->updateRecord($paymentId, array(
            'status' => self::STATUS_COMPLETE,
            'reference_id' => $completeData,
            'status_update_at' => array('sql'=>'NOW()'),
        ));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'payment_id';
}
