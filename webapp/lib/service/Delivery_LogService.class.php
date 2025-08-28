<?php

class Delivery_LogService extends Service {

    // "step" 列の値。
    const WAIT = 0;     // 待機
    const GOING = 1;    // 送信中
    const FINISH = 2;   // 終了
    const STOP = 3;     // 停止


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
        $where ['ORDER BY'] = 'start_at DESC';

        return $this->selectPage($where, $numOnPage, $page);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 次の配信タイミングで送信すべき内容を返す。
     *
     * @param int       配信対象のユーザIDを最大でいくつ取得するか。
     * @return array    送信すべき内容を持っている delivery_log レコード。
     *                  ただし、"user_ids" キーに今回の配信対象のユーザIDの配列が格納されている。
     *                  送信すべきものがないならnull。
     */
    public function nextDeliveryInfo($targetCount) {

        // ステータスが待機中か配信中、かつ、開始時間が過ぎているレコードを一つ取得する。
        $record = $this->selectRecord(array(
            'step' => array('sql'=>'<= ?', 'value'=>self::GOING),
            'start_at' => array('sql'=>'<= NOW()'),
            'ORDER BY' => 'start_at',
            'LIMIT' => 1,
        ));

        // なかったら送信すべき内容はない。
        if(!$record)
            return null;

        // 対象条件を取得。
        $condition = $record['target'];

        // すでに送ったユーザを除外する条件を追加。
        $condition['id_except_upper'] = is_null($record['last_send_id']) ? 0 : $record['last_send_id'];

        // アンインストールユーザを除外する条件を追加。
        $condition['except_retire'] = true;

        // 対象ユーザを取得。
        $record['user_ids'] = Service::create('User_Info')->findUsers($condition, $targetCount);

        // もう対象ユーザがいないなら...
        if(!$record['user_ids']) {

            // 該当レコードを配信終了にセット。
            $this->updateRecord($record['delivery_id'], array(
                'step' => self::FINISH,
                'finish_at' => array('sql'=>'NOW()'),
            ));

            // 再帰して、次の配信予定レコードを取得する。
            return $this->nextDeliveryInfo($targetCount);
        }

        // リターン。
        return $record;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 配信が行った後の処理を行う。
     *
     * @param int       配信を行った配信ID
     * @param array     配信したユーザIDの配列
     */
    public function endDelivery($deliveryId, $targetIds) {

        $this->updateRecord($deliveryId, array(
            'step' => self::GOING,
            'last_send_id' => max($targetIds),
            'send_count' => array('sql'=>'send_count + ?', 'value'=>count($targetIds)),
        ));

    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された配信を停止状態にする。
     *
     * @param int   配信ID
     */
    public function stopDelivery($deliveryId) {

        $this->updateRecord($deliveryId, array(
            'step' => self::STOP,
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された効果測定キーを持つ配信レコードの効果測定値をカウントアップする。
     *
     * @param string    効果測定キー。open_key 列の値。
     */
    public function countupWonderness($key) {

        $rate = 1;

        // キーなしの場合は処理しない。
        if(!$key)
            return;

        // 一定確率でしかカウントしない。
#         if(mt_rand(1, $rate) > 1)
#             return;

        $sql = '
            UPDATE delivery_log
            SET wonderness = wonderness + ?
            WHERE open_key = ?
        ';

        $this->createDao()->execute($sql, array($rate, $key));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'delivery_id';


    //-----------------------------------------------------------------------------------------------------
    /**
     * processRecordをオーバーライド。
     * target列をデコードしておく。
     */
    protected function processRecord(&$record) {

        $record['target'] = json_decode($record['target'], true);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * insertRecord をオーバーライド。
     * open_keyを設定するようにする。
     */
    public function insertRecord($values, $returnAutoNumber = false) {

        if(!$values['open_key'])
            $values['open_key'] = Common::createRandomString(16);

        // INSERT。
        return parent::insertRecord($values, $returnAutoNumber);
    }
}
