<?php

class Approach_LogService extends Service {

    // status列の値。
    const STATUS_INIT = 0;
    const STATUS_OK = 1;
    const STATUS_NG = 2;
    const STATUS_OK_CONFIRMED = 11;
    const STATUS_NG_CONFIRMED = 12;
    const STATUS_CANCEL_CONFIRMED = 13;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザが関わる仲間申請未回答レコードの数を返す。
     *
     * @param int       ユーザID
     * @return array    次のキーを持つ配列
     *                      request     申請している数
     *                      receive     申請されている数
     */
    public function getUnansweredCount($userId) {

        $sql = '
            SELECT (
                        SELECT COUNT(*)
                        FROM approach_log
                        WHERE approacher_id = :id
                          AND status = :status
                   ) AS request
                 , (
                        SELECT COUNT(*)
                        FROM approach_log
                        WHERE recipient_id = :id
                          AND status = :status
                   ) AS receive
            FROM DUAL
        ';

        $params = array(
            'id' => $userId,
            'status' => self::STATUS_INIT,
        );

        return $this->createDao(true)->getRow($sql, $params);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザが仲間申請したもので、回答済みだが未確認のレコードの数を返す。
     *
     * @param int       ユーザID
     * @return int      該当のレコード数
     */
    public function getUnconfirmedCount($userId) {

        return $this->countRecord(array(
            'approacher_id' => $userId,
            'status' => array(self::STATUS_OK, self::STATUS_NG),
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザの仲間申請の一覧を取得する。
     *
     * @param int       ユーザID
     * @param string    送信分を取得するなら "send"、受信分を取得するなら "receive"。
     * @param int       1ページあたりの件数。
     * @param int       何ページ目か。0スタート。
     * @return array    Service::selectPage と同様。
     *                  結果セットの列は基本的には approach_log だが、相手ユーザのレコードが
     *                  格納されている "companion" 列が加わっている。
     */
    public function getList($userId, $side, $showOnPage, $page) {

        // 受信・送信で変わらない条件をセット。
        $where = array();
        $where['ORDER BY'] = 'approach_log.create_at';

        // 受信・送信で変わる条件をセット。
        if($side == 'send') {
            $where['approach_log.status'] = array(self::STATUS_INIT, self::STATUS_OK, self::STATUS_NG);
            $where['approach_log.approacher_id'] = $userId;
            $companionColumn = 'recipient_id';
        }else {
            $where['approach_log.status'] = self::STATUS_INIT;
            $where['approach_log.recipient_id'] = $userId;
            $companionColumn = 'approacher_id';
        }

        // ページ分けで approach_log レコードを取得。
        $list = $this->selectPage($where, $showOnPage, $page);

        // 相手ユーザのID一覧を配列で取得。
        $companionIds = ResultsetUtil::colValues($list['resultset'], $companionColumn);

        // 相手ユーザの情報を一括で取得。ユーザIDをキーとする配列にする。
        $userSvc = new User_InfoService();
        $users = $userSvc->getRecordsIn($companionIds);

        // 結果セットの "companion" 列に相手ユーザのレコードを埋め込んでいく。
        foreach($list['resultset'] as &$record) {
            $record['companion'] = $users[ $record[$companionColumn] ];
        }unset($record);

        // リターン。
        return $list;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザが仲間申請しているユーザIDの一覧を取得する。
     *
     * @param int       ユーザID
     * @return array    仲間申請しているユーザIDすべてを配列で。
     */
    public function getApproacherIds($userId) {

        $sql = '
            SELECT recipient_id
            FROM approach_log
            WHERE approacher_id = ?
              AND status = ?
        ';

        return $this->createDao(true)->getCol($sql, array(
            $userId, self::STATUS_INIT
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザからユーザへ、仲間申請を出せるかどうかを返す。
     *
     * @param int       申請側ユーザID
     * @param int       受信側ユーザID
     * @return string   以下のコードのいずれか
     *                      ok                  申請可能
     *                      recipient_limit     申請相手が上限一杯
     *                      inviter_limit       申請者が上限一杯
     *                      cross_request       既に申請を受けている
     *                      member_already      既に仲間になっている
     *                      self_request        自分に申請しようとしている
     */
    public function checkApproachable($inviterId, $recipientId) {

        $memberSvc = new User_MemberService();

        // 申請者の仲間上限をチェック
        if( !$memberSvc->canIncreaseMember($inviterId) )
            return 'inviter_limit';

        // 受信者の仲間上限をチェック
        if( !$memberSvc->canIncreaseMember($recipientId) )
            return 'recipient_limit';

        // 受信者から申請を受けていないかチェック
        if( $this->getApproachRecord($recipientId, $inviterId) )
            return 'cross_request';

        // すでに仲間でないかチェック。
        if( $memberSvc->getRecord($inviterId, $recipientId) )
            return 'member_already';

        // 自分に申請しようとしてないかチェック。
        if($inviterId == $recipientId)
            return 'self_request';

        // ここまでくればOK。
        return 'ok';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザからユーザへの仲間申請中のレコードを取得する。
     *
     * @param int       申請側ユーザID
     * @param int       受信側ユーザID
     * @return array    仲間申請レコード。なかった場合はNULL。
     */
    public function getApproachRecord($approacherId, $recipientId) {

        return $this->selectRecord(array(
            'approacher_id' => $approacherId,
            'recipient_id' => $recipientId,
            'status' => self::STATUS_INIT,
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 仲間申請のレコードを作成する。
     *
     * @param int   招待したユーザID
     * @param int   招待されたユーザID
     */
    public function makeApproach($approacherId, $recipientId) {

        // 対象の情報を表すレコードを作成。
        $where = array(
            'approacher_id' => $approacherId,
            'recipient_id' => $recipientId,
            'status' => self::STATUS_INIT
        );

        // 既に申請が行われていて、未回答の場合のものがある場合に備えて、create_atを更新してみる。
        $recordExists = $this->createDao()->update($where, array(
            'create_at' => array('sql'=>'NOW()')
        ));

        // 対象レコードがなかった場合は作成する。
        if(!$recordExists)
            $this->insertRecord($where);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 申請の結果をセットする。
     *
     * @param mixed     申請レコードのID。複数同時に更新する場合は配列で指定する。
     * @param string    status列の値。このクラスの定数 STATUS_OK, STATUS_NG, STATUS_CANCEL_CONFIRMED の
     *                  いずれか。
     */
    public function setResult($recordId, $result) {

        $this->updateRecord($recordId, array(
            'status' => $result,
            'answer_date' => array('sql'=>'NOW()'),
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたユーザが出した仲間申請で、返事が来ているものをすべて確認済みにする。
     *
     * @param int       ユーザID
     */
    public function confirmResult($userId) {

        $where = array(
            'approacher_id' => $userId,
            'status' => array(self::STATUS_OK, self::STATUS_NG),
        );

        $this->createDao()->update($where, array(
            'status' => array('sql'=>'status + 10'),
        ));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'approach_id';
}
