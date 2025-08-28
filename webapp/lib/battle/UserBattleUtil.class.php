<?php

/**
 * ユーザ対戦によるバトルについての、バトルユーティリティ実装。
 */
Class UserBattleUtil extends BattleCommon {

    // 対戦防衛時、アイテム破損判定を回避できるラストアクセスからの時間数
    const BREAK_EXEMPT_HOURS = 3;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 対戦相手キャラクターの抽出を行い、そのユーザ一覧を返す。
     *
     * @param array     抽出の基準になるキャラクターレコード。
     * @param int       戦闘種別ID。
     * @param int       何件抽出するか。
     * @return array    対戦相手キャラクターのレコードを列挙した配列。
     */
    public function getRivalList($character, $tourId, $findCount) {

        // 現在はユーザ対戦の相手を抽出するのみ。

        $memberSvc = new User_MemberService();
        $charaSvc = new Character_InfoService();
        $battleSvc = new Battle_LogService();

        // 対戦相手候補の情報を最低限取得してシャッフルする。
        $candidates = $charaSvc->getRivals($character, $findCount + 4);

        // 仲間のID一覧を取得。
        $memberIds = $memberSvc->getMemberIds($character['user_id']);

        // 候補キャラ一覧の中から規定件数を取得。
        $resultIds = array();
        foreach($candidates as $candidate) {

            // 自分は除く。
            if($candidate['user_id'] == $character['user_id'])
                continue;

            // 自分の仲間は除く。
            if(in_array($candidate['user_id'], $memberIds))
                continue;

            // ユーザバトル中と思われるキャラは除く
            if( $battleSvc->inUserBattle($candidate['character_id']) )
                continue;

            // 対戦チュートリアルをまだ行っていないユーザは除く
            $user = Service::create('User_Info')->needRecord($candidate['user_id']);
            if($user['tutorial_step'] <= User_InfoService::TUTORIAL_RIVAL)
                continue;

            // スフィアでの行動が近いキャラは除く。
#             if( Service::create('Sphere_Info')->isActive($candidate['character_id']) )
#                 continue;

            // 候補者のIDを追加。規定件数になったらループを抜ける。
            $resultIds[] = $candidate['character_id'];
            if(count($resultIds) >= $findCount)
                break;
        }

        // IDからレコードを取得して、擬似列を追加してリターン。
        $list = $charaSvc->getRecordsIn($resultIds, false);
        $charaSvc->addExColumn($list, true);
        return $list;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定したキャラ同士がバトルできるかどうかを返す。
     * システム的におかしい引数の場合は例外を投げる。
     *
     * @param int       挑戦側キャラクタID
     * @param int       防衛側キャラクタID
     * @param int       戦闘種別ID。
     * @return string   調査結果を表すコード。以下のいずれか。
     *                      ok              対戦可能
     *                      consume_pt      対戦ptが足りない
     *                      count_rival     同じ相手との一日バトル制限数に達している
     *                      sphere          フィールドクエスト進行中である
     */
    public function canBattle($challengerId, $defenderId, $tournamentId = Tournament_MasterService::TOUR_MAIN) {

        // 自分と対決しようとしているならエラー。
        if($challengerId == $defenderId)
            throw new MojaviException('自分と対戦しようとした。');

        // 双方の情報を取得。
        $svc = new Character_InfoService();
        $challenger = $svc->needRecord($challengerId);
        $defender =   $svc->needRecord($defenderId);

        // 双方の所属ユーザが同じであればエラー。
        if($challenger['user_id'] == $defender['user_id'])
            throw new MojaviException('同じ所属のキャラ同士で対戦しようとした。');

        // システムユーザとは対戦できない。
        if($challenger['user_id'] < 0  ||  $defender['user_id'] < 0)
            throw new MojaviException('システムユーザのキャラで対戦しようとした。');

        // 同じ相手との一日バトル制限数
        $battleSvc = new Battle_LogService();
        if(DUEL_LIMIT_ON_DAY_RIVAL <= $battleSvc->getTodayBattleCount($challenger['user_id'], $defenderId))
            return 'count_rival';

        // 防衛側のスフィア出撃チェック。
#         if( Service::create('Sphere_Info')->isActive($defenderId) )
#             return 'sphere';

        // 挑戦側のユーザのIDを取得。
        $user = Service::create('User_Info')->needRecord($challenger['user_id']);

        // 対戦ptの有無を調査
        if($user['match_pt'] < USER_BATTLE_CONSUME)
            return 'consume_pt';

        // ここまで来ればOK
        return 'ok';
    }


    // 基底メソッドのオーバーライド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * createBattleをオーバーライド。
     * side_reverse, relate_id の入力を不要にする。
     */
    public function createBattle($params) {

        $params['side_reverse'] = false;
        $params['relate_id'] = null;

        return parent::createBattle($params);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBrainLevelを実装。
     */
    protected function getBrainLevel($character) {

        // 相手側のブレインレベルは50固定。
        return 50;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getTimeupTurnsを実装。
     */
    protected function getTimeupTurns() {

        // ターン数は8固定。
        return 8;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * openBattleを実装。
     */
    public function openBattle($battle) {

        $userSvc = new User_InfoService();

        // 対戦ptがあるかどうかチェック。
        $user = $userSvc->needRecord($battle['player_id']);
        if($user['match_pt'] < USER_BATTLE_CONSUME) {
            Controller::getInstance()->getLogger()->WARNING("openBattle: 対戦ptが足りない\n_GET = " . print_r($_GET, true));
            return 'consume_pt';
        }

        // 対戦ptを減じる。
        Service::create('User_Info')->plusValue($battle['player_id'], array(
            'match_pt' => -1 * USER_BATTLE_CONSUME,
        ));

        return '';
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * continueBattleを実装。
     */
    public function continueBattle($battle) {

        // 特にすることはない。
        return '';
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * discontinuRecvBattleを実装。
     * バトルを途中から復帰できるようにする。
     */
    public function discontinuRecvBattle($battle, $param) {

        // 特にすることはない。
        return '';
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * finishBattleをオーバーライド。
     * 履歴の作成を行う。
     */
    public function finishBattle($battleId, $detail) {

        $historySvc = new History_LogService();
        $charaSvc = new Character_InfoService();

        // とりあえずバトルを終了処理。
        $battle = parent::finishBattle($battleId, $detail);

        // 履歴の作成。
        $historySvc->insertRecord(array(
            'user_id' => $charaSvc->needUserId($battle['challenger_id']),
            'type' => History_LogService::TYPE_BATTLE_CHALLENGE,
            'ref1_value' => $battle['defender_id'],
            'ref2_value' => $battleId,
        ));
        $historySvc->insertRecord(array(
            'user_id' => $charaSvc->needUserId($battle['defender_id']),
            'type' => History_LogService::TYPE_BATTLE_DEFENCE,
            'ref1_value' => $battle['challenger_id'],
            'ref2_value' => $battleId,
        ));

        // リターン
        return $battle;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * バトルの背景画像パスを返す。
     */
    public function getBattleBg($battle) {

        //ユーザーバトルはとりあえず森で決め打ちで
        $image = "forest";

        return $image;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * makeResultDetail()をオーバーライド。
     * チーム対戦チケット付与処理を行う。
     */
    protected function makeResultDetail($battle, $detail, $updateResult, $spendResult, $tourResult) {

        // 基底の処理を行う。
        $result = parent::makeResultDetail($battle, $detail, $updateResult, $spendResult, $tourResult);

        // 今日、まだバトルしていないなら、チケットを付与してresult_detailにチケット付与のマークが
        // 付くようにする。
        if(TEAM_BATTLE_OPEN){
          if( !Service::create('Battle_Log')->alreadyBattleToday($battle['challenger_id']) ) {
              Service::create('User_Item')->gainItem($battle['player_id'], 99002);
              $result['challenger']['gain']['ticket'] = true;
          }
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * updateCharacterをオーバーライド。
     * character_info.hpの減算処理を行う。
     */
    protected function updateCharacter($battle, $detail) {

        $charaSvc = new Character_InfoService();

        // 挑戦側、防衛側を順次処理していく。
        $damage = ($detail['challenger']['hp_on_end'] == 0) ? false : $detail['defender']['total_hurt'];
        $charaSvc->damageHp($battle['challenger_id'], $damage);
        $damage = ($detail['defender']['hp_on_end'] == 0) ? false : $detail['challenger']['total_hurt'];
        $charaSvc->damageHp($battle['defender_id'], $damage);

        // あとは親のメソッドに処理させる。
        return parent::updateCharacter($battle, $detail);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBattleExpをオーバーライド。
     * 防衛側の経験値を 1/4 する。
     */
    protected function getBattleExp($battle, $detail) {

        $result = parent::getBattleExp($battle, $detail);

        $result['defender'] = ceil($result['defender'] * 0.25);

        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBattleGoldをオーバーライド。
     */
    protected function getBattleGold($battle, $detail) {

        // 戻り値初期化。防衛側には入らないので、挑戦側についてのみ考える。
        $result = array('challenger'=>0, 'defender'=>0);

        // 負けたのなら 0。
        if($detail['result'] == Battle_LogService::DEF_WIN)
            return $result;

        $gradeSvc = new Grade_MasterService();

        // 挑戦側と防衛側のキャラクター情報取得。
        $charaSvc = new Character_InfoService();
        $challenger = $charaSvc->needRecord($battle['challenger_id']);
        $defender =   $charaSvc->needRecord($battle['defender_id']);

        // 基本的には、挑戦側の階級に応じて報奨金を手に入れるが...
        $grade = $gradeSvc->needRecord($challenger['grade_id']);
        $gold = $grade['battle_reward'];

        // 相手との階級差に応じて増減する。
        $gradeDef = $gradeSvc->gradeCmp($defender['grade_id'], $challenger['grade_id']);
        switch(true) {
            case $gradeDef > 0:   $gold *= 1.0 + ($gradeDef*0.20);    break;   // 相手の階級が上なら、[階級差x20%]の増額。
            case $gradeDef < 0:   $gold *= 1.0 + ($gradeDef*0.15);    break;   // 相手の階級が下なら、[階級差x15%]の減額。
        }

        // 0以下にはならないようにする。
        if($gold <= 0)
            $gold = 1;

        // タイムアップの場合は40%にする。
        if($detail['result'] == Battle_LogService::TIMEUP)
            $gold *= 0.4;

        // 相討ちの場合は100%のボーナスを付ける。
        else if($detail['result'] == Battle_LogService::DRAW)
            $gold *= 2.0;

        // 最後に端数処理してリターン。
        $result['challenger'] = (int)ceil($gold);
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBattleGradeをオーバーライド。
     */
    protected function getBattleGrade($battle, $detail) {

        // まずは基底で処理。
        $result = parent::getBattleGrade($battle, $detail);

        // 防衛側は1/3。
        $result['defender'] /= 3;
        $result['defender'] = (int)round(abs($result['defender'])) * ($result['defender'] > 0 ? +1 : -1);

        // 挑戦側が負けている場合、最低でも1は減らす。
        if($detail['result'] == Battle_LogService::DEF_WIN  &&  $result['challenger'] >= 0)
            $result['challenger'] = -1;

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getEquipSpendingをオーバーライド。
     */
    protected function getEquipSpending($battle, $detail) {

        // とりあえず共通処理の結果を取得。
        $result = parent::getEquipSpending($battle, $detail);

        // 挑戦側は標準ロジックの1/2に。
        foreach($result['challenger'] as $index => &$value) {
            if($index != 'break_judge')
                $value = (int)($value / 2);
        }unset($value);

        // 防衛側は消耗しない。
        foreach($result['defender'] as $index => &$value) {
            if($index != 'break_judge')
                $value = 0;
        }unset($value);

        // リターン。
        return $result;
    }
}
