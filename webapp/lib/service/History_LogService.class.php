<?php

class History_LogService extends Service {

    // typeの値。
    const TYPE_BATTLE_CHALLENGE = 1;
    const TYPE_BATTLE_DEFENCE = 2;
    const TYPE_CHANGE_GRADE = 3;
    const TYPE_LEVEL_UP = 4;
    const TYPE_EFFECT_TIMEUP = 5;
    const TYPE_INVITE_SUCCESS = 6;
    const TYPE_PRESENTED = 7;
    const TYPE_QUEST_FIN = 8;           // 廃止
    const TYPE_ITEM_BREAK = 10;
    const TYPE_ITEM_LVUP = 11;
    const TYPE_WEEKLY_HIGHER = 12;
    const TYPE_CAPTURE = 13;
    const TYPE_ADMIRED = 14;
    const TYPE_REPLIED = 15;
    const TYPE_COMMENT = 16;
    const TYPE_QUEST_FIN2 = 17;
    const TYPE_TEAM_BATTLE = 18;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザの、履歴一覧を返す。
     * 戻り値に含まれる結果セットには、type列の値に応じてaddExColumn()で説明する疑似列が加えられる。
     *
     * @param int       ユーザID
     * @param string    コメントの履歴を取得するなら "comment"、出来事の履歴を取得するなら "history"。
     * @param int       1ページあたりの件数。省略時は10。
     * @param int       取得するページ。0スタート。省略時は0。
     * @return array    Service::selectPageと同様。
     */
    public function getUserHistory($userId, $filter, $numOnPage, $page = 0) {

        switch($filter) {
            case 'comment':     $type = self::TYPE_COMMENT; break;
            case 'history':     $type = array('sql'=>"!= ?", 'value'=>self::TYPE_COMMENT);  break;
            case 'team':        $type = self::TYPE_TEAM_BATTLE; break;
        }

        // 該当データを取得するための条件を作成。
        $condition = array(
            'user_id' => $userId,
            'type' => $type,
            'ORDER BY' => 'create_at DESC',
        );

        // 取得。該当データの結果セットに対して疑似列を追加する。
        $result = $this->selectPage($condition, $numOnPage, $page);
        $this->addExColumn($result['resultset'], true);

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザと、その仲間の履歴のうち、特定の種別のものを発生日順に取得する。
     * ※パフォーマンス上問題が出やすいので、過去数日以内に発生したものに限定する。
     *
     * @param int       ユーザID
     * @param int       1ページあたりの件数。省略時は10。
     * @param int       取得するページ。0スタート。省略時は0。
     * @return array    Service::selectPageと同様。
     */
    public function getFriendsHistory($userId, $numOnPage, $page = 0) {

        // 指定されたユーザの仲間のID一覧を取得。
        $memberIds = Service::create('User_Member')->getMemberIds($userId);

        // 該当データを取得するための条件を作成。
        $condition = array(
            'user_id' => $memberIds,
            'type' => array(
                self::TYPE_CHANGE_GRADE,    self::TYPE_QUEST_FIN,   self::TYPE_WEEKLY_HIGHER,
                self::TYPE_CAPTURE,         self::TYPE_QUEST_FIN2,  self::TYPE_BATTLE_CHALLENGE
            ),
            'create_at' => array('sql'=>'>= NOW() - INTERVAL 14 DAY'),
            'ORDER BY' => 'create_at DESC',
        );

        // 取得。該当データの結果セットに対して疑似列を追加する。
        $list = $this->selectPage($condition, $numOnPage, $page);
        $this->addExColumn($list['resultset'], true);

        // リターン。
        return $list;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザとその仲間のコメントをページ分けして取得する。
     * ※パフォーマンス上問題が出やすいので、過去数日以内に発生したものに限定する。
     *
     * @param int       ユーザID
     * @param int       1ページあたりの件数。省略時は10。
     * @param int       取得するページ。0スタート。省略時は0。
     * @return array    Service::selectPageと同様。
     */
    public function getTimeLine($userId, $numOnPage, $page = 0) {

        // 指定されたユーザとその仲間のID一覧を取得。
        $userIds = Service::create('User_Member')->getMemberIds($userId);
        $userIds[] = $userId;

        // 該当データを取得するための条件を作成。
        $condition = array(
            'user_id' => $userIds,
            'type' => self::TYPE_COMMENT,
            'create_at' => array('sql'=>'>= NOW() - INTERVAL 14 DAY'),
            'ORDER BY' => 'create_at DESC',
        );

        // 取得。該当データの結果セットに対して疑似列を追加する。
        $list = $this->selectPage($condition, $numOnPage, $page);
        $this->addExColumn($list['resultset'], true);

        // リターン。
        return $list;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザの、引数で指定されたタイプの履歴のうち、まだチェック済みでないレコードを返す
     * とともに、チェック済みにする(オプション)。
     *
     * @param int       ユーザID
     * @param int       種別。このクラスの定数を使用。配列で複数指定もできる。
     * @param bool      チェックのみで、チェック済みにしたくない場合はtrueを指定する。
     * @return array    チェック済みでなかったレコードを列挙する結果セット。
     */
    public function checkHistory($userId, $type, $checkOnly = false) {

        // 該当レコードの条件を作成。
        $condition = array(
            'history_log.user_id' => $userId,
            'history_log.type' => $type,
            'history_log.check_flg' => 0,
            'ORDER BY' => 'history_log.create_at',
        );

        // 取得。
        $resultset = $this->selectResultset($condition);

        // 疑似列を追加する。
        $this->addExColumn($resultset, true);

        // チェック済みにする。
        if(!$checkOnly  &&  count($resultset) > 0) {
            $this->createDao()->update($condition, array(
                'check_flg' => 1,
            ));
        }

        // リターン。
        return $resultset;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザの一番新しいコメント履歴のレコードを取得する。
     *
     * @param int       ユーザID
     * @return array    一番新しいコメント履歴のレコード。コメントしていない場合はnull。
     */
    public function getNewestComment($userId) {

        $record = $this->selectRecord(array(
            'type' => self::TYPE_COMMENT,
            'user_id' => $userId,
            'ORDER BY' => 'create_at DESC',
        ));

        $this->addExColumn($record);

        return $record;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された履歴に対するレスをページ分けして取得する。
     *
     * @param int       履歴ID
     * @param int       1ページの件数
     * @param int       取得するページ番号
     * @return array    DataAccessObject::getPage と同様。
     */
    public function getReplies($historyId, $numOnPage, $page = 0) {

        $ids = Service::create('History_Reply')->getReplyIds($historyId);

        $condition = array(
            'history_id' => $ids,
            'ORDER BY' => 'create_at DESC',
        );

        // 取得。該当データの結果セットに対して疑似列を追加する。
        $list = $this->selectPage($condition, $numOnPage, $page);
        $this->addExColumn($list['resultset'], true);

        return $list;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザの履歴をクリアする。
     *
     * @param int       ユーザID
     */
    public function clearHistory($userId) {

        $this->createDao()->delete( array('user_id'=>$userId) );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザのコメントを作成する。
     *
     * @param int       コメントしたユーザID
     * @param string    内容
     * @param array     他の履歴に対するレスである場合に、その履歴ID。配列で複数指定することもできる。
     */
    public function createComment($commenterId, $body, $replyTo = array()) {

        // 引数正規化。
        $replyTo = (array)$replyTo;

        // 宛先の決定。レス先が一つだけ指定されている場合は、そのユーザ。
        // レス先がない、あるいは複数の場合は null。
        $to = null;
        if(count($replyTo) == 1) {
            $toTarget = $this->getRecord($replyTo[0]);
            if($toTarget)
                $to = $toTarget['user_id'];
        }

        // プラットフォームにメッセージを提出。
        $textId = Service::create('Text_Log')->postText('TWT', $body, $commenterId, $to);

        // レコード作成。
        $record = array(
            'type' => self::TYPE_COMMENT,
            'user_id' => $commenterId,
            'ref1_value' => $textId,
        );
        $newId = $this->insertRecord($record, true);

        // リプライでないならここで終了。
        if(!$replyTo)
            return;

        $replySvc = new History_ReplyService();

        // 引数正規化。
        if(!is_array($replyTo))  $replyTo = array($replyTo);

        // 返信のひも付けを行う。
        foreach($replyTo as $toId)
            $replySvc->insertRecord(array('history_id'=>$newId, 'reply_to'=>$toId));

        // 返信先の履歴をすべて取得。
        $targets = $this->getRecordsIn($replyTo, false);

        // 返信先の履歴を一つずつ見ていき...
        foreach($targets as $target) {

            // レス対象のユーザにメッセージ送信
            $body = sprintf('No%dの履歴にﾚｽが付きました', $target['history_id']);
            PlatformApi::sendMessage($target['user_id'], $body, '履歴にﾚｽが付きました');

            // 履歴作成
            $this->insertRecord(array(
                'type' => self::TYPE_REPLIED,
                'user_id' => $target['user_id'],
                'ref1_value' => $target['history_id'],
                'ref2_value' => $commenterId,
            ));
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された履歴に、指定されたユーザからの称賛を付ける。
     *
     * @param int       履歴ID
     * @param int       称賛したユーザのID
     */
    public function admireHistory($historyId, $admirerId) {

        // 称賛レコードを挿入。
        Service::create('History_Admiration')->insertRecord(array(
            'history_id' => $historyId,
            'admirer_id' => $admirerId,
        ));

        // 履歴を取得。
        $history = $this->getRecord($historyId);
        if(!$history)
            return;

        // つぶやいたユーザにメッセージ送信。
        $body = sprintf('No%dの履歴に%sをもらいました', $historyId, ADMIRATION_NAME);
        PlatformApi::sendMessage($history['user_id'], $body, '履歴に'.ADMIRATION_NAME.'もらいました');

        // 履歴作成。
        $this->insertRecord(array(
            'type' => History_LogService::TYPE_ADMIRED,
            'user_id' => $history['user_id'],
            'ref1_value' => $historyId,
            'ref2_value' => $admirerId,
        ));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'history_id';

    // 30日以上経過したレコードは削除するようにする。
    protected $deleteOlds = 30;


    //-----------------------------------------------------------------------------------------------------
    /**
     * insertRecordをオーバーライド。
     * システムユーザなら処理しないようにする。
     */
    public function insertRecord($values, $returnAutoNumber = false) {

        if($values['user_id'] < 0) {
            if($returnAutoNumber)
                throw new MojaviException('システムユーザなので、履歴レコードを挿入しなかった');
            else
                return;
        }

        return parent::insertRecord($values, $returnAutoNumber);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * deleteRecord()をオーバーライド。deleteRecordsIn() に転送する。
     */
    public function deleteRecord(/* 可変引数 */) {

        $args = func_get_args();
        $this->deleteRecordsIn( $args );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * exColumnをオーバーライド。以下の拡張列を追加する。
     *     reply_count      レス数
     *     goodness         称賛数
     *     reply_to         レス先の履歴IDの配列
     * また、typeに応じて以下の拡張列を追加する。
     *     type=1, 2
     *          battle      ref2_valueで示されているbattle_logレコード(getRecordBiasで取得したもの)
     *                      ただし、削除されている場合はnull。
     *     type=3
     *          character   階級変更したキャラの character_info レコード
     *          grade       階級変更後の grade_master レコード
     *          up_flg      昇格したならtrue、降格したならfalse
     *     type=4
     *          character   レベルアップしたキャラの character_info レコード
     *     type=5
     *          character       効果がかかっていたキャラの character_info レコード
     *          effect_name     切れた効果の名前
     *     type=6
     *          invited     招待に応じてくれたユーザの user_info レコード。
     *                      null は自分が招待されて、特典を得たことを表す。
     *     type=7
     *          giver       プレゼントをくれたユーザの user_info レコード
     *          item        プレゼントされた item_master レコード
     *     type=8
     *          quest       成功／失敗したquest_masterレコード。
     *     type=10, 11
     *          item        壊れた／レベルアップしたitem_masterレコード。
     *     type=12
     *          item        賞品の item_master レコード
     *     type=13
     *          monster     キャプチャしたモンスターの Monster_MasterService レコード
     *          rare_name   レア度を表す文字列
     *     type=14,15
     *          companion   称賛／レスを付けたユーザ
     *     type=16
     *          comment     コメント内容
     *     type=17
     *          summary     クエストのサマリ。スフィアレコードが消えている場合は null。
     *     type=18
     *          summary     クエストのサマリ。スフィアレコードが消えている場合は null。
     *          challenger  挑戦側のuser_infoレコードの配列。スフィアレコードが消えている場合は null。
     *          defender    防衛側のuser_infoレコードの配列。スフィアレコードが消えている場合は null。
     */
    protected function exColumn(&$record) {

        $replySvc = new History_ReplyService();

        // reply_count を追加。
        $record['reply_count'] = $replySvc->getReplyCount($record['history_id']);

        // goodness を追加。
        $record['goodness'] = Service::create('History_Admiration')->getGoodnessCount($record['history_id']);

        // goodness を追加。
        $record['reply_to'] = $replySvc->getReplyTo($record['history_id']);

        switch($record['type']) {

            case self::TYPE_BATTLE_CHALLENGE:
            case self::TYPE_BATTLE_DEFENCE:

                $battleSvc = new Battle_LogService();
                $record['battle'] = $battleSvc->getRecord($record['ref2_value']);

                if($record['battle']) {
                    $biasColumn = ($record['type'] == self::TYPE_BATTLE_CHALLENGE) ? 'challenger_id' : 'defender_id';
                    $battleSvc->addBiasColumn($record['battle'], $record['battle'][$biasColumn]);
                }

                break;

            case self::TYPE_CHANGE_GRADE:

                $svc = new Character_InfoService();
                $record['character'] = $svc->getRecord($record['ref1_value']);

                $record['up_flg'] = ($record['ref2_value'] > 0);

                $svc = new Grade_MasterService();
                $record['grade'] = $svc->getRecord( (int)abs($record['ref2_value']) );

                break;

            case self::TYPE_LEVEL_UP:

                $svc = new Character_InfoService();
                $record['character'] = $svc->getRecord($record['ref1_value']);
                break;

            case self::TYPE_EFFECT_TIMEUP:

                $svc = new Character_InfoService();
                $record['character'] = $svc->getRecord($record['ref1_value']);
                $effectSvc = new Character_EffectService();
                $record['effect_name'] = $effectSvc->getEffectName($record['ref2_value']);
                break;

            case self::TYPE_INVITE_SUCCESS:

                $userSvc = new User_InfoService();
                $record['invited'] = $record['ref1_value'] ? $userSvc->getRecord($record['ref1_value']) : null;
                break;

            case self::TYPE_PRESENTED:

                $userSvc = new User_InfoService();
                $record['giver'] = $userSvc->getRecord($record['ref1_value']);
                $itemSvc = new Item_MasterService();
                $record['item'] = $itemSvc->getRecord($record['ref2_value']);
                break;

            case self::TYPE_QUEST_FIN:

                $svc = new Quest_MasterService();
                $record['quest'] = $svc->getRecord($record['ref1_value']);
                break;

            case self::TYPE_ITEM_BREAK:
            case self::TYPE_ITEM_LVUP:

                $svc = new Item_MasterService();
                $record['item'] = $svc->getRecord($record['ref1_value']);
                break;

            case self::TYPE_WEEKLY_HIGHER:

                $svc = new Item_MasterService();
                $record['item'] = $svc->getRecord($record['ref2_value']);
                break;

            case self::TYPE_CAPTURE:

                $record['monster'] = Service::create('Monster_Master')->getRecord($record['ref1_value']);
                $record['rare_name'] = Monster_MasterService::$RARE_LEVELS[ $record['monster']['rare_level'] ];
                break;

            case self::TYPE_ADMIRED:
            case self::TYPE_REPLIED:

                $record['companion'] = Service::create('User_Info')->getRecord[ $record['ref2_value'] ];
                break;

            case self::TYPE_COMMENT:

                $record['comment'] = Text_LogService::get($record['ref1_value']);
                break;

            case self::TYPE_QUEST_FIN2:

                $sphereRecord = Service::create('Sphere_Info')->getRecord($record['ref1_value']);
                if($sphereRecord) {
                    $sphere = SphereCommon::load($sphereRecord);
                    $record['summary'] = $sphere->getSummary();
                }

                break;

            case self::TYPE_TEAM_BATTLE:

                $userSvc = new User_InfoService();

                $sphereRecord = Service::create('Sphere_Info')->getRecord($record['ref1_value']);
                if($sphereRecord) {

                    $sphere = SphereCommon::load($sphereRecord);
                    $record['summary'] = $sphere->getSummary();

                    $record['challenger'] = $userSvc->getRecordsIn($record['summary']['x_allies']);
                    ResultsetUtil::order($record['challenger'], 'user_id', $record['summary']['x_allies']);

                    $record['defender'] = $userSvc->getRecordsIn($record['summary']['x_rivals']);
                    ResultsetUtil::order($record['defender'], 'user_id', $record['summary']['x_rivals']);
                }

                break;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * deleteOldRecordsをオーバーライド。
     * 紐づいているText_Logレコードも削除するようにする。
     */
    protected function deleteOldRecords() {

        // 削除対象になるレコードを取得。
        $sql = '
            SELECT history_id
            FROM history_log
            WHERE create_at < NOW() - INTERVAL ? DAY
            LIMIT 40
        ';
        $deleteIds = $this->createDao()->getCol($sql, $this->deleteOlds);

        $this->deleteRecordsIn($deleteIds);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された複数のレコードを削除する。
     *
     * @param array     history_idの配列。
     */
    public function deleteRecordsIn($deleteIds) {

        $dao = $this->createDao();

        // 削除対象がないならリターン。
        if(count($deleteIds) == 0)
            return;

        // 紐づいている称賛ログを削除。
        Service::create('History_Admiration')->deleteAdmiration($deleteIds);

        // 紐づいている返信データを削除。
        Service::create('History_Reply')->deleteReply($deleteIds);

        // 削除対象になるレコードのうち、コメントを表しているものの、テキストIDを得る。
        $where = array(
            'history_id' => $deleteIds,
            'type' => self::TYPE_COMMENT,
        );
        $sql = "SELECT ref1_value FROM history_log\n" . DataAccessObject::buildWhere($where, $params);
        $textIds = $dao->getCol($sql, $params);

        // 紐づいているText_Logレコードを削除。
        Service::create('Text_Log')->deleteRecordsIn($textIds);

        // 削除対象のレコードを削除。
        $this->createDao()->delete(array('history_id' => $deleteIds));
    }
}
