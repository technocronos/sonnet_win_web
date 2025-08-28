<?php

class User_InfoService extends Service {

    // 初期のパラメータ値
    const INITIAL_GOLD = 0;       // ゲーム内通貨

    // userinfo.tutorial_stepの各値
    const TUTORIAL_MORNING = 0;
    const TUTORIAL_MAINMENU = 10;
    const TUTORIAL_FIELD = 20;
    const TUTORIAL_BATTLE = 30;
    const TUTORIAL_AFTERBATTLE = 40;    // 廃止
    const TUTORIAL_STATUS = 50;         // 廃止
    const TUTORIAL_PRESHOP = 60;
    const TUTORIAL_SHOPPING = 70;
    const TUTORIAL_GACHA = 75;
    const TUTORIAL_RIVAL = 80;          // 廃止
    const TUTORIAL_EQUIP = 85;
    const TUTORIAL_LAST = 90;
    const TUTORIAL_END = 100;
    const TUTORIAL_MOVE = 110;
    const TUTORIAL_GLOBALMOVE = 120;
    const TUTORIAL_FINISH = 130;


    //-----------------------------------------------------------------------------------------------------
    // 集計関連メソッド。

    /**
     * 引数で指定された日付範囲でユーザ登録数を集計する。
     *
     * @param string    集計範囲開始日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @param string    集計範囲終了日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @return array    登録日をインデックス、登録人数を値とする配列。
     */
    public function sumupRegistration($from, $to) {

        // まずは登録日別に集計。
        $sql = "
            SELECT DATE_FORMAT(create_at, '%Y-%m-%d') date
                 , COUNT(user_agent like '%iPhone%' OR user_agent like '%iPad%' OR NULL) as ios_count
                 , COUNT(user_agent like '%Android%' OR NULL) as android_count
                 , COUNT(*) as count
            FROM user_info
            WHERE create_at >= ?
              AND create_at < ?
            GROUP BY date
        ";

        $resultset = $this->createDao(true)->getAll($sql, array($from, $to));
        return $resultset;
    }

    /**
     * 引数で指定された日付範囲で登録日別の早期離脱人数を集計する。
     *
     * @param string    集計範囲開始日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @param string    集計範囲終了日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @return array    最終アクセス日をインデックス、最終アクセス人数を値とする配列。
     */
    public function sumupEarlySecession($from, $to) {

        // まずは登録日別に集計。
        $sql = "
            SELECT DATE_FORMAT(create_at, '%Y-%m-%d') date
                 , COUNT(*) as count
            FROM user_info
            WHERE create_at >= ?
              AND create_at < ?
              AND create_at < last_access_date - INTERVAL 3 HOUR
            GROUP BY date
        ";

        $resultset = $this->createDao(true)->getAll($sql, array($from, $to));
        return ResultsetUtil::colValues($resultset, 'count', 'date');
    }

    /**
     * 引数で指定された日付範囲で日付別の最終アクセス人数を集計する。
     *
     * @param string    集計範囲開始日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @param string    集計範囲終了日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @return array    最終アクセス日をインデックス、最終アクセス人数を値とする配列。
     */
    public function sumupLastAccess($from, $to) {

        // まずは登録日別に集計。
        $sql = "
            SELECT DATE_FORMAT(last_access_date, '%Y-%m-%d') date
                 , COUNT(*) as count
            FROM user_info
            WHERE last_access_date >= ?
              AND last_access_date < ?
            GROUP BY date
        ";

        $resultset = $this->createDao(true)->getAll($sql, array($from, $to));
        return ResultsetUtil::colValues($resultset, 'count', 'date');
    }

    /**
     * 引数で指定された日付範囲で日付別のアンインストール人数を集計する。
     *
     * @param string    集計範囲開始日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @param string    集計範囲終了日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @return array    アンイスントール日をインデックス、アンイスントール人数を値とする配列。
     */
    public function sumupRetire($from, $to) {

        // まずは登録日別に集計。
        $sql = "
            SELECT DATE_FORMAT(retire_date, '%Y-%m-%d') date
                 , COUNT(*) as count
            FROM user_info
            WHERE retire_date >= ?
              AND retire_date < ?
            GROUP BY date
        ";

        $resultset = $this->createDao(true)->getAll($sql, array($from, $to));
        return ResultsetUtil::colValues($resultset, 'count', 'date');
    }

    /**
     * ユーザー数の合計、および残存率を集計する。
     *
     * @param int       まだ生きているユーザと判断する、最終アクセスからの日数
     * @return array    次のキーを持つ配列
     *                      total       全登録人数
     *                      living      残存している数
     *                      living_rate 残存率
     */
    public function sumupTotals($livingDays) {

        $dao = $this->createDao(true);

        // まずはユーザ数をカウント。
        $sql = "
            SELECT COUNT(*)
            FROM user_info
            WHERE create_at >= '" . RELEASE_DATE . "'
        ";
        $total = $dao->getOne($sql);

        $sql = "
            SELECT COUNT(user_agent like '%iPhone%' OR user_agent like '%iPad%' OR NULL) 
            FROM user_info
            WHERE create_at >= '" . RELEASE_DATE . "'
        ";
        $ios_total = $dao->getOne($sql);

        $sql = "
            SELECT COUNT(user_agent like '%Android%' OR NULL)
            FROM user_info
            WHERE create_at >= '" . RELEASE_DATE . "'
        ";
        $android_total = $dao->getOne($sql);

        // 最終アクセスが指定日数以内のユーザをカウント。
        $sql = '
            SELECT COUNT(*)
            FROM user_info
            WHERE last_access_date > NOW() - INTERVAL ? DAY
        ';
        $living = $dao->getOne($sql, $livingDays);

        // リターン。
        return array(
            'total' => $total,
            'ios_total' => $ios_total,
            'android_total' => $android_total,
            'living' => $living,
            'living_rate' => $total ? $living/$total : 0,
        );
    }

    /**
     * ユーザー数のチュートリアル別の集計を行う。
     *
     * @return array    次の列を持つ結果セット形式の配列
     *                      tutorial_step   日にち
     *                      count           総人数
     *                      secession       総人数のうち、7日以上アクセスがないユーザ
     *                      retire          総人数のうち、アンイントールしているユーザ、
     */
    public function sumupTutorialStep() {

        $sql = "
            SELECT tutorial_step
                 , COUNT(*) AS count
                 , SUM( IF(last_access_date < NOW() - INTERVAL 7 DAY, 1, 0) ) AS secession
                 , COUNT(retire_date) AS retire
            FROM user_info
            WHERE create_at >= '" . RELEASE_DATE . "'
            GROUP BY tutorial_step
            ORDER BY tutorial_step
        ";

        return $this->createDao(true)->getAll($sql);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された条件を満たすユーザのIDを返す。
     *
     * @param array         条件。次のキーを持つ配列。
     *                          id                  ID
     *                          name                ユーザ名。中間一致検索する。
     *                          access_date_from    最終アクセス日時(開始)
     *                          access_date_to      最終アクセス日時(終了)
     *                          create_at_from      登録日時(開始)
     *                          create_at_to        登録日時(終了)
     *                          except_retire       アンインストールユーザを除外するかどうか。
     *                          id_except_upper     除外したいユーザIDの範囲がある場合に、除外上限ID。
     *                                              ここで指定した値以下のIDは無視される。
     *                          return_matched_rows これは条件ではなく、全体の該当件数も取得することを示す。
     * @param reference     最大抽出件数。0は制限しないことを意味する。
     *                      条件の "return_matched_rows" キーにtrueを指定した場合、ここで指定した変数に
     *                      制限しなかった場合の該当件数が格納される。
     * @return array        条件に該当するユーザのID。いない場合はカラの配列。
     */
    public function findUsers($condition, &$limit = 0) {

        $dao = $this->createDao(true);

        // 固定の条件を作成。システムユーザは除外する。
        $where = array();
        $where['user_info.user_id:1'] = array('sql'=>'> 0');

        // 指定された条件を組み込む。
        if(strlen($condition['id']))  $where['user_info.user_id:2'] = $condition['id'];
        if(strlen($condition['character_id']))  $where['character_info.character_id:2'] = $condition['character_id'];
        if(strlen($condition['name']))  $where['user_info.name'] = array('sql'=>'LIKE ?', 'value'=>'%'.DataAccessObject::escapeLikeLiteral($condition['name']).'%');
        if(strlen($condition['body']))  $where['t.body'] = array('sql'=>'LIKE ?', 'value'=>'%'.DataAccessObject::escapeLikeLiteral($condition['body']).'%');
        if(strlen($condition['access_date_from']))  $where['user_info.last_access_date:1'] = array('sql'=>'>= ?', 'value'=>$condition['access_date_from']);
        if(strlen($condition['access_date_to']))    $where['user_info.last_access_date:2'] = array('sql'=>'< ?', 'value'=>$condition['access_date_to']);
        if(strlen($condition['create_at_from']))  $where['user_info.create_at:1'] = array('sql'=>'>= ?', 'value'=>$condition['create_at_from']);
        if(strlen($condition['create_at_to']))    $where['user_info.create_at:2'] = array('sql'=>'< ?', 'value'=>$condition['create_at_to']);
        if(strlen($condition['af_status']))  $where['appsflyer.af_status'] = $condition['af_status'];
        if(strlen($condition['campaign']))  $where['appsflyer.campaign'] = array('sql'=>'LIKE ?', 'value'=>'%'.DataAccessObject::escapeLikeLiteral($condition['campaign']).'%');
        if(strlen($condition['media_source']))  $where['appsflyer.media_source'] = array('sql'=>'LIKE ?', 'value'=>'%'.DataAccessObject::escapeLikeLiteral($condition['media_source']).'%');

        if(strlen($condition['grade_id'])){  
            if($condition['grade_id'] > 0){  
                $where['character_info.grade_id'] = $condition['grade_id'];
            }
        }

        if(strlen($condition['virtual_coin']))    $where['user_info.virtual_coin'] = array('sql'=>'> ?', 'value'=>$condition['virtual_coin']);

        if($condition['except_retire']) $where['user_info.retire_date'] = null;
        if(strlen($condition['id_except_upper'])) {
            $where['user_info.user_id:3'] = array('sql'=>'> ?', 'value'=>$condition['id_except_upper']);
            $where['ORDER BY'] = 'user_info.user_id';
        }

        $where['GROUP BY'] = 'user_info.user_id,t.body';

        if(strlen($condition['sales']))  $where['HAVING'] = 'sales > ' . $condition['sales'];

        if($limit)  $where['LIMIT'] = $limit;

        $option = $condition['return_matched_rows'] ? 'SQL_CALC_FOUND_ROWS' : '';

        // SQL作成。
        $sql = "
            SELECT {$option} user_info.user_id,t.body,sum(p.unit_price) as sales
            FROM user_info LEFT OUTER JOIN character_info
                  ON user_info.user_id = character_info.user_id LEFT OUTER JOIN text_log as t 
            		ON user_info.user_id = t.writer_id 
            				AND t.create_at = (select max(text_log.create_at) from text_log where text_log.writer_id = user_info.user_id)
            		LEFT OUTER JOIN payment_log as p ON user_info.user_id = p.user_id and p.status = " .Payment_LogService::STATUS_COMPLETE. "
                LEFT OUTER JOIN appsflyer
                    ON user_info.platform_uid = appsflyer.platform_uid 
        " . DataAccessObject::buildWhere($where, $params);

        // 検索。
        $ret = $dao->getCol($sql, $params);

        // LIMITなしの場合の該当件数を取得。
        if($condition['return_matched_rows'])
            $limit = $dao->getOne('SELECT FOUND_ROWS()');

        // リターン。
        return $ret;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたプラットフォームユーザIDでユーザレコードを取得する。
     *
     * @param int       プラットフォームユーザID
     * @return array    ユーザレコード
     */
    public function getRecordByPuid($platformUid) {

        return $this->selectRecord(array('platform_uid'=>$platformUid));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザの platform_uid 列の値を返す。
     *
     * @param int       ユーザID
     * @return string   platform_uid 列の値。レコードがなかった場合は false。
     */
    public function getPlatformUid($userId) {

        $sql = '
            SELECT platform_uid
            FROM user_info
            WHERE user_id = ?
        ';

        return $this->createDao(true)->getOne($sql, $userId);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザに、現在時までの影響を与えて、与えた後のユーザレコードを返す。
     * ついでに最終アクセス日時の更新や退会フラグの解除も出来る。
     *
     * @param int       プラットフォームユーザID。内部ユーザIDではないので注意。
     * @param bool      最終アクセス日時を更新と退会フラグの解除を行うかどうか。
     * @return array    影響を与えた後のユーザレコード。レコードがない場合は null。
     *                  前回アクセスからの経過日数を表す "absence_days" という擬似列が追加されている。
     */
    public function affectByTime($platformUid, $updateLastAccess = true) {

        // レコード取得。なかったらnullリターン。
        $record = $this->getRecordByPuid($platformUid);
        if(!$record)
            return null;

        // 現在時と最終計算日時を取得。
        $now = time();
        $lastAffected = strtotime($record['last_affected']);

        // "absence_days" を計算する。
        $record['absence_days'] = (int)( ($now - strtotime($record['last_access_date'])) / (24*60*60) );

        // 最終計算からほとんど時間が経っていないならスキップする。
        if($now < $lastAffected + 1 * 2)
            return $record;

        // 更新準備。
        $update = array();

        // 時間によるポイント回復分を計算。
        $actPtRecv = ($now - $lastAffected) * ACTION_PT_RECOVERY;
        $matPtRecv = ($now - $lastAffected) * MATCH_PT_RECOVERY;

        // 最大値も加味して、回復後の値を計算。
        $update['action_pt'] = min(ACTION_PT_MAX, $record['action_pt'] + $actPtRecv);
        $update['match_pt'] =  min(MATCH_PT_MAX,  $record['match_pt'] +  $matPtRecv);

        // その他、更新する列を取得。
        $update['last_affected'] = date('Y/m/d H:i:s', $now);
        if($updateLastAccess) {
            $update['last_access_date'] = $update['last_affected'];
            $update['retire_date'] = null;
        }

        // 更新。
        $this->updateRecord($record['user_id'], $update);

        // 取得したレコードに更新分を反映。
        // ユーザレコードはよく取得されるので、キャッシュにも反映しておく。
        $record = array_merge($record, $update);
        $this->setPkCache($record['user_id'], $record);

        // リターン。
        return $record;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザのチュートリアルステップが、指定されたステップと一致する場合に、
     * ステップを1つ進める。
     *
     * @param int   ユーザID
     * @param int   念のため、現在のチュートリアルステップ。該当するなら+1する。
     */
    public function tutorialStepUp($userId, $step) {

        static $NEXT_STEPS = array();

        if(!(FPhoneUtil::getCarrier() == 'pc')){
            // 各ステップにおける、次のステップのマップ。(ガラケー)
            $NEXT_STEPS = array(
                self::TUTORIAL_MORNING => self::TUTORIAL_MAINMENU,
                self::TUTORIAL_MAINMENU => self::TUTORIAL_FIELD,
                self::TUTORIAL_FIELD => self::TUTORIAL_BATTLE,
                self::TUTORIAL_BATTLE => self::TUTORIAL_PRESHOP,
                self::TUTORIAL_AFTERBATTLE => self::TUTORIAL_STATUS,    // 廃止
                self::TUTORIAL_STATUS => self::TUTORIAL_PRESHOP,        // 廃止
                self::TUTORIAL_PRESHOP => self::TUTORIAL_SHOPPING,
                self::TUTORIAL_SHOPPING => self::TUTORIAL_GACHA,
                self::TUTORIAL_GACHA => self::TUTORIAL_LAST,
                self::TUTORIAL_RIVAL => self::TUTORIAL_LAST,            // 廃止
                self::TUTORIAL_LAST => self::TUTORIAL_END,
                self::TUTORIAL_END => self::TUTORIAL_MOVE,
                self::TUTORIAL_MOVE => self::TUTORIAL_GLOBALMOVE,
                self::TUTORIAL_GLOBALMOVE => self::TUTORIAL_FINISH,
            );

        }else{
            // 各ステップにおける、次のステップのマップ。
            $NEXT_STEPS = array(
                self::TUTORIAL_MORNING => self::TUTORIAL_MAINMENU,
                self::TUTORIAL_MAINMENU => self::TUTORIAL_FIELD,
                self::TUTORIAL_FIELD => self::TUTORIAL_BATTLE,
                self::TUTORIAL_BATTLE => self::TUTORIAL_PRESHOP,
                self::TUTORIAL_AFTERBATTLE => self::TUTORIAL_STATUS,    // 廃止
                self::TUTORIAL_STATUS => self::TUTORIAL_PRESHOP,        // 廃止
                self::TUTORIAL_PRESHOP => self::TUTORIAL_SHOPPING,
                self::TUTORIAL_SHOPPING => self::TUTORIAL_GACHA,
                self::TUTORIAL_GACHA => self::TUTORIAL_EQUIP,
                self::TUTORIAL_RIVAL => self::TUTORIAL_LAST,            // 廃止
                self::TUTORIAL_EQUIP => self::TUTORIAL_LAST,
                self::TUTORIAL_LAST => self::TUTORIAL_END,
                self::TUTORIAL_END => self::TUTORIAL_MOVE,
                self::TUTORIAL_MOVE => self::TUTORIAL_GLOBALMOVE,
                self::TUTORIAL_GLOBALMOVE => self::TUTORIAL_FINISH,
            );
        }

        // 指定されたステップではないなら何もしない。
        $user = $this->needRecord($userId);
        if($user['tutorial_step'] != $step)
            return;

        // 次ステップがないがない場合はエラー。念のためのチェック。
        if(!isset($NEXT_STEPS[$step]))
            throw new MojaviException('次のチュートリアルステップがない');

        // 次ステップに更新。
        $update = array();
        $update['tutorial_step'] = $NEXT_STEPS[$step];
        $this->updateRecord($userId, $update);

        // チュートリアルが終わったら、友だち招待応諾の処理を行う。
        if($update['tutorial_step'] == self::TUTORIAL_END)
            Service::create('Invitation_Log')->congraturateInvitation($userId);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザの行動ptを、指定された値にする。
     *
     * @param int   ユーザID
     * @param int   行動pt。省略した場合は最大値にする。
     */
    public function setActionPt($userId, $actionPt = -1) {

        if($actionPt == -1)
            $actionPt = ACTION_PT_MAX;

        $this->updateRecord($userId, array(
            'action_pt' => $actionPt
        ));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * BITCOINプレゼント処理を行う。
     */
    public function setVirtualCoin($user_id, $flag_group, $amount, $flag_id) {

        $flg = Service::create('Vcoin_Flag_Log')->getValue($flag_group, $user_id, $flag_id);

        //すでに与えている場合はリターン
        if($flg)
            return false;

        // 仮想通貨プレゼント
        $this->plusValue($user_id, array(
            'virtual_coin' => $amount,
        ));

        // BITCOINを受け取ったフラグをONに。
        Service::create('Vcoin_Flag_Log')->setValue(
            $flag_group, $user_id, $flag_id, $amount
        );

        return true;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザの地点を移動する。
     *
     * @param int   ユーザID
     * @param int   地域ID
     */
    public function movePlace($userId, $placeId) {

        $this->updateRecord($userId, array(
            'place_id' => $placeId
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザの退会日時をセットする。
     *
     * @param mied  ユーザID。配列での複数指定も可能。
     */
    public function setRetire($userIds) {

        // 指定が何もないなら処理しない。
        if(!$userIds)
            return;

        $sql = '
            UPDATE user_info
            SET retire_date = NOW()
        ' . DataAccessObject::buildWhere(array('user_id'=>$userIds), $sqlParams);

        $this->createDao()->execute($sql, $sqlParams);
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'user_id';


    //-----------------------------------------------------------------------------------------------------
    /**
     * insertRecord をオーバーライド。
     * nameをサービスコンテナから取得するようにする他、さまざまな初期値を補う。
     */
    public function insertRecord($values, $returnAutoNumber = false) {

        // "name" 列が指定されていない場合はプラットフォームから取得。
        if(strlen($values['name']) == 0) {
            $nickname = PlatformApi::queryNickname($values['platform_uid']);
            $values['name'] = (strlen($nickname) > 0) ? $nickname : '(取得失敗)';
        }

        // 初期値を補う。
        $values += array(
            'user_id' => PlatformApi::getInternalUid($values['platform_uid']),
            'action_pt' => ACTION_PT_MAX,
            'match_pt' => MATCH_PT_MAX,
            'gold' => self::INITIAL_GOLD,
            'place_id' => Place_MasterService::INITIAL_PLACE,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'name_sync_date' => array('sql'=>'NOW()'),
            'last_affected' => array('sql'=>'NOW()'),
            'last_access_date' => array('sql'=>'NOW()'),
        );

        // INSERT。
        return parent::insertRecord($values, $returnAutoNumber);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * plusValue をオーバーライド。
     */
    public function plusValue($primaryKey, $update) {

        // 行動ptが最大値を超えないようにする。
        if( array_key_exists('action_pt', $update) ) {
            $update['action_pt'] = array(
                'sql' => 'LEAST(action_pt + ?, ?)',
                'value' => array($update['action_pt'], ACTION_PT_MAX),
            );
        }

        // 対戦ptが最大値を超えないようにする。
        if( array_key_exists('match_pt', $update) ) {
            $update['match_pt'] = array(
                'sql' => 'LEAST(match_pt + ?, ?)',
                'value' => array($update['match_pt'], MATCH_PT_MAX),
            );
        }

        // お金が 0 を下回らないようにする。
        if( array_key_exists('gold', $update) ) {
            $update['gold'] = array(
                'sql' => 'GREATEST(0, gold + ?)',
                'value' => $update['gold'],
            );
        }

        parent::plusValue($primaryKey, $update);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * processRecordをオーバーライド。
     */
    protected function processRecord(&$record) {

        // プラットフォームとニックネームの同期を定期的に行うようにする。
        // name_sync_dateが規定日数以上経過しているなら同期。
        // …でも、システムユーザやアンインストールユーザは除外。
        if($record['user_id'] > 0  &&  !$record['retire_date']  &&  strtotime($record['name_sync_date']) < time() - 4*24*60*60) {

            // ニックネームが取得できようが出来まいが、name_sync_dateは更新する。
            // (アンインストールで取得できなくなっているユーザもいるため)
            $update = array();
            $update['name_sync_date'] = array('sql'=>'NOW()');

            // プラットフォームからニックネーム取得。取得できたならnameも更新する。
            $nickname = PlatformApi::queryNickname($record['platform_uid']);
            if(strlen($nickname) > 0)
                $update['name'] = $nickname;

            // 更新。
            $this->updateRecord($record['user_id'], $update);

            // 取得しようとしているレコードにも反映させる。
            $update['name_sync_date'] = date('Y-m-d H:i:s');
            $record = array_merge($record, $update);
        }

        // "short_name" 疑似列を追加。
        $record['short_name'] = mb_strimwidth($record['name'], 0, USERNAME_DISPLAY_WIDTH, '', 'UTF-8');
    }
}
