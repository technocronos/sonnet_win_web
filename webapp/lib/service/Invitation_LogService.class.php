<?php

class Invitation_LogService extends Service {

    // 友達招待の特典。アイテムIDをキー、数量を値とする。
    public static $INVITE_BONUS = array(
        99003 => 5000, 1902 => 3, 1906 => 3
    );

    // 友達招待に応じた場合の特典。アイテムIDをキー、数量を値とする。
    public static $ANSWER_BONUS = array(
        99003 => 2000
    );

    const INVITE_BTC = 0.002;                     //　友達招待
    const INVITED_BTC = 0.001;                     //　友達招待された


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された日付範囲の招待数を集計する。
     *
     * @param string    集計範囲開始日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @param string    集計範囲終了日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @return array    次のキーを持つ配列。
     *                      create      招待日をインデックス、招待数を値とする配列。
     *                      accept      応諾日をインデックス、応諾数を値とする配列。
     */
    public function sumupInvitation($from, $to) {

        $result = array();

        // まずは招待日別に集計。
        $sql = "
            SELECT DATE_FORMAT(create_at, '%Y-%m-%d') date
                 , COUNT(*) as count
            FROM invitation_log
            WHERE create_at >= ?
              AND create_at < ?
            GROUP BY date
        ";

        $resultset = $this->createDao(true)->getAll($sql, array($from, $to));
        $result['create'] = ResultsetUtil::colValues($resultset, 'count', 'date');

        // 次に応諾日別に集計。
        $sql = "
            SELECT DATE_FORMAT(accept_date, '%Y-%m-%d') date
                 , COUNT(*) as count
            FROM invitation_log
            WHERE accept_date >= ?
              AND accept_date < ?
            GROUP BY date
        ";

        $resultset = $this->createDao(true)->getAll($sql, array($from, $to));
        $result['accept'] = ResultsetUtil::colValues($resultset, 'count', 'date');

        // リターン
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 仲間申請されたときのレコードを作成する。
     *
     * @param int   招待したユーザID
     * @param int   招待されたユーザID
     */
    public function makeInvitation($inviterId, $recipientId, $device_id) {

        //友達招待期間外の場合は何もしない
        if(!FRIEND_INVITE_OPEN) return;

        // 既存のレコードがないかチェック。
        $record = $this->getRecord($inviterId, $recipientId);

        // 既存のレコードがあって、すでに応じられているなら無視する。
        if(isset($record)  &&  $record['accept_date'])
            return -1;

        if($device_id != null){
            // 既存のdeviceIdレコードがないかチェック。
            $count = $this->getDeviceIdCount($device_id);

            // 既存のレコードがあって、すでに応じられているなら無視する。
            if($count > 0)
                return -2;
        }

        // 既存のレコードがある場合は create_at を更新。ない場合は作成する。
        if(isset($record)) {
            $this->updateRecord(array($inviterId, $recipientId), array(
                'create_at' => array('sql'=>'NOW()')
            ));
        }else {
            $this->insertRecord(array(
                'inviter_id' => $inviterId,
                'recipient_id' => $recipientId,
                'device_id' => $device_id,
            ));
        }

        return 1;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * すでに招待を応諾したdevice_idのレコードがあるかどうか調べる
     *
     * @param device_id     デバイスID。
     */
    public function getDeviceIdCount($device_id) {

        // SQL作成＆実行
        $sql = "
          SELECT count(*)
          FROM invitation_log
          WHERE device_id = '" . $device_id . "' AND accept_date IS NOT NULL 
          ";

         return $this->createDao(true)->getOne($sql);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されているユーザが招待に応じたときの特典付与などを処理する。
     *
     * @param int       招待に応じたユーザのID
     */
    public function congraturateInvitation($answererId) {

        $uitemSvc = new User_ItemService();

        // アプリ招待されているなら、レコード更新＆招待レコードの取得。
        $inviterIds = $this->answerInvitation($answererId);

        // 招待したユーザすべてに対して...
        foreach($inviterIds as $inviterId) {

            // 招待したユーザに通常特典を付与する。
            foreach(self::$INVITE_BONUS as $itemId => $num)
                $uitemSvc->gainItem($inviterId, $itemId, $num);

            // 友達関係にする。ここでは友達人数上限は考慮しない。せっかく招待してくれたんだから...
            Service::create('User_Member')->makeFriend($inviterId, $answererId);

            // 履歴に挿入
            Service::create('History_Log')->insertRecord(array(
                'type' => History_LogService::TYPE_INVITE_SUCCESS,
                'user_id' => $inviterId,
                'ref1_value' => $answererId,
            ));

            Service::create('User_Info')->setVirtualCoin($inviterId, Vcoin_Flag_LogService::INVITE, self::INVITE_BTC, $answererId);

Common::varLog("友達招待した user_id=" . $inviterId . " flag_id=" . $answererId . " prize=" . self::INVITE_BTC);

            // つぶやいたユーザにメッセージ送信
            $body = '友だち招待に応じてくれました。特典ｹﾞｯﾄ';
            PlatformApi::sendMessage($inviterId, $body, '友だち招待に応じてくれました');
        }

        // 招待されたユーザにも...
        if(count($inviterIds) > 0) {

            // 特典付与。
            foreach(self::$ANSWER_BONUS as $itemId => $num)
                $uitemSvc->gainItem($answererId, $itemId, $num);

            // 招待されたことによる特典ゲットの履歴を入れる。
            Service::create('History_Log')->insertRecord(array(
                'type' => History_LogService::TYPE_INVITE_SUCCESS,
                'user_id' => $answererId,
                'ref1_value' => 0,
            ));

            Service::create('User_Info')->setVirtualCoin($answererId, Vcoin_Flag_LogService::INVITED, self::INVITED_BTC, $inviterId);

Common::varLog("友達招待された user_id=" . $answererId . " flag_id=" . $inviterId . " prize=" . self::INVITED_BTC);

        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されているユーザが招待されているレコードを、応諾として更新する。
     *
     * @param int       ユーザID
     * @return array    指定のユーザを招待したユーザのID一覧。
     */
    public function answerInvitation($userId) {

        // 指定のユーザが招待されたレコードを抽出するための条件を作成。
        $where = array(
            'recipient_id' => $userId,
            'accept_date' => null,
        );

        // 先に取得する。
        $records = $this->selectResultset($where);

        // accept_dateを現在日時で更新。
        $this->createDao()->update($where, array(
            'accept_date' => array('sql'=>'NOW()'),
        ));

        // inviter_id列の値を配列として返す。
        return ResultsetUtil::colValues($records, 'inviter_id');
    }

    public function getRecipientCount($userId) {
        return $this->countRecord(array('recipient_id'=>$userId));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('inviter_id', 'recipient_id');
}
