<?php

class Message_LogService extends Service {

    // メッセージ送信による特典付与を一日に何度まで行うか。
    const FAVOR_LIMIT_PER_DAY = 30;

    // 同一人物へのメッセージ送信による特典付与を一日に何度まで行うか。
    const FAVOR_LIMIT_PER_COMPANION = 3;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザの、未読メッセージ件数を返す。
     */
    public function getUnreadCount($userId) {

        return $this->countRecord(array(
            'receive_user_id' => $userId,
            'checked' => 0,
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザの、送信メッセージ一覧を返す。
     * 引数・戻り値仕様は、第一引数以外は DataAccessObject::getPage と同様。
     */
    public function getSendList($userId, $numOnPage, $page = 0) {

        $condition = array(
            'send_user_id' => $userId,
            'ORDER BY' => 'create_at DESC',
        );

        return $this->selectPage($condition, $numOnPage, $page);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザの、受信メッセージ一覧を返す。
     * 引数・戻り値は、getSendList と同様。
     */
    public function getReceiveList($userId, $numOnPage, $page = 0) {

        $condition = array(
            'receive_user_id' => $userId,
            'ORDER BY' => 'create_at DESC',
        );

        return $this->selectPage($condition, $numOnPage, $page);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザから指定のユーザへメッセージ送信可能かどうかを調べる。
     *
     * @param int       送信する側のユーザID
     * @param int       送信される側のユーザID
     * @return string   以下のコードのいずれか
     *                      ok                  送信可能
     *                      self_communicate    自分に送信しようとしている
     *                      black_list          ブラックリスト
     */
    public function canCommunicate($userId, $companionId) {

        // 自分に送信しようとしていないかチェック。
        if($userId == $companionId)
            return 'self_communicate';

        // ブラックリストに登録されていないかチェック。
        if( PlatformApi::isForbidden($companionId, $userId) )
            return 'black_list';

        // ここまでくればOK。
        return 'ok';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザへメッセージを送るとともに、ポイント付与等の付随する処理も行う。
     *
     * @param int       送信側ユーザID
     * @param int       受信側ユーザID
     * @param string    メッセージ内容
     * @return string   次の値のいずれか
     *                      daily_limit         一日の付与制限で特典なし
     *                      companion_limit     同じ相手による制限で特典なし
     *                      take_favor          特典獲得
     */
    public function sendMessage($senderId, $recipientId, $body) {

        // 特典付与判定＆処理。
        $favor = $this->giveMessageFavor($senderId, $recipientId);

        // プラットフォームにメッセージを提出。
        $textSvc = new Text_LogService();
        $textId = $textSvc->postText('MSG', $body, $senderId, $recipientId);

        // メッセージレコード作成。
        $this->insertRecord(array(
            'receive_user_id' => $recipientId,
            'send_user_id' => $senderId,
            'text_id' => $textId,
            'favor_flg' => ($favor == 'take_favor') ? 1 : 0,
        ));

        // 特典付与処理の結果をリターン。
        return $favor;
    }

    /**
     * sendMessageのヘルパ。
     * 引数で指定されたメッセージ送信による特典付与処理を行い、結果を返す。
     */
    private function giveMessageFavor($senderId, $recipientId) {

        // 一日の付与制限に達しているならリターン。
        if(self::FAVOR_LIMIT_PER_DAY <= $this->getFavorCount($senderId))
            return 'daily_limit';

        // 同じ相手の付与制限に達しているならリターン。
        if(self::FAVOR_LIMIT_PER_COMPANION <= $this->getFavorCount($senderId, $recipientId))
            return 'companion_limit';

        // ここまできたら特典付与。

        // 受け主のアバターに階級ポイントを付与。
        $charaSvc = new Character_InfoService();
        $avatarId = $charaSvc->needAvatarId($recipientId);
        $pt = 1;
        $charaSvc->gainGradePt($avatarId, $pt);

        // リターン。
        return 'take_favor';
    }

    /**
     * sendMessageのヘルパ。
     * 引数で指定されたメッセージ送信による特典付与が、本日何回行われているかを返す。
     */
    private function getFavorCount($senderId, $recipientId = null) {

        $condition = array(
            'send_user_id' => $senderId,
            'favor_flg' => 1,
            'create_at' => array('sql'=>'>= CURRENT_DATE'),
        );

        if( !is_null($recipientId) )
            $condition['receive_user_id'] = $recipientId;

        return $this->countRecord($condition);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザの受信メッセージをすべてチェック済みにする。
     *
     * @param int   ユーザID
     */
    public function markReceiverChecked($userId) {

        $where = array(
            'receive_user_id' => $userId,
            'checked' => 0,
        );

        $this->createDao()->update($where, array(
            'checked' => 1
        ));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'message_id';

    // 20日以上経過したレコードは削除するようにする。
    protected $deleteOlds = 20;


    //-----------------------------------------------------------------------------------------------------
    /**
     * processResultset をオーバーライド。body 列を追加する。
     * processRecordで処理できそうだが、コンテナサーバに何度も問い合わせをすることになるので問題がある。
     */
    public function processResultset(&$resultset) {

        // 結果セットのtext_id列を抜き出す。
        $ids = ResultsetUtil::colValues($resultset, 'text_id');

        // プラットフォームからテキスト取得。
        $svc = new Text_LogService();
        $texts = $svc->getTextsIn($ids);

        // 結果セットのbody列に埋め込んでいく。
        foreach($resultset as &$record)
            $record['body'] = $texts[ $record['text_id'] ];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * processRecord をオーバーライド。
     * processResultset を経由するようにする。
     */
    protected function processRecord(&$record) {

        $tempSet = array($record);

        $this->processResultset($tempSet);

        $record = $tempSet[0];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * deleteOldRecordsをオーバーライド。
     * 紐づいているText_Logレコードも削除するようにする。
     */
    protected function deleteOldRecords() {

        $dao = $this->createDao();

        // 削除対象になるレコードを取得。
        $sql = '
            SELECT message_id
                 , text_id
            FROM message_log
            WHERE create_at < NOW() - INTERVAL ? DAY
            LIMIT 40
        ';
        $resultset = $dao->getAll($sql, $this->deleteOlds);

        // なかったらリターン。
        if(!$resultset)
            return;

        // 紐づいているText_Logレコードを削除。
        $textIds = ResultsetUtil::colValues($resultset, 'text_id');
        Service::create('Text_Log')->deleteRecordsIn($textIds);

        // 削除対象のレコードを削除。
        $dao->delete(array(
            'message_id' => ResultsetUtil::colValues($resultset, 'message_id'),
        ));
    }
}
