<?php

class Coin_LogService extends Service {


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
    public function sumupCoin($startDate, $endDate) {

        // SQL作成＆実行
        $sql = "
            SELECT DATE_FORMAT(create_at,'%Y-%m-%d') AS sale_date
                 , CONCAT(item_type, item_id) AS item
                 , unit_price
                 , SUM(amount) AS amount
                 , SUM(amount) * unit_price AS sales
            FROM coin_log
            WHERE create_at >= ?
              AND create_at < ?
            GROUP BY sale_date, item_type, item_id, unit_price
        ";

        return $this->createDao(true)->getAll($sql, array($startDate, $endDate));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'id';
}
