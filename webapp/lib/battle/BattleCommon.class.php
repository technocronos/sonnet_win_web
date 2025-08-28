<?php

/**
 * バトルに関する処理を収めているクラスの基底。
 */
abstract class BattleCommon {

    // staticメソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * バトルFLASHで使用するスピードの優位度(スピードバランス)を計算する。
     * +1.0 ～ -1.0 の値で、プレイヤー側側完全優位なら+1.0、相手側完全優位なら-1.0
     *
     * @param array     プレイヤー側キャラ情報。character_info レコードが拡張されたもの。
     * @param array     同、相手側。
     * @return float    スピードバランス。
     */
    public static function getSpeedBalance($sideP, $sideE) {

        // バランス1.0となるスピード差を求める。
        // 10 の [両者の平均Lv * 3]% 増し。
        $speedWidth = 10 * (1.0 + ($sideP['level']+$sideE['level'])/2 * 0.03);

        // スピードバランスの計算。
        $result = ($sideP['total_speed'] - $sideE['total_speed']) / $speedWidth;

        // +1.0 ～ -1.0 に補正する。
        if($result > 1.0)       $result = 1.0;
        else if($result < -1.0) $result = -1.0;

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * battle_logレコードを受け取って、そのバトルを扱うのに適したユーティリティクラスのオブジェクトを返す。
     *
     * @param array     battle_logレコード
     * @return object   BattleCommonからの派生クラスのオブジェクト。
     */
    public static function factory($battle) {

        switch($battle['tournament_id']) {

            case Tournament_MasterService::TOUR_MAIN:
                $className = 'UserBattleUtil';
                break;

            case Tournament_MasterService::TOUR_QUEST:

		        // バトル情報からスフィアを取得。
        		$sphereData = Service::create('Sphere_Info')->needRecord($battle['relate_id']);

		        // 派生クラスの名前を取得。
		        $customClassName = 'FieldBattle' . sprintf('%05d', $sphereData["quest_id"]) . 'Util';

		        // 派生クラスが定義されているファイルパスを取得。
		        $customFile = dirname(__FILE__).'/extends/'.$customClassName.'.class.php';

		        // そのファイルがあるなら派生クラス名を返す。
		        if(file_exists($customFile)) {
		            //require_once($customFile);
	                $className = $customClassName;

		        // ファイルがないなら、FieldBattleUtilを返す。
		        }else {
	                $className = 'FieldBattleUtil';
		        }

                break;
        }

        return new $className();
    }


    // インスタンスメソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * バトルデータの作成を行う。
     *
     * @param array     以下のキーを持つ配列。
     *                      challenger      挑戦側バトルキャラ情報。character_info レコードが拡張された
     *                                      ものだが、必要があれば任意に情報を追加できる。
     *                      defender        同、防衛側
     *                      tournament_id   戦闘種別ID
     *                      player_id       バトルをプレイするユーザのID
     *                      side_reverse    プレイユーザの視点に立つキャラが防衛側である場合はtrueを指定する
     *                      relate_id       必要ある場合は、関連ID
     * @return int      作成したバトルレコードのID
     */
    public function createBattle($params) {

        // バトルレコード作成
        $record = array(
            'tournament_id' => $params['tournament_id'],
            'challenger_id' => $params['challenger']['character_id'],
            'defender_id' => $params['defender']['character_id'],
            'player_id' => $params['player_id'],
            'side_reverse' => $params['side_reverse'],
            'relate_id' => $params['relate_id'],
            'status' => Battle_LogService::CREATED,
            'ready_detail' => array(
                'rand_seed' => mt_rand(1, 65535),
                'continue_count' => $params['continue_count'],
                'in_game_flg' => 0,
                'challenger' => $params['challenger'],
                'defender' => $params['defender'],
            ),
        );
        return Service::create('Battle_Log')->insertRecord($record, true);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * バトルFLASHで使用するパラメータを返す。
     *
     * @param array     battle_logレコード。
     * @return array    次のキーを持つ配列。
     *                      sideP        プレイヤー側、character_info(拡張モード)
     *                      sideE        同、相手側
     *                      other        戦闘時に使用されるその他のパラメータ
     *                          rand_seed       乱数シード
     *                          brain_level     敵側思考Lv
     *                          speed_balance   スピードバランス
     *                          timeup_turns    タイムアップになるターン数
     *                          damage_indexP   バトルの事前評価
     *                          damage_indexE
     *                          speed_index
     *                          total_index
     *                          term_aimP
     *                          term_aimE
     *                          navi_open       ナビの台詞
     *                          navi_win
     *                          navi_lose
     *                          navi_draw
     *                          navi_timeup
     */
    public function getFlashParams($battle) {

        // その他パラメータ初期化。
        $other = array();

        // プレイヤーキャラと相手キャラが挑戦側、防衛側のどちらになるのかを取得。
        $sideP = &$battle['ready_detail'][ $battle['side_reverse'] ? 'defender' : 'challenger' ];
        $sideE = &$battle['ready_detail'][ $battle['side_reverse'] ? 'challenger' : 'defender' ];

        // スピードバランスとブレインレベル、タイムアップターン数を取得。
        $other['rand_seed'] = $battle['ready_detail']['rand_seed'];
        $other['speed_balance'] = self::getSpeedBalance($sideP, $sideE);
        $other['brain_level'] = $this->getBrainLevel($sideE);
        $other['timeup_turns'] = $this->getTimeupTurns();

        // バトルの事前評価を行う。
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

        // 平均ダメージ量($aveDamageC, $aveDamageD)を算出
        $aveDamageC = max(0, $sideP['total_attack1'] * 0.80 - $sideE['total_defence1'] * 0.65)
                    + max(0, $sideP['total_attack2'] * 0.80 - $sideE['total_defence2'] * 0.65)
                    + max(0, $sideP['total_attack3'] * 0.80 - $sideE['total_defence3'] * 0.65);
        $aveDamageC /= 3;
        $aveDamageD = max(0, $sideE['total_attack1'] * 0.80 - $sideP['total_defence1'] * 0.65)
                    + max(0, $sideE['total_attack2'] * 0.80 - $sideP['total_defence2'] * 0.65)
                    + max(0, $sideE['total_attack3'] * 0.80 - $sideP['total_defence3'] * 0.65);
        $aveDamageD /= 3;

        // ダメージインデックスを取得。平均ダメージ量が最大HPの15%以上なら"+1"、1.5%以下なら"-1"。
        $damRate = $aveDamageC / $sideE['hp_max'];
        if($damRate >= 0.15)        $other['damage_indexP'] = +1;
        else if($damRate <= 0.015)  $other['damage_indexP'] = -1;
        else                        $other['damage_indexP'] =  0;

        $damRate = $aveDamageD / $sideP['hp_max'];
        if($damRate >= 0.15)        $other['damage_indexE'] = +1;
        else if($damRate <= 0.015)  $other['damage_indexE'] = -1;
        else                        $other['damage_indexE'] =  0;

        // スピードインデックスを求める。プレイヤー側優位なら+1、相手側優位なら-1。
        $other['speed_index'] = ( (abs($other['speed_balance']) > 0.625) ? 1 : 0 )
                               * ( ($other['speed_balance'] > 0) ? +1 : -1 );

        // ダメージインデックスとスピードインデックスを合わせて、トータルインデックスを求める。
        // この値は...
        //     +3   プレイヤー側ダメージインデックスhigh、かつ、相手側ダメージインデックスlow、しかもプレイヤー側スピード優位
        //     +2   例) プレイヤー側のほうがダメージインデックス高い、かつ、スピード優位
        //              プレイヤー側ダメージインデックスhigh、かつ、相手側ダメージインデックスlow
        //     +1   例) プレイヤー側ダメージインデックスhigh、かつ、相手側ダメージインデックスlow、でも相手側スピード優位
        //              プレイヤー側ダメージインデックスhigh、あとは同等
        //              プレイヤー側スピード優位、あとは同等
        //      0   例) 両方ダメージインデックhigh
        //              両方ダメージインデックスlow
        //              プレイヤー側ダメージインデックスhigh、相手側スピード優位
        //              全インデックス0
        //     -n   プラスの反対
        $other['total_index'] = $other['damage_indexP'] - $other['damage_indexE'] + $other['speed_index'];

        // ターミネート狙いかどうかを取得。
        // 現在HPが最大HPの30%以下、かつ、平均ダメージ量が現在HPの5%以上ならそう判断する。
        if($sideE['hp'] > 0)
            $other['term_aimP'] = ($sideE['hp'] / $sideE['hp_max'] < 0.30)  &&  ($aveDamageC / $sideE['hp'] >= 0.05);
        else
            $other['term_aimP'] = 1;

        if($sideP['hp'] > 0)
            $other['term_aimE'] = ($sideP['hp'] / $sideP['hp_max'] < 0.30)  &&  ($aveDamageD / $sideP['hp'] >= 0.05);
        else
            $other['term_aimE'] = 1;

        // ナビの台詞を取得する。
        $other = $other + $this->getNaviLines($other, $sideE);

        //コンティニューアイテム個数を得る。敵側は要らない。
        $sideP["continueInfo"] = $this->getContinueInfo($battle);

        // リターン。
        return array(
            'sideP' => $sideP,
            'sideE' => $sideE,
            'other' => $other,
        );
    }


const CONTINUE_ERR_NO_ITEM = 1;
const CONTINUE_ERR_OVER_COUNT = 2;
const CONTINUE_ERR_NOT_USE = 3;
const CONTINUE_ERR_NOT_QUEST = 4;
const CONTINUE_ERR_NOT_AVATAR = 5;

//コンティニューできるリミット回数（3回コンティニューできるということは5回戦闘可能ということ）
const CONTINUE_LIMIT_COUNT = 3;

    /*
    コンティニューアイテム情報を得る。
    ・1回のバトルでコンティニューできる回数に制限をかけないといけない
    ・コンティニューできる敵に制限をかけないといけない
    ・クエストバトル以外では使えないようにしないといけない
    */
    public function getContinueInfo($battle) {

        // プレイヤーキャラと相手キャラが挑戦側、防衛側のどちらになるのかを取得。
        $sideP = &$battle['ready_detail'][ $battle['side_reverse'] ? 'defender' : 'challenger' ];
        $sideE = &$battle['ready_detail'][ $battle['side_reverse'] ? 'challenger' : 'defender' ];

        $uitemSvc = new User_ItemService();
        $uitem = $uitemSvc->getHoldCount($sideP["user_id"], Item_MasterService::BATTLE_CONTINUE_ID);

        $array["continueError"] = 0;
        $array["continueItemCnt"] = $uitem;

        //アイテムが無い(これはエラーにしない)
        if($array["continueItemCnt"] == 0){
            //$array["continueError"] = self::CONTINUE_ERR_NO_ITEM;
        }

        //1回の戦闘でコンティニューしすぎ
        if($battle["ready_detail"]["continue_count"] > self::CONTINUE_LIMIT_COUNT){
            $array["continueError"] = self::CONTINUE_ERR_OVER_COUNT;
        }

        //この敵には使えない
        if($sideE["continue_not_use"]){
            $array["continueError"] = self::CONTINUE_ERR_NOT_USE;
        }

        //クエストバトル以外では使えない
        if($battle["tournament_id"] != Tournament_MasterService::TOUR_QUEST){
            $array["continueError"] = self::CONTINUE_ERR_NOT_QUEST;
        }

        //自分のコードが主人公以外は使えない。
        if($sideP["code"] != "avatar"){
            $array["continueError"] = self::CONTINUE_ERR_NOT_AVATAR;
        }

        return $array;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * バトルの開始処理を行う。
     *
     * @param array     battle_logレコード
     * @return string   FLASHに返すエラーコード。エラーがない場合はカラ文字列。
     */
    abstract public function openBattle($battle);

    //-----------------------------------------------------------------------------------------------------
    /**
     * バトルの開始処理を行う。
     *
     * @param array     battle_logレコード
     * @return string   FLASHに返すエラーコード。エラーがない場合はカラ文字列。
     */
    abstract public function continueBattle($battle);

    //-----------------------------------------------------------------------------------------------------
    /**
     * バトルの開始処理を行う。
     *
     * @param array     battle_logレコード
     * @return string   FLASHに返すエラーコード。エラーがない場合はカラ文字列。
     */
    abstract public function discontinuRecvBattle($battle,$param);

    //-----------------------------------------------------------------------------------------------------
    /**
     * バトルの決着処理を行う。
     *
     * @param int       バトルID
     * @param array     FLASHがPOSTしたデータ。
     * @return array    データ更新後のbattle_logレコード。
     */
    public function finishBattle($battleId, $detail) {

        $battleSvc = new Battle_LogService();

        // バトルレコードを取得。
        $battle = $battleSvc->needRecord($battleId);

        // FLASHがPOSTしたデータから不要なものを削除。必要なものを追加。
        $detail = $this->translateDetail($battle, $detail);

        // 戦闘種別ごとのカウント処理。
        $ctourSvc = new Character_TournamentService();
        $tourResult = array();
        $tourResult['challenger'] = $ctourSvc->recordFight($battle['challenger_id'], $battle['tournament_id'], $detail['result'], true);
        $tourResult['defender'] =   $ctourSvc->recordFight($battle['defender_id'],   $battle['tournament_id'], $detail['result'], false);

        // アイテム使用回数更新＆壊れ判定
        $spendResult = $this->spendEquip($battle, $detail);

        // 経験値・所持金の増減、階級変更、アイテム獲得等。
        $updateResult = $this->updateCharacter($battle, $detail);

        // バトルレコードの result_detail 列の値を作成。
        $result = $this->makeResultDetail($battle, $detail, $updateResult, $spendResult, $tourResult);

        // バトルレコードを決着状態へ更新
        $battleSvc->setStatus($battleId, $detail['result'], $result);
        $battle['status'] = $detail['result'];
        $battle['result_detail'] = $result;

        // 記念すべき勝利数・敗北数を上げた場合の処理を行う。
        if($detail['result'] != Battle_LogService::TIMEUP) {

            switch($detail['result']) {
                case Battle_LogService::CHA_WIN:  $chaCode='win';   $defCode='lose';  break;
                case Battle_LogService::DEF_WIN:  $chaCode='lose';  $defCode='win';   break;
                case Battle_LogService::DRAW:     $chaCode='draw';  $defCode='draw';  break;
            }

            if($battle['side_reverse'])
                $this->processMemorialResult($battle['defender_id'],   $defCode, $result['defender']['total_result']);
            else
                $this->processMemorialResult($battle['challenger_id'], $chaCode, $result['challenger']['total_result']);
        }

        // リターン
        return $battle;
    }


    // protectedメンバ
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 相手側ブレインレベルを返す。
     *
     * @param array     battle_log.ready_detail にある相手側バトルキャラ情報
     * @return int      相手側ブレインレベル
     */
    abstract protected function getBrainLevel($character);


    //-----------------------------------------------------------------------------------------------------
    /**
     * タイムアップになるターン数を返す。
     *
     * @return int      タイムアップになるターン数
     */
    abstract protected function getTimeupTurns();


    //-----------------------------------------------------------------------------------------------------
    /**
     * ナビの台詞を取得する。
     *
     * @param array     バトルの事前評価を格納している配列。キーはgetFlashParams()を参照。
     * @param array     相手側のバトルキャラ情報
     * @return array    各場面におけるナビの台詞を格納している配列。キーはgetFlashParams()を参照。
     */
    protected function getNaviLines($indices, $enemyChara) {

        $set = $this->getLineSet($enemyChara);

        $lines = array();
        $lines['navi_open'] =   $this->pickNaviLine($set, $indices, 'open');
        $lines['navi_win'] =    $this->pickNaviLine($set, $indices, 'win');
        $lines['navi_lose'] =   $this->pickNaviLine($set, $indices, 'lose');
        $lines['navi_draw'] =   $this->pickNaviLine($set, $indices, 'draw');
        $lines['navi_timeup'] = $this->pickNaviLine($set, $indices, 'timeup');

        return $lines;
    }

    /**
     * getNaviLines() のヘルパ
     * 台詞セットの中から該当の台詞を取り出す。
     */
    protected function pickNaviLine($set, $indices, $type) {

        if($indices['term_aimP'] && $indices['term_aimE'])
            if( isset($set['death_match'][$type]) )  return $set['death_match'][$type];

        if($indices['term_aimE'])
            if( isset($set['danger'][$type]) )  return $set['danger'][$type];

        if($indices['term_aimP'])
            if( isset($set['snipe'][$type]) )  return $set['snipe'][$type];

        if($indices['total_index'] == +3)
            if( isset($set['superior3'][$type]) )  return $set['superior3'][$type];

        if($indices['total_index'] >= +2)
            if( isset($set['superior2'][$type]) )  return $set['superior2'][$type];

        if($indices['total_index'] >= +1)
            if( isset($set['superior1'][$type]) )  return $set['superior1'][$type];

        if($indices['total_index'] == -3)
            if( isset($set['inferior3'][$type]) )  return $set['inferior3'][$type];

        if($indices['total_index'] <= -2)
            if( isset($set['inferior2'][$type]) )  return $set['inferior2'][$type];

        if($indices['total_index'] <= -1)
            if( isset($set['inferior1'][$type]) )  return $set['inferior1'][$type];

        if($indices['damage_indexP'] == +1 && $indices['damage_indexE'] == +1)
            if( isset($set['hard_match'][$type]) )  return $set['hard_match'][$type];

        if($indices['damage_indexP'] == -1 && $indices['damage_indexE'] == -1)
            if( isset($set['soft_match'][$type]) )  return $set['soft_match'][$type];

        if($indices['damage_indexP'] == +1 && $indices['speed_index'] == -1)
            if( isset($set['hard_vs_speed'][$type]) )  return $set['hard_vs_speed'][$type];

        if($indices['damage_indexP'] == -1 && $indices['speed_index'] == +1)
            if( isset($set['speed_vs_hard'][$type]) )  return $set['speed_vs_hard'][$type];

        return $set['default'][$type];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ナビの台詞セットを取得する。
     *
     * @param array     相手側のバトルキャラ情報
     * @return array    ナビの台詞セット。次のような構造を有するもの
     *                      death_match     双方のHPが少ない場合
     *                          open            開始時の台詞
     *                          win             勝利時の台詞
     *                          lose            敗北時の台詞
     *                          draw            相討時の台詞
     *                          timeup          時間切れの台詞
     *                      danger          自分のHPが少ない場合。以下、"death_match" と同じキーを有するものとする。
     *                      snipe           相手のHPが少ない
     *                      superior3       事前評価インデックスがすべて優位
     *                      superior2       事前評価インデックスのうち2点以上で優位
     *                      superior1       事前評価インデックスのうち1点以上で優位
     *                      inferior3       事前評価インデックスがすべて不利
     *                      inferior2       事前評価インデックスのうち2点以上で不利
     *                      inferior1       事前評価インデックスのうち1点以上で不利
     *                      hard_match      ダメージ量が双方とも大きい
     *                      soft_match      ダメージ量が双方とも小さい
     *                      hard_vs_speed   プレイヤー側のダメージ量が大きいが、相手はスピードが速い
     *                      speed_vs_hard   プレイヤー側のスピードが速いが、相手はダメージ量が大きい
     *                      default         いずれにも該当しない
     *                  default以外のキーは省略可能。上から順に該当判定される。
     *                  また、default以外のキーは台詞が5つ揃っている必要もない。
     */
    protected function getLineSet($enemyChara) {

        // 半角幅29、2行まで

        // 戻り値初期化。
        $set = array();

        $set['death_match'] = array(
            'open' =>   AppUtil::getText("battle_text_death_match_open"),
            'win' =>    AppUtil::getText("battle_text_death_match_win"),
            'lose' =>   AppUtil::getText("battle_text_death_match_lose"),
            'draw' =>   AppUtil::getText("battle_text_death_match_draw"),
            'timeup' => AppUtil::getText("battle_text_death_match_timeup"),
        );

        $set['danger'] = array(
            'open' =>   AppUtil::getText("battle_text_danger_open"),
            'win' =>    AppUtil::getText("battle_text_danger_win"),
            'lose' =>   AppUtil::getText("battle_text_danger_lose"),
            'draw' =>   AppUtil::getText("battle_text_danger_draw"),
            'timeup' => AppUtil::getText("battle_text_danger_timeup"),
        );

        $set['snipe'] = array(
            'open' =>   AppUtil::getText("battle_text_snipe_open"),
            'win' =>    AppUtil::getText("battle_text_snipe_win"),
            'lose' =>   AppUtil::getText("battle_text_snipe_lose"),
            'draw' =>   AppUtil::getText("battle_text_snipe_draw"),
            'timeup' => AppUtil::getText("battle_text_snipe_timeup"),
        );

        $set['superior3'] = array(
            'open' =>   AppUtil::getText("battle_text_superior3_open"),
            'win' =>    AppUtil::getText("battle_text_superior3_win"),
            'lose' =>   AppUtil::getText("battle_text_superior3_lose"),
            'timeup' => AppUtil::getText("battle_text_superior3_timeup"),
        );

        $set['superior2'] = array(
            'open' =>   AppUtil::getText("battle_text_superior2_open"),
            'draw' =>   AppUtil::getText("battle_text_superior2_draw"),
        );

        $set['superior1'] = array(
            'open' =>   AppUtil::getText("battle_text_superior1_open"),
            'win' =>    AppUtil::getText("battle_text_superior1_win"),
            'lose' =>   AppUtil::getText("battle_text_superior1_lose"),
        );


        $set['inferior3'] = array(
            'open' =>   AppUtil::getText("battle_text_inferior3_open"),
            'win' =>    AppUtil::getText("battle_text_inferior3_win"),
            'lose' =>   AppUtil::getText("battle_text_inferior3_lose"),
            'timeup' => AppUtil::getText("battle_text_inferior3_timeup"),
        );

        $set['inferior2'] = array(
            'open' =>   AppUtil::getText("battle_text_inferior2_open"),
            'draw' =>   AppUtil::getText("battle_text_inferior2_draw"),
        );

        $set['inferior1'] = array(
            'open' =>   AppUtil::getText("battle_text_inferior1_open"),
            'win' =>    AppUtil::getText("battle_text_inferior1_win"),
            'lose' =>   AppUtil::getText("battle_text_inferior1_lose"),
        );

        $set['hard_match'] = array(
            'open' =>   AppUtil::getText("battle_text_hard_match_open"),
        );

        $set['soft_match'] = array(
            'open' =>   AppUtil::getText("battle_text_soft_match_open"),
        );

        $set['hard_vs_speed'] = array(
            'open' =>   AppUtil::getText("battle_text_hard_vs_speed_open"),
        );

        $set['speed_vs_hard'] = array(
            'open' =>   AppUtil::getText("battle_text_speed_vs_hard_open"),
        );

        $set['default'] = array(
            'open' =>   AppUtil::getText("battle_text_default_open"),
            'win' =>    AppUtil::getText("battle_text_default_win"),
            'lose' =>   AppUtil::getText("battle_text_default_lose"),
            'draw' =>   AppUtil::getText("battle_text_default_draw"),
            'timeup' => AppUtil::getText("battle_text_default_timeup"),
        );

        // リターン。
        return $set;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * FLASHがPOSTしたデータを整形したものを返す。
     *
     * @param array     battle_logレコード。
     * @param array     FLASHがPOSTしたデータ。
     * @return array    以下のキーを含む配列。
     *                      result          決着コード。Battle_LogServiceで定義されている値
     *                      match_length    ターン数
     *                      challenger      挑戦側について...
     *                          hp_on_end           終了時HP
     *                          tactN               Nは整数。各戦術を選択した回数。
     *                          normal_attacks      通常攻撃回数
     *                          normal_hits         通常攻撃ヒット回数
     *                          normal_hurt         通常攻撃ダメージ合計
     *                          revenge_count       リベンジ発動回数
     *                          revenge_attacks     リベンジ攻撃回数
     *                          revenge_hits        リベンジヒット回数
     *                          revenge_hurt        リベンジダメージ合計
     *                          total_hurt          与えたダメージ合計
     *                      defender        防衛側。挑戦側と同様
     */
    protected function translateDetail($battle, $flash) {

        // FLASHがPOSTしたデータには以下のキーが含まれる。
        // (Xは "P" か "E" が入る。それぞれプレイヤー側、相手側のデータであることを表す。
        //     result      FLASHからの決着コード。"win", "lose", "draw", "timeup" のいずれか。
        //     time        バトル時間。ターン数。
        //     hpX         終了時HP
        //     tactXn      nは整数。nで表される戦術を選択した回数。
        //     nattCntX    通常攻撃を繰り出した回数
        //     nhitCntX    通常攻撃を当てた回数
        //     ndamX       通常攻撃によって与えたダメージ
        //     revCntX     リベンジを発動した回数
        //     rattCntX    リベンジ攻撃を繰り出した回数
        //     rhitCntX    リベンジ攻撃を当てた回数
        //     rdamX       リベンジ攻撃によって与えたダメージ
        //     odamX       その他によって与えたダメージ

        $result = array();
        $result['match_length'] = $flash['time'];

        // FLASHからの決着コードをBattle_LogServiceで定義されている値に変換する。
        $result['result'] = (int)strtr($flash['result'], array(
            'win' =>    $battle['side_reverse'] ? Battle_LogService::DEF_WIN : Battle_LogService::CHA_WIN,
            'lose' =>   $battle['side_reverse'] ? Battle_LogService::CHA_WIN : Battle_LogService::DEF_WIN,
            'draw' =>   Battle_LogService::DRAW,
            'timeup' => Battle_LogService::TIMEUP,
        ));

        if(!$result['result'])
            throw new MojaviException('定義されていない決着コードです: ' . $flash['result']);

        // 挑戦側と防衛側とに帰属する情報をセット。
        for($i = 0 ; $i < 2 ; $i++) {

            $side = ($i == 0) ? 'P' : 'E';
            $store = ($side == 'P' ^ $battle['side_reverse']) ? 'challenger' : 'defender';

            $result[$store] = array(
                'hp_on_end' =>       $flash["hp{$side}"],
                'tact0' =>           $flash["tact{$side}0"],
                'tact1' =>           $flash["tact{$side}1"],
                'tact2' =>           $flash["tact{$side}2"],
                'tact3' =>           $flash["tact{$side}3"],
                'normal_attacks' =>  $flash["nattCnt{$side}"],
                'normal_hits' =>     $flash["nhitCnt{$side}"],
                'normal_hurt' =>     $flash["ndam{$side}"],
                'revenge_count' =>   $flash["revCnt{$side}"],
                'revenge_attacks' => $flash["rattCnt{$side}"],
                'revenge_hits' =>    $flash["rhitCnt{$side}"],
                'revenge_hurt' =>    $flash["rdam{$side}"],
                'total_hurt' =>      $flash["ndam{$side}"] + $flash["rdam{$side}"] + $flash["odam{$side}"],
            );
        }

        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * バトル後のキャラクターデータの更新を行う。
     *
     * @param array     battle_logレコード。
     * @param array     バトルの内容詳細。translateDetail() の戻り値。
     * @return array    以下のキーを含む配列。
     *                      challenger      挑戦側
     *                          character       更新後のCharacter_Info
     *                          gain_exp        獲得した経験値
     *                          gain_gold       獲得したお金。奪われる場合はマイナス
     *                          nominal_grade   獲得した階級pt(名目)。減った場合はマイナス
     *                          gain_grade      獲得した階級pt(実質)。減った場合はマイナス
     *                          gain_items      獲得したuser_itemの配列
     *                          gain_monster    キャプチャーしたモンスターのcharacter_id
     *                      defender        防衛側。挑戦側と同様
     */
    protected function updateCharacter($battle, $detail) {

        $userSvc = new User_InfoService();
        $charaSvc = new Character_InfoService();
        $uitemSvc = new User_ItemService();

        // 戻り値初期化。
        $result = array('challenger'=>array(), 'defender'=>array());

        // 各種獲得値を取得。
        $exp = $this->getBattleExp($battle, $detail);
        $gold = $this->getBattleGold($battle, $detail);
        $grade = $this->getBattleGrade($battle, $detail);
        $items = $this->getBattleItems($battle, $detail);

        // 獲得内容の反映。挑戦側、防衛側の順で処理する。
        for($i = 0 ; $i < 2 ; $i++) {

            $side = $i ? 'challenger' : 'defender';

            // ユーザIDの取得。
            $chara = $charaSvc->needRecord($battle["{$side}_id"]);
            $result[$side]['character'] = $chara;

            // システムユーザの場合は処理しない。
            if($chara['user_id'] < 0) {
                $result[$side]['gain_exp'] = 0;
                $result[$side]['gain_gold'] = 0;
                $result[$side]['nominal_grade'] = 0;
                $result[$side]['gain_grade'] = 0;
                $result[$side]['gain_items'] = array();
                continue;
            }

            // キャラクターの経験値取得＆戻り値の取得。
            $result[$side]['gain_exp'] = 0;
            if($exp[$side]) {
                $growth = $charaSvc->gainExp($battle["{$side}_id"], $exp[$side]);
                $result[$side]['gain_exp'] = $growth['after']['exp'] - $growth['before']['exp'];
                $result[$side]['character'] = $growth['after'];
            }

            // 所持金増減
            $result[$side]['gain_gold'] = $gold[$side];
            if($gold[$side])
                $userSvc->plusValue($chara['user_id'], array('gold'=>$gold[$side]));

            // 階級pt増減
            $result[$side]['nominal_grade'] = $grade[$side];
            $result[$side]['gain_grade'] = $grade[$side];
            if($grade[$side]) {

                // gainGradePt() の第二引数は実際に与えられた階級ptに修正されることに注意。
                $growth = $charaSvc->gainGradePt($battle["{$side}_id"], $result[$side]['gain_grade']);
                $result[$side]['character'] = $growth['after'];
            }

            // アイテム付与
            $result[$side]['gain_items'] = array();
            foreach($items[$side] as $itemId) {
                $uitemId = $uitemSvc->gainItem($chara['user_id'], $itemId);
                $result[$side]['gain_items'][] = $uitemSvc->getRecord($uitemId);
            }
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で渡されたバトル情報から、双方が獲得する経験値を計算する。
     *
     * @param array     battle_logレコード。
     * @param array     バトルの内容詳細。translateDetail() の戻り値。
     * @return array    以下のキーを含む配列。
     *                      challenger      挑戦側が獲得する経験値。
     *                      defender        同防衛側。
     */
    protected function getBattleExp($battle, $detail) {

        // 戻り値初期化。
        $result = array();

        // 挑戦側と防衛側のキャラクター情報取得。
        $charaSvc = new Character_InfoService();
        $challenger = &$battle['ready_detail']['challenger'];
        $defender =   &$battle['ready_detail']['defender'];

        // 挑戦側、防衛側の順に処理する。
        for($i = 0 ; $i < 2 ; $i++) {

            $sideS = ($i == 0) ? 'challenger' : 'defender';
            $sideO = ($i == 0) ? 'defender' : 'challenger';

            // フルターン、フルダメージで勝った時の経験値を計算
            $fullExp = $this->getFullExp(${$sideS}, ${$sideO});

            // 完全経験値から、ターミネート、ダメージ、ターンから得られる経験値を計算。
            $termExp = $fullExp * 0.40;
            $damExp = $fullExp * 0.35;
            $timeExp = $fullExp * 0.25;

            // 得られる経験値初期化。
            $exp = 0.0;

            // ターミネートしてるのならターミネート分を追加。
            if(
                   ($i == 0  &&  $detail['result'] == Battle_LogService::CHA_WIN)
                || ($i == 1  &&  $detail['result'] == Battle_LogService::DEF_WIN)
            ) {
                $exp += $termExp;
            }

            // 相手のHPに対するダメージの割合を求めて、ダメージ経験値を追加。
            $rate = $detail[$sideS]['total_hurt'] / ${$sideO}['hp_max'];
            if($rate < 0.0) $rate = 0.0;
            $exp += $damExp * $rate;

            // フルターンを 8 として、ターン経験値を追加。
            $rate = $detail['match_length'] / 8;
            $exp += $timeExp * $rate;

            // 最後に端数処理して完了。
            $result[$sideS] = (int)ceil($exp);
        }

        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定したキャラが、別に指定したキャラに、フルターン、フルダメージで勝った時の経験値を計算する。
     *
     * @param array     勝ったキャラの情報
     * @param array     負けたキャラの情報
     * @return int      フルターン、フルダメージの場合の経験値
     */
    protected function getFullExp($winner, $loser) {

        // 基本経験値を取得。
        $base = $this->getBaseExp($loser);

        // レベル差による倍率を求める。[(相手の強さ/自分の強さ)の3乗] とする。
        // 「強さ」とはLvに10を足した値とする。
        $rate = pow( ($loser['level']+10) / ($winner['level']+10), 3 );

        // 基本経験値に倍率をかけて、完全経験値とする。
        // ただし、倍率は3倍を上限とする。
        return $base * min($rate, 3.0);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 基底の経験値計算 getBattleExp() で使用される「基本経験値」を返す。
     *
     * @param array     ready_detail に格納されている相手のキャラクター情報
     * @return int      基本経験値
     */
    protected function getBaseExp($oppositeChara) {

        // 基底は固定で40。
        return 40;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で渡されたバトル情報から、双方が獲得するお金を計算する。
     *
     * @param array     battle_logレコード。
     * @param array     バトルの内容詳細。translateDetail() の戻り値。
     * @return array    以下のキーを含む配列。
     *                      challenger      挑戦側が獲得するお金。奪われる場合はマイナス
     *                      defender        同防衛側。
     */
    protected function getBattleGold($battle, $detail) {

        return array('challenger'=>0, 'defender'=>0);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で渡されたバトル情報から、双方が獲得する階級ptを計算する。
     *
     * @param array     battle_logレコード。
     * @param array     バトルの内容詳細。translateDetail() の戻り値。
     * @return array    以下のキーを含む配列。
     *                      challenger      挑戦側が獲得する階級pt。マイナスもあり
     *                      defender        同防衛側。
     */
    protected function getBattleGrade($battle, $detail) {

        $gradeSvc = new Grade_MasterService();

        // 戻り値初期化。
        $result = array('challenger'=>0, 'defender'=>0);

        // 挑戦側と防衛側のキャラクター情報取得。
        $charaSvc = new Character_InfoService();
        $challenger = $charaSvc->needRecord($battle['challenger_id']);
        $defender =   $charaSvc->needRecord($battle['defender_id']);

        // 挑戦側から見たときの、相手の階級との差を取得。
        $gradeDef = $gradeSvc->gradeCmp($defender['grade_id'], $challenger['grade_id']);

        // 時間切れの場合。挑戦側にのみ、自分以上の階級相手の場合に固定で1pt。
        if($detail['result'] == Battle_LogService::TIMEUP) {
            $result['challenger'] = ($gradeDef >= 0) ? 1 : 0;

        // 相討ちの場合。固定で両者とも+20。
        }else if($detail['result'] == Battle_LogService::DRAW) {
            $result['challenger'] = 20;
            $result['defender'] = 20;

        // 勝ち・負けの場合。
        }else {

            // 挑戦側が負けている場合は階級差をひっくり返して考える。
            if($detail['result'] == Battle_LogService::DEF_WIN)
                $gradeDef *= -1;

            // 自分以上の階級に勝った、あるいは自分以下の階級に負けた場合の変動量
            if($gradeDef >= 0) {
                $pt = 10 + $gradeDef * 6;
                if($pt > 30) $pt = 30;

            // 自分未満の階級に勝った、あるいは自分以上の階級に負けた場合の変動量
            }else {
                $pt = 10 + $gradeDef * 3;   // $gradeDefはマイナスであることに注意。
                if($pt < 0) $pt = 0;
            }

            // 勝った方はプラス、負けたほうはマイナス。ただしマイナスは1/2。
            $result[$detail['result'] == Battle_LogService::CHA_WIN ? 'challenger' : 'defender'] = $pt;
            $result[$detail['result'] == Battle_LogService::CHA_WIN ? 'defender' : 'challenger'] = -1 * $pt/2;
        }

        // 端数処理。
        $result['challenger'] = (int)round(abs($result['challenger'])) * ($result['challenger'] > 0 ? +1 : -1);
        $result['defender'] =   (int)round(abs($result['defender'])) *   ($result['defender'] >   0 ? +1 : -1);

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で渡されたバトル情報から、双方が獲得するアイテムを計算する。
     *
     * @param array     battle_logレコード。
     * @param array     バトルの内容詳細。translateDetail() の戻り値。
     * @return array    以下のキーを含む配列。
     *                      challenger      挑戦側が獲得するアイテムのitem_id。
     *                      defender        同防衛側。
     */
    protected function getBattleItems($battle, $detail) {

        return array('challenger'=>array(), 'defender'=>array());
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * バトル後の装備経験値アップ等を処理する。
     *
     * @param array     battle_logレコード。
     * @param array     バトルの内容詳細。translateDetail() の戻り値。
     * @return array    以下のキーを含む配列。
     *                      challenger      挑戦側。
     *                          before          更新前の、装備箇所(mount_id)をキー、装備している
     *                                          user_itemレコードを値とする配列。
     *                          after           同、更新後。損壊した箇所はnullになっている。
     *                      defender        防衛側。挑戦側と同様。
     */
    protected function spendEquip($battle, $detail) {

        $equipSvc = new Character_EquipmentService();

        // アイテムの消耗を取得。
        $spend = $this->getEquipSpending($battle, $detail);

        // 戻り値初期化。
        $result = array('challenger'=>null, 'defender'=>null);

        // 挑戦側消耗処理。システムキャラでない場合のみ行う。
        if($battle['challenger_id'] > 0)
            $result['challenger'] = $equipSvc->spendEquips($battle['challenger_id'], $spend['challenger']['break_judge'], $spend['challenger']);

        if($battle['defender_id'] > 0)
            $result['defender'] = $equipSvc->spendEquips($battle['defender_id'], $spend['defender']['break_judge'], $spend['defender']);

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * バトルでの装備品の消耗度を取得する。
     *
     * @param array     battle_logレコード。
     * @param array     バトルの内容詳細。translateDetail() の戻り値。
     * @return array    以下のキーを含む配列。
     *                      challenger      挑戦側の消耗度。
     *                                      mount_master.mount_id をキー、消耗度を値とする配列。
     *                                      キー "break_judge" には、破損判定を行うかどうかを指定する。
     *                      defender        同、防衛側。
     */
    protected function getEquipSpending($battle, $detail) {

        // 戻り値初期化。
        $result = array();

        // 消耗度の基本値を取得。経過ターン数が1-2なら0、3-6なら1、7-8なら2。
        $base = (int)round(($detail['match_length'] - 1) / 4);

        // 挑戦側、防衛側の順に処理する。
        for($i = 0 ; $i < 2 ; $i++) {

            $sideS = $i ? 'challenger' : 'defender';
            $sideO = $i ? 'defender' : 'challenger';

            // キャラクターのタイプによって分ける。
            switch($battle['ready_detail'][$sideS]['race']) {

                // 主人公キャラの場合。各箇所の消耗度は次のように計算する。
                //     武器     基本値 + 強攻選択回数/2(切り捨て)
                //     服       基本値 + リベンジ発動回数
                //     頭       基本値 + 吸収選択回数/2(切り捨て)
                //     ｱｸｾｻﾘ    基本値 + ユニゾン発生回数
                case 'PLA':
                    $result[$sideS] = array(
                        'break_judge' => true,
                        Mount_MasterService::PLAYER_WEAPON => $base + (int)floor($detail[$sideS]['tact1'] / 2),
                        Mount_MasterService::PLAYER_BODY =>   $base + $detail[$sideS]['revenge_count'],
                        Mount_MasterService::PLAYER_HEAD =>   $base + (int)floor($detail[$sideS]['tact3'] / 2),
                        Mount_MasterService::PLAYER_SHIELD => $base + $detail[$sideS]['tact0'],
                    );
                    break;

                // システム設定キャラは装備箇所がない。
                case 'MOB':
                    $result[$sideS] = array(
                        'break_judge' => true,
                    );
                    break;
            }
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * バトルレコードの result_detail 列の値を作成する。
     *
     * @param array     battle_logレコード。
     * @param array     バトルの内容詳細。translateDetail() の戻り値。
     * @param array     キャラクターデータの更新結果。updateCharacter() の戻り値。
     * @param array     装備消耗処理の結果。spendEquip() の戻り値。
     * @param array     戦闘統計処理の結果。Character_TournamentService::recordFight() の戻り値を、
     *                  "challenger", "defender" のキーに格納したもの。
     * @return array    battle_log.result_detail 列に格納すべき値。
     */
    protected function makeResultDetail($battle, $detail, $updateResult, $spendResult, $tourResult) {

        // 戦闘時間。
        $result = array('match_length'=>$detail['match_length']);

        //仮想通貨ゲットかどうか
        $result["get_vcoin"] = $updateResult["get_vcoin"];

        //raidpointゲットかどうか
        $result["get_raid_point"] = $updateResult["get_raid_point"];
        $result["monster"] = $updateResult["monster"];

        //nftゲットかどうか
        $result["get_nft"] = $updateResult["get_nft"];

        // 挑戦側⇒防衛側の順で処理する。
        for($i = 0 ; $i < 2 ; $i++) {
            $side = $i ? 'challenger' : 'defender';

            $result[$side] = array(
                'character' => $updateResult[$side]['character'],
                'total_result' => $tourResult[$side]['total'],
                'gain' => array(
                    'exp' => $updateResult[$side]['gain_exp'],
                    'gold' => $updateResult[$side]['gain_gold'],
                    'grade' => $updateResult[$side]['gain_grade'],
                    'grade_nominal' => $updateResult[$side]['nominal_grade'],
                    'uitem' => $updateResult[$side]['gain_items'],
                    'monster' => $updateResult[$side]['gain_monster'],
                ),
                'equip' => $spendResult[$side],
                'summary' => $detail[$side],
            );
        }

        // リターン。
        return $result;
    }


    // privateメンバ
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 記念すべき勝利数を上げた場合の処理を行う。
     */
    private function processMemorialResult($charaId, $code, $totalResult) {

        // システムキャラなら処理しない。
        if($charaId < 0)
            return;

        // 戦果に応じて、勝利数or敗北数or相討数 を取得。
        $num = $totalResult[$code];

        // 勝利or敗北で、10, 50, 100, 200, 300, ... 回目である場合、あるいは、
        // 相討ちで 10, 20, 30, ... 回目である場合以外は何もしない。
        if( !(
            ( $code != 'draw'  &&  ($num == 10  ||  $num == 50  ||  $num%100 == 0) )
            ||
            ( $code == 'draw'  &&  $num%10 == 0 )
        )) {
            return;
        }

        // キャラの情報を取得して、アクティビティ送信に使う値を作成。
        $chara = Service::create('Character_Info')->needRecord($charaId);
        $trans = array('[:name:]'=>Text_LogService::get($chara['name_id']), '[:num:]'=>$num);

        // アクティビティのテンプレートを取得。
        switch($code) {
            case 'win':    $activity = ACTIVITY_MEMORIAL_WIN;   break;
            case 'lose':   $activity = ACTIVITY_MEMORIAL_LOSE;  break;
            case 'draw':   $activity = 'キャラ"[:name:]"が相討ち[:num:]回目!?';  break;
        }

        // アクティビティを送信。
        PlatformApi::postActivity(strtr($activity, $trans));
    }
}
