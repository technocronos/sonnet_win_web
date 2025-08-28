<?php

class Battle_LogService extends Service {

    // status, true_status 列の値。
    const CREATED = 0;
    const IN_GAME = 1;
    const NO_GAME = 2;
    const IN_CONTINUE = 3;
    const CHA_WIN = 11;
    const DEF_WIN = 12;
    const DRAW = 13;
    const TIMEUP = 14;

    // status, true_status 列の値で、決着済みの値の下限値。
    const SETTLE_BORDER = 11;

    // 決着が付くまでに遅すぎると判断する閾秒数
    const TOO_LONG_MATCH_SECS = 900;


    //-----------------------------------------------------------------------------------------------------
    /**
     * getRecordして取得したレコードに、addBiasColumn()を適用して返すショートカットメソッド。
     *
     * @param int       バトルID。
     * @param int       擬似列を加えるときの基準になるキャラクターID
     * @return array    擬似列も加えた結果レコード
     */
    public function getRecordBias($primaryKey, $biasCharaId = null) {

        // 指定されたレコードを取得。取得できなかったら即リターン。
        $result = $this->getRecord($primaryKey);
        if(!$result)
            return $result;

        // 追加列を加える。
        $this->addBiasColumn($result, $biasCharaId);

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたレコードに以下の疑似列を加える。
     *     is_challenger            第二引数に指定したキャラクターが挑戦側かどうか
     *     bias_user_id             第二引数に指定したキャラクターの持ち主のユーザID
     *     bias_user_name           第二引数に指定したキャラクターの持ち主のユーザ名
     *     bias_character_id        第二引数に指定したキャラクターID
     *     bias_character_name      第二引数に指定したキャラクターの名前
     *     rival_user_id            対戦相手のキャラクターの持ち主のユーザID
     *     rival_user_name          対戦相手のキャラクターの持ち主のユーザ名
     *     rival_character_id       対戦相手のキャラクターID
     *     rival_character_name     対戦相手のキャラクターの名前
     *     bias_status              第二引数に指定したユーザにとって、試合結果はどうだったか
     *                              win, lose, draw, timeup, newgame, ingame, nogame のいずれか。
     *     bias_ready               "ready_detail" 要素の、第二引数に指定したユーザ側の要素への参照。
     *     rival_ready              "ready_detail" 要素の、対戦相手の要素への参照。
     *     bias_result              "result_detail" 要素の、第二引数に指定したユーザ側の要素への参照。
     *     rival_result             "result_detail" 要素の、対戦相手の要素への参照。
     *     comment                  comment_id列で示されているコメントの内容。
     *
     * @param array     battle_logの単一レコード、あるいは結果セット。
     *                  処理結果もここに返される。
     * @param int       擬似列を加えるときの基準になるキャラクターID。省略時は挑戦側キャラクターが使用される。
     * @param bool      第一引数に指定したものが結果セットである場合は true を指定する。
     */
    public function addBiasColumn(&$target, $biasCharaId = null, $isResultset = false) {

        // レコードでなく結果セットで指定されている場合は再帰して対処。
        if($isResultset) {
            foreach($target as &$record)
                $this->addBiasColumn($record, $biasCharaId, false);
            return;
        }

        // レコードでない場合は処理しない。
        if(!is_array($target))
            return;

        // 基準ユーザが挑戦側なのか防衛側なのかを取得する。
        // 引数省略時は挑戦側。
        $isChallenger = is_null($biasCharaId) ? true : ($target['challenger_id'] == $biasCharaId);

        // 変数 $bias と $rival に挑戦側／防衛側の識別子を代入。
        $bias =  $isChallenger ? 'challenger' : 'defender';
        $rival = $isChallenger ? 'defender' : 'challenger';

        // とりあえずすぐ取得できる擬似列を作成。
        $target['is_challenger'] = $isChallenger;
        $target['bias_character_id'] = $target["{$bias}_id"];
        $target['rival_character_id'] = $target["{$rival}_id"];

        // detail系の列がない場合は補っておく。
        if(!isset($target['ready_detail']['challenger']))  $target['ready_detail']['challenger'] = null;
        if(!isset($target['ready_detail']['defender']))  $target['ready_detail']['defender'] = null;
        if(!isset($target['result_detail']['challenger']))  $target['result_detail']['challenger'] = null;
        if(!isset($target['result_detail']['defender']))  $target['result_detail']['defender'] = null;

        // detail系の疑似列を作成。
        $target['bias_ready'] = &$target['ready_detail'][$bias];
        $target['rival_ready'] = &$target['ready_detail'][$rival];
        $target['bias_result'] = &$target['result_detail'][$bias];
        $target['rival_result'] = &$target['result_detail'][$rival];

        // キャラクター名とユーザIDを取得。
        $charaSvc = new Character_InfoService();

        $chara = $charaSvc->needRecord($target['bias_character_id']);
        $target['bias_character_name'] = Text_LogService::get($chara['name_id']);
        $target['bias_user_id'] = $chara['user_id'];

        $chara = $charaSvc->needRecord($target['rival_character_id']);
        $target['rival_character_name'] = Text_LogService::get($chara['name_id']);
        $target['rival_user_id'] = $chara['user_id'];

        // ユーザ名を取得。
        $userSvc = new User_InfoService();
        $user = $userSvc->needRecord($target['bias_user_id']);
        $target['bias_user_name'] = $user['short_name'];
        $user = $userSvc->needRecord($target['rival_user_id']);
        $target['rival_user_name'] = $user['short_name'];

        // bias_statusを割り出す。
        // 以下はstatus列の値とbias_statusの値の、勝敗がついていない場合のマップ。
        $biasStatusTable = array(
            self::CREATED => 'newgame',
            self::IN_GAME => 'ingame',
            self::IN_CONTINUE => 'ingame',
            self::NO_GAME => 'nogame',
            self::DRAW => 'draw',
            self::TIMEUP => 'timeup',
        );

        // 勝敗がついていないならマップから。
        if(isset($biasStatusTable[ $target['true_status'] ])) {
            $target['bias_status'] = $biasStatusTable[ $target['true_status'] ];

        // 勝敗がついている場合は、勝っているのか負けているのか計算する。
        }else {
            $win = ($bias == 'defender') ^ ($target['true_status'] == self::CHA_WIN);
            $target['bias_status'] = $win ? 'win' : 'lose';
        }

        // コメントがある場合は取得する。
        if($target['comment_id'])
            $target['comment'] = Service::create('Text_Log')->getText($target['comment_id']);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された挑戦側ユーザの、指定された防衛側キャラクターに対する今日のバトル回数を返す。
     *
     * @param int       挑戦側ユーザID
     * @param int       防衛側キャラクターID
     * @return int      今日のバトル回数
     */
    public function getTodayBattleCount($challengeUserId, $rivalCharaId) {

        return $this->countRecord(array(
            'challenger_id' => Service::create('Character_Info')->getCharaIds($challengeUserId),
            'defender_id' => $rivalCharaId,
            'create_at' => array('sql'=>'BETWEEN CURRENT_DATE AND CURRENT_DATE + INTERVAL 1 DAY'),
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された挑戦側キャラが今日、ユーザ対戦しているかどうかを返す。
     *
     * @param int       挑戦側キャラクタID
     * @return boot     今日対戦しているならtrue、していないならfalse。
     */
    public function alreadyBattleToday($challengerId) {

        return $this->createDao(true)->exists(array(
            'challenger_id' => $challengerId,
            'tournament_id' => Tournament_MasterService::TOUR_MAIN,
            'create_at' => array('sql'=>'>= ?', 'value'=>date('Y/m/d 00:00:00')),
            'status' => array('sql'=>'>= ?', 'value'=>self::SETTLE_BORDER),
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたキャラクターが現在ユーザバトル中と思われるかどうかを返す。
     *
     * @param int       キャラクターID
     * @return bool     バトル中と思われるならtrue、そうではないならfalse。
     */
    public function inUserBattle($characterId) {

        $dao = $this->createDao(true);

        // 挑戦側で未決着のレコードがあるかどうかを調べる。
        $exists = $dao->exists(array(
            'challenger_id' => $characterId,
            'tournament_id' => Tournament_MasterService::TOUR_MAIN,
            'status' => array('sql'=>'< ?', 'value'=>self::SETTLE_BORDER),
            'create_at' => array('sql'=>'> NOW() - INTERVAL ? SECOND', 'value'=>self::TOO_LONG_MATCH_SECS),
        ));

        // あるなら防衛側は見る必要ない。
        if($exists)
            return true;

        // 防衛側で未決着のレコードがあるかどうかを調べる。
        return $dao->exists(array(
            'defender_id' => $characterId,
            'tournament_id' => Tournament_MasterService::TOUR_MAIN,
            'status' => array('sql'=>'< ?', 'value'=>self::SETTLE_BORDER),
            'create_at' => array('sql'=>'> NOW() - INTERVAL ? SECOND', 'value'=>self::TOO_LONG_MATCH_SECS),
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザorキャラクタの戦歴をページ分けして取得する。
     *
     * @param array     検索条件。以下のキーを含む配列。
     *                      userId          ユーザID。characterIdを指定するなら省略可能
     *                      characterId     キャラクタID。userIdを指定するなら省略可能
     *                      tourId          戦闘種別ID
     *                      side            挑戦側なら "challenge"、防衛側なら "defend"。
     * @param int       1ページあたりの件数。
     * @param int       何ページ目か。0スタート。
     * @return array    Service::selectPage と同様。
     */
    public function getBattleList($condition, $numOnPage, $page) {

        // 配列 $where にWHERE条件を格納していく。
        $where = array();
        $where['battle_log.tournament_id'] = $condition['tourId'];
        $where['battle_log.status'] = array('sql'=>'>= ?', 'value'=>self::SETTLE_BORDER);
        $where['ORDER BY'] = 'battle_log.create_at DESC';

        $sidePrefix = ($condition['side'] == 'challenge') ? 'challenger' : 'defender';

        if(array_key_exists('characterId', $condition))
            $where["battle_log.{$sidePrefix}_id"] = $condition['characterId'];

        if(array_key_exists('userId', $condition)) {
            $where["battle_log.{$sidePrefix}_id"] =
                Service::create('Character_Info')->getCharaIds($condition['userId']);
        }

        // 取得。
        return $this->selectPage($where, $numOnPage, $page);
    }

    public function getBattleList2($condition, $numOnPage, $page) {

        // 配列 $where にWHERE条件を格納していく。
        $where = array();
        $where['battle_log.tournament_id'] = $condition['tourId'];
        $where['battle_log.status'] = array('sql'=>'>= ?', 'value'=>self::SETTLE_BORDER);
        $where['ORDER BY'] = 'battle_log.create_at DESC';

        $where["OR"] = array("battle_log.challenger_id" => $condition['characterId'] ,"battle_log.defender_id" => $condition['characterId']);


        if(array_key_exists('create_at_from', $condition)) {
            $where['battle_log.create_at'] = array('sql'=>'>= ?', 'value'=>$condition["create_at_from"]);

        }

        if(array_key_exists('create_at_to', $condition)) {
            $where['battle_log.create_at:2'] = array('sql'=>'<= ?', 'value'=>$condition["create_at_to"]);
        }

        // 取得。
        return $this->selectPage($where, $numOnPage, $page);
    }


    public function getBattleListAdmin($condition, $numOnPage, $page) {

        // 配列 $where にWHERE条件を格納していく。
        $where = array();
        $where['battle_log.tournament_id'] = $condition['tourId'];
        $where['ORDER BY'] = 'battle_log.create_at ASC';

        if(array_key_exists('characterId', $condition) && $condition['characterId'] != "") {
            $where["OR"] = array("battle_log.challenger_id" => $condition['characterId'] ,"battle_log.defender_id" => $condition['characterId']);
        }

        if(array_key_exists('rivalCharacterId', $condition) && $condition['rivalCharacterId'] != "") {
            $where["OR:2"] = array("battle_log.challenger_id" => $condition['rivalCharacterId'] ,"battle_log.defender_id" => $condition['rivalCharacterId']);
        }

        if(array_key_exists('create_at_from', $condition) && $condition['create_at_from'] != "") {
            $where['battle_log.create_at'] = array('sql'=>'>= ?', 'value'=>$condition["create_at_from"]);

        }

        if(array_key_exists('create_at_to', $condition) && $condition['create_at_to'] != "") {
            $where['battle_log.create_at:2'] = array('sql'=>'<= ?', 'value'=>$condition["create_at_to"]);
        }

        // 取得。
        return $this->selectPage($where, $numOnPage, $page);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 直近の全く同じ相手と思われるIN_GAMEのレコードを探してコンティニューレコードかどうか調べる。
     *
     * @param string    ポイント種別。以下のいずれか
     *                      grade   階級pt
     * @param string    期間の開始の日付文字列。
     * @param string    期間の終わりの日付文字列。
     * @return array    ユーザIDをキー、ポイントを値とする配列。
     */
    public function SearchContinueRec($params) {

        $dao = $this->createDao();

        $sql = "
            SELECT *
            FROM battle_log
            WHERE tournament_id = ?
              AND challenger_id = ?
              AND defender_id = ?
              AND player_id = ?
              AND side_reverse = ?
              AND relate_id = ?
              AND status IN (? , ?)
              AND create_at > NOW() - INTERVAL ? SECOND
              ORDER BY create_at DESC
        ";

        $records = $dao->getAll($sql, array(
            Tournament_MasterService::TOUR_QUEST, 
            $params['challenger']['character_id'],
            $params['defender']['character_id'],
            $params['player_id'],
            $params['side_reverse'],
            $params['relate_id'],
            self::CREATED,
            self::IN_GAME,
            self::TOO_LONG_MATCH_SECS,
            )
        );

        // 取得したレコードを一つずつ見ていく。
        foreach($records as $record) {
            $detail = json_decode($record['ready_detail'], true);

            //まずIN_GAMEでcontinue_countが進んでいるレコードは継続レコード
            if($detail["continue_count"] > 0 && $record["status"] == self::IN_GAME)
                return $record["battle_id"];
            //in_game_flgが1のレコードはショップに行ったが戻った継続レコード
            else if($detail["in_game_flg"] == 1)
                return $record["battle_id"];
        }

        return NULL;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定の期間のレコードを参照して、指定の種別でユーザごとのポイントを計算する。
     *
     * @param string    ポイント種別。以下のいずれか
     *                      grade   階級pt
     * @param string    期間の開始の日付文字列。
     * @param string    期間の終わりの日付文字列。
     * @return array    ユーザIDをキー、ポイントを値とする配列。
     */
    public function sumupPoint($type, $begin, $end) {

        $charaSvc = new Character_InfoService();
        $dao = $this->createDao();

        // 今のところ階級pt獲得集計しか実装していない。
        if($type != 'grade')
            throw new MojaviException('実装してないポイント種別が指定された');

        // 戻り値初期化。
        $result = array();

        // 期間内のbattle_logレコードをすべて取得して処理するのだが、期間1週間とかあると
        // メモリオーバーしそうな予感がするので、とりあえず、対象レコードのIDのみを取得する。
        $sql = "
            SELECT battle_id
            FROM battle_log
            WHERE result_at >= ?
              AND result_at < ?
              AND challenger_id > 0
              AND defender_id > 0
        ";
        $ids = $dao->getCol($sql, array($begin, $end));

        // 30件ずつ処理する。
        for($i = 0 ;  ; $i++) {

            // 次に処理するbattle_idの配列を取得。
            $next = array_slice($ids, $i * 30, 30);

            // もうなくなったなら終了。
            if(!$next)
                break;

            // 処理対象のbattle_logレコードを取得。
            $sqlParams = array();
            $sql = "
                SELECT challenger_id
                     , defender_id
                     , result_detail
                FROM battle_log
                WHERE battle_id " . DataAccessObject::buildRightSide($next, $sqlParams) . "
            ";
            $records = $dao->getAll($sql, $sqlParams);

            // 取得したレコードを一つずつ見ていく。
            foreach($records as $record) {

                // 結果詳細を取得。
                $detail = json_decode($record['result_detail'], true);

                // 挑戦側⇒防衛側の順で処理する。
                for($s = 0 ; $s < 2 ; $s++) {
                    $side = $s ? 'challenger' : 'defender';

                    // ポイントを取得。名目階級ptなのだが、下位互換のため、キーがない場合は実質階級ptを使う。
                    $pt = isset($detail[$side]['gain']['grade_nominal']) ?
                        $detail[$side]['gain']['grade_nominal'] : $detail[$side]['gain']['grade'];

                    // 戻り値にポイントを加算。ただし、マイナスは無視する。
                    if($pt > 0) {
                        $userId = $charaSvc->needUserId( $record["{$side}_id"] );
                        $result[$userId] += $pt;
                    }
                }
            }
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * バトルの結果をセットする。
     *
     * @param int       バトルID
     * @param int       ステータスコード。このクラスの定数を使用。
     * @param array     result_detail に格納するデータ。決着していないなら省略可能。
     */
    public function setStatus($battleId, $statusCode, $detail = null) {

        $update = array();
        $update['status'] = $statusCode;

        // 決着しているなら result_detail, result_at も更新する。
        if(self::SETTLE_BORDER <= $statusCode) {

            // データ容量節約のため、装備品のflavor_textを削除する。
            for($i = 0 ; $i <= 1 ; $i++) {

                $side = $i ? 'challenger' : 'defender';

                $equips = &$detail[$side]['equip'];
                if(!$equips)
                    continue;

                foreach($equips['before'] as &$equip) {
                    unset($equip['flavor_text']);
                }unset($equip);

                foreach($equips['after'] as &$equip) {
                    unset($equip['flavor_text']);
                }unset($equip);
            }

            // json_encode。
            $update['result_detail'] = json_encode($detail);

            // result_at の更新
            $update['result_at'] = array('sql'=>'NOW()');
        }

        $this->updateRecord($battleId, $update);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * バトルに対するコメントをセットする。
     *
     * @param int       バトルID
     * @param string    コメント内容。
     */
    public function setComment($battleId, $comment) {

        $textSvc = new Text_LogService();

        // 指定されたバトル情報を取得。
        $battle = $this->needRecord($battleId);

        // 防衛側のユーザIDを取得。
        $toId = Service::create('Character_Info')->needUserId($battle['defender_id']);

        // コメントの更新の場合。
        if($battle['comment_id']) {
            $textSvc->updateText($battle['comment_id'], $comment, $toId);

        // コメントの作成の場合。
        }else {
            $textId = Service::create('Text_Log')->postText('BTL', $comment, $battle['player_id'], $toId);
            $this->updateRecord($battleId, array('comment_id'=>$textId));
        }
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'battle_id';

    // 20日以上経過したレコードは削除するようにする。
    protected $deleteOlds = 20;


    //-----------------------------------------------------------------------------------------------------
    /**
     * insertRecordをオーバーライド
     */
    public function insertRecord($values, $returnAutoNumber = false) {

        // side_reverse の値を正規化。
        $values['side_reverse'] = $values['side_reverse'] ? 1 : 0;

        // validation_code の値を自動で決定。
        $values['validation_code'] = Common::createRandomString(32);

        // ready_detail をjson_encode。
        if(isset($values['ready_detail'])) {

            // データ容量節約のため、装備品のflavor_textを削除する。
            for($i = 0 ; $i <= 1 ; $i++) {

                $side = $i ? 'challenger' : 'defender';

                $equips = &$values['ready_detail'][$side]['equip'];
                if(!$equips)
                    continue;

                foreach($equips as &$equip) {
                    unset($equip['flavor_text']);
                }unset($equip);
            }

            // json_encode。
            $values['ready_detail'] = json_encode($values['ready_detail']);
        }

        // result_detail をjson_encode。INSERT時にこのキーないと思うけど…
        if(isset($values['result_detail'])) $values['result_detail'] = json_encode($values['result_detail']);

        return parent::insertRecord($values, $returnAutoNumber);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * processRecordをオーバーライド。
     */
    public function processRecord(&$record) {

        $ready_detail = (strlen($record['ready_detail']) > 0) ? json_decode($record['ready_detail'], true) : array();

        // 無効試合になったかどうかも加味した "true_status" 列を加える
        if(($record['status'] == Battle_LogService::CREATED  ||  $record['status'] == Battle_LogService::IN_GAME)
            && time() - strtotime($record['create_at']) > Battle_LogService::TOO_LONG_MATCH_SECS) {
            // 未決着で、規定時間を過ぎているなら無効。
            $record['true_status'] = Battle_LogService::NO_GAME;
        }else if(($record['status'] == Battle_LogService::CREATED || $record['status'] == Battle_LogService::IN_GAME) && $ready_detail["in_game_flg"] == 1){
            // 未決着で、in_game_flgが立っているならコンティニュー
            $record['true_status'] = Battle_LogService::IN_CONTINUE;
        }else {
            // それ以外はそのまま
            $record['true_status'] = $record['status'];
        }

        // ready_detail, result_detail をjson_decodeしておく。
        $record['ready_detail'] = (strlen($record['ready_detail']) > 0) ? json_decode($record['ready_detail'], true) : array();
        $record['result_detail'] = (strlen($record['result_detail']) > 0) ? json_decode($record['result_detail'], true) : array();

        // コメントがある場合は取得して、"comment" 列に格納する。
        $record['comment'] = Service::create('Text_Log')->getText($record['comment_id']);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * deleteOldRecordsをオーバーライド。
     * 紐づいているText_Logレコードも削除するようにする。
     */
    public function deleteOldRecords() {

        $dao = $this->createDao();

        // 削除対象になるレコードを取得。
        $sql = '
            SELECT battle_id
                 , comment_id
            FROM battle_log
            WHERE create_at < NOW() - INTERVAL ? DAY
            LIMIT 40
        ';
        $resultset = $dao->getAll($sql, $this->deleteOlds);

        // なかったらリターン。
        if(!$resultset)
            return;

        // 紐づいているText_Logレコードを削除。
        $commentIds = ResultsetUtil::colValues($resultset, 'comment_id');
        Service::create('Text_Log')->deleteRecordsIn($commentIds);

        // 削除対象のレコードを削除。
        $dao->delete(array(
            'battle_id' => ResultsetUtil::colValues($resultset, 'battle_id'),
        ));
    }
}
