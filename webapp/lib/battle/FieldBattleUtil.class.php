<?php

/**
 * クエストフィールドでのバトルについての、バトルユーティリティ実装。
 */
Class FieldBattleUtil extends BattleCommon {

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたユニットがフィールド上で打倒された場合の経験値とお金を計算する。
     *
     * @param array     打倒されたユニットのデータ
     * @param array     打倒したユニットのデータ
     * @return array    次のキーを持つ配列。
     *                      exp
     *                      gold
     */
    public static function getFieldReward($terminated, $terminator) {

        $object = new self();

        // 経験値は7割。お金は満額。
        return array(
            'exp' => (int)($object->getFullExp($terminator, $terminated) * 0.7),
            'gold' => $object->getTerminatedGold($terminated),
        );
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたユニットがフィールド上で打倒された場合の経験値とお金を計算する。
     *
     * @param array     打倒されたユニットのデータ
     * @param array     打倒したユニットのデータ
     * @return array    次のキーを持つ配列。
     *                      exp
     *                      gold
     */
    public static function getRaidDungeonResult($quest_id, $user_id, $mon_character_id){
        $result = array();

        $raid = Service::create('Raid_Dungeon')->getCurrent();
        $uitemSvc = new User_ItemService();

        if($raid != null){
            if($quest_id == $raid["quest_id"]){
                $raid_status = Service::create('Raid_Dungeon')->getStatus($raid);
                if($raid_status == Raid_DungeonService::START){

                    //ethアドレスが必要だが登録が無い場合はリターン
                    if($raid["require_kind"] == Raid_DungeonService::REQUIRE_ETHADDR){
                        $addr =Service::create('User_Property_String')->getProperty($user_id, 'ether_addr');
                        if($addr == null || $addr == "")
                            return $result;
                    }


                    //図鑑登録対象かどうか調べる
                    $monsSvc = new Monster_MasterService();
                    $monster = $monsSvc->getRecord($mon_character_id);
                    //Common::varLog($monster);
                    if($monster != null){
                        if($monster['rare_level'] == 1)
                            $amount = RAID_AMOUNT_RARE1;
                        else if($monster['rare_level'] == 2)
                            $amount = RAID_AMOUNT_RARE2;
                        else if($monster['rare_level'] == 3)
                            $amount = RAID_AMOUNT_RARE3;

                        //そのモンスターがすでに倒されているか調べる
                        $date = date('Y-m-d', strtotime("now"));
                        $monuser = Service::create('Raid_Monster_User')->getMonsterIdByDate($raid["id"], $mon_character_id, $date);
                        if($monuser == null){
                            Service::create('Raid_Monster_User')->setValue($raid["id"], $user_id, $mon_character_id, $amount);
                            $result["get_raid_point"] = $amount;
                            $result["monster"] = $monster;

                            if($raid["require_kind"] == Raid_DungeonService::REQUIRE_ETHADDR){
                                $addr =Service::create('User_Property_String')->getProperty($user_id, 'ether_addr');
                                if($addr != ""){
                                    //TODO::NFT付与処理
                                    $result["get_nft"] = true;
                                }
                            }

                            //これでレイドダンジョンがクリアされてるかどうか再度ステータスを調べる
                            $raid_status = Service::create('Raid_Dungeon')->getStatus($raid);
                            if($raid_status == Raid_DungeonService::START && Service::create('Raid_Monster_User')->is_clear($raid['id'])){
                                //終了処理をする
                                $userranklist = Service::create('Raid_Monster_User')->getUserRank($raid["id"]);

                                $today = date("Y-m-d");// 現在の日付け取得
                                $today = strtotime($today);// タイムスタンプへ変換
                                $past = ($today - strtotime($raid["start_at"])) / (60 * 60 * 24);

                                if($raid['join_prize_kind'] == Raid_PrizeService::PRIZE_KIND_BTC){
                                    if($past == 0)
                                        $flag_group = Vcoin_Flag_LogService::RAID_0;
                                    else if($past == 1)
                                        $flag_group = Vcoin_Flag_LogService::RAID_1;
                                    else if($past == 2)
                                        $flag_group = Vcoin_Flag_LogService::RAID_2;
                                    else if($past == 3)
                                        $flag_group = Vcoin_Flag_LogService::RAID_3;
                                    else if($past == 4)
                                        $flag_group = Vcoin_Flag_LogService::RAID_4;
                                    else if($past == 5)
                                        $flag_group = Vcoin_Flag_LogService::RAID_5;
                                }

                                foreach($userranklist as $row){
                                    if($raid['join_prize_kind'] == Raid_PrizeService::PRIZE_KIND_BTC){
                                        $vres = Service::create('User_Info')->setVirtualCoin($row['user_id'], $flag_group, $raid["join_prize"], $raid["id"]);
                                        if($vres)
                                            Common::varLog("レイドダンジョン全打倒 BTC付与 " . $past . "日目 user_id=" . $row['user_id'] . " raid_dungeon_id=" . $raid["id"] . " prize=" . $raid["join_prize"]);
                                    }else{
                                        $uitemId = $uitemSvc->gainItem($row['user_id'], (int)$raid["join_prize"]);
                                        Common::varLog("レイドダンジョン全打倒 アイテム付与 " . $past . "日目 user_id=" . $row['user_id'] . " raid_dungeon_id=" . $raid["id"] . " prize=" . $raid["join_prize"]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたキャラクタ同士の省略バトルでのダメージを計算する。
     *
     * @param array     一方のキャラデータ。
     * @param array     もう一方のキャラデータ。
     * @return array    第一引数側のキャラのダメージを "challenger"、第二引数側のキャラのダメージを
     *                  "defender" に格納した配列。
     */
    public static function omissionBattle($challenger, $defender) {

        // スピードバランスを取得。
        $speedBalance = self::getSpeedBalance($challenger, $defender);

        // ダメージ初期化。
        $result = array('challenger'=>0, 'defender'=>0);

        // リベンジカウントの初期化。
        $star = array('challenger'=>0, 'defender'=>0);

        // ノーマルダメージの計算。規定回数繰り返す。
        for($i = 0 ; $i < 10 ; $i++) {

            // 両者の攻撃カードの決定。
            $card = array('challenger'=>mt_rand(1, 3), 'defender'=>mt_rand(1, 3));

            // 攻め側⇒受け側の順で処理する。
            for($side = 0 ; $side < 2 ; $side++) {
                $attacker = $side ? 'defender' : 'challenger';
                $defencer = $side ? 'challenger' : 'defender';

                // ダメージ計算。
                $damage = self::calcNormalDamage(
                    $$attacker, $card[$attacker], $$defencer, $card[$defencer], $speedBalance * ($side ? -1 : +1)
                );

                // スターに変換されたならスターカウントを、ダメージになったならダメージをアップ。
                if($damage == -1)
                    $star[$attacker]++;
                else if($damage == -2)
                    $star[$defencer]++;
                else
                    $result[$defencer] += $damage;
            }

            // ダメージがHPを上回ったならそこでストップ。
            if($challenger['hp'] <= $result['challenger']  ||  $defender['hp'] <= $result['defender'])
                return $result;
        }

        // リベンジの計算。攻め側⇒受け側の順で処理する。
        for($side = 0 ; $side < 2 ; $side++) {
            $attacker = $side ? 'defender' : 'challenger';
            $defencer = $side ? 'challenger' : 'defender';

            // ダメージ計算。
            $result[$defencer] += self::calcRevengeDamage(
                $$attacker, $$defencer, $star[$attacker], $speedBalance * ($side ? -1 : +1)
            );
        }

        // リターン。
        return $result;
    }

    /**
     * 引数に指定された情報でノーマルダメージを計算する。
     *
     * @param array     攻撃側プロパティ
     * @param int       攻撃側カード
     * @param array     防御側プロパティ
     * @param int       防御側カード
     * @param float     攻撃側スピードバランス
     * @return int      ダメージ。ただし、-1は攻撃側のスターに、-2は防御側のスターになったことを意味する。
     */
    public static function calcNormalDamage($attacker, $attackCard, $defencer, $defenceCard, $speedBalance) {

        // カードの相性を判定。有利なら1、不利なら2、同じなら0。
        $affinity = $attackCard - $defenceCard;
        if($affinity < 0) $affinity += 3;

        // 不利なら攻撃側スターに。
        if($affinity == 2)
            return -1;

        // 攻撃側スピードバランスに従って20～60%で吸収判定。
        if( mt_rand(1, 100) <= 40 - (int)(20*$speedBalance) )
            return -2;

        // ここまで来たらダメージ計算。
        return self::calcDamage($attacker["total_attack{$attackCard}"], $defencer["total_defence{$attackCard}"]);
    }

    /**
     * 引数に指定された情報でリベンジダメージを計算する。
     *
     * @param array     攻撃側プロパティ
     * @param array     防御側プロパティ
     * @param int       スターの数
     * @param float     攻撃側スピードバランス
     * @return int      ダメージ
     */
    public static function calcRevengeDamage($attacker, $defencer, $starCount, $speedBalance) {

        // ダメージ初期化。
        $damage = 0;

        // スターの数、判定する。
        for($i = 0 ; $i < $starCount ; $i++) {

            // 攻撃側スピードバランスに従って25～75%で回避判定。
            if( mt_rand(1, 100) <= 50 - (int)(25*$speedBalance) )
                continue;

            // 攻撃カードの決定。
            $card = mt_rand(1, 3);

            // 攻撃力の計算。受け側の該当攻撃力の75% + 受け側の該当攻撃力の25%
            $attackPow = (int)($defencer["total_attack{$card}"] * 0.75 + $attacker["total_attack{$card}"] * 0.25);

            // ダメージ計算して戻り値に追加。
            $damage += self::calcDamage($attackPow, $defencer["total_defence{$card}"]);
        }

        // リターン。
        return $damage;
    }

    /**
     * 引数に指定された攻撃力と防御力によるダメージを計算する。
     *
     * @param int       攻撃力
     * @param int       防御力
     * @return int      ダメージ
     */
    public static function calcDamage($attack, $defence) {

        $damage = (int)($attack * 0.70 - $defence * 0.55);
        if($damage <= 0)
            $damage = mt_rand(0, 1);

        return $damage;
    }


    // 基底メソッドのオーバーライド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * createBattleをオーバーライド。
     * tournament_id の入力を不要にする。
     */
    public function createBattle($params) {

        //コンティニューレコードがあるならそちらを使う
        $battleSvc = new Battle_LogService();
        $battleId = $battleSvc->SearchContinueRec($params);

        if(is_null($battleId)){
            $params['tournament_id'] = Tournament_MasterService::TOUR_QUEST;
            //コンティニューをした回数
            $params['continue_count'] = 0;
            //コンティニュー時のスターの個数。最初はゼロ
            $params['challenger']["starcnt"] = 0;
            $params['defender']["starcnt"] = 0;

            //サマリーも持っておく
            $params['challenger']['summary']['tact0'] = 0;
            $params['challenger']['summary']['tact1'] = 0;
            $params['challenger']['summary']['tact2'] = 0;
            $params['challenger']['summary']['tact3'] = 0;
            $params['challenger']['summary']['nattCnt'] = 0;
            $params['challenger']['summary']['nhitCnt'] = 0;
            $params['challenger']['summary']['ndam'] = 0;
            $params['challenger']['summary']['revCnt'] = 0;
            $params['challenger']['summary']['rattCnt'] = 0;
            $params['challenger']['summary']['rhitCnt'] = 0;
            $params['challenger']['summary']['rdam'] = 0;
            $params['challenger']['summary']['odam'] = 0;

            $params['defender']['summary']['tact0'] = 0;
            $params['defender']['summary']['tact1'] = 0;
            $params['defender']['summary']['tact2'] = 0;
            $params['defender']['summary']['tact3'] = 0;
            $params['defender']['summary']['nattCnt'] = 0;
            $params['defender']['summary']['nhitCnt'] = 0;
            $params['defender']['summary']['ndam'] = 0;
            $params['defender']['summary']['revCnt'] = 0;
            $params['defender']['summary']['rattCnt'] = 0;
            $params['defender']['summary']['rhitCnt'] = 0;
            $params['defender']['summary']['rdam'] = 0;
            $params['defender']['summary']['odam'] = 0;

            return parent::createBattle($params);
        }else{
            //これでゲームを進められるようにする
            //バトルに戻る時にエラーにしないようにバトルログのstatusをCREATEDに戻す
            $update["status"] = Battle_LogService::CREATED;
            Service::create('Battle_Log')->updateRecord($battleId, $update);

            return $battleId;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBrainLevelを実装。
     */
    protected function getBrainLevel($character) {

        // "battle_brain" キーがあるならそこから、ないなら50とする。
        return isset($character['battle_brain']) ? $character['battle_brain'] : 50;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getTimeupTurnsを実装。
     */
    protected function getTimeupTurns() {

        // ターン数は4固定。
        return 4;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getLineSetをオーバーライド。
     * 一部台詞を変更する。
     */
    protected function getLineSet($enemyChara) {

        // カスタムされた台詞セットがあるならそれをリターン。
        $set = SphereUnit::getBattleLines($enemyChara);
        if($set)
            return $set;

        // まずは親メソッドが設定する台詞セットを取得。
        $set = parent::getLineSet($enemyChara);

        $set['death_match']['win'] = AppUtil::getText("battle_text_field_death_match_win");
        $set['death_match']['lose'] = AppUtil::getText("battle_text_field_death_match_lose");

        $set['danger']['lose'] = AppUtil::getText("battle_text_field_danger_lose");

        $set['snipe']['open'] = AppUtil::getText("battle_text_field_snipe_open");
        $set['snipe']['lose'] = AppUtil::getText("battle_text_field_snipe_lose");

        $set['superior3']['open'] = AppUtil::getText("battle_text_field_superior3_open");
        $set['superior3']['win'] = AppUtil::getText("battle_text_field_superior3_win");

        $set['superior2']['open'] = AppUtil::getText("battle_text_field_superior2_open");

        $set['superior1']['open'] = AppUtil::getText("battle_text_field_superior1_open");
        $set['superior1']['lose'] = AppUtil::getText("battle_text_field_superior1_lose");

        $set['inferior3']['open'] = AppUtil::getText("battle_text_field_inferior3_open");
        $set['inferior3']['lose'] = AppUtil::getText("battle_text_field_inferior3_lose");

        $set['inferior2']['open'] = AppUtil::getText("battle_text_field_inferior2_open");

        $set['inferior1']['open'] = AppUtil::getText("battle_text_field_inferior1_open");
        $set['inferior1']['lose'] = AppUtil::getText("battle_text_field_inferior1_lose");

        $set['default']['open'] = AppUtil::getText("battle_text_field_default_open");
        $set['default']['win'] = AppUtil::getText("battle_text_field_default_win");
        $set['default']['lose'] = AppUtil::getText("battle_text_field_default_lose");
        $set['default']['timeup'] = AppUtil::getText("battle_text_field_default_timeup");

        // リターン。
        return $set;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * openBattleを実装。
     */
    public function openBattle($battle) {

        // 特にすることはない。
        return '';
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * continueBattleを実装。
     */
    public function continueBattle($battle) {

        // プレイヤーキャラと相手キャラが挑戦側、防衛側のどちらになるのかを取得。
        $sideP = &$battle['ready_detail'][ $battle['side_reverse'] ? 'defender' : 'challenger' ];
        $sideE = &$battle['ready_detail'][ $battle['side_reverse'] ? 'challenger' : 'defender' ];

        //コンティニューアイテムを本当に持っているか
        $continueInfo = $this->getContinueInfo($battle);

        if($continueInfo["continueItemCnt"] <= 0){
            return "error_no_item";
        }

        $uitemSvc = new User_ItemService();
        $uitem = $uitemSvc->getRecordByItemId($sideP["user_id"], Item_MasterService::BATTLE_CONTINUE_ID);

        // 使用。
        $uitemSvc->useItem($uitem['user_item_id'], $sideP["character_id"]);

        //バトルログを更新する
        $update['ready_detail'] = json_encode($battle['ready_detail']);
        Service::create('Battle_Log')->updateRecord($battle["battle_id"], $update);

        //スフィアにもhp更新を通知しないと復帰した時もとにもどってしまう。
        // バトル情報からスフィアを取得。
        $sphereData = Service::create('Sphere_Info')->needRecord($battle['relate_id']);
        $sphere = SphereCommon::load($sphereData);

        // 最大HPをhpに設定。つまり全回復

        // スフィアにバトルコンティニューを通知。
        $sphere->battleContinue($battle,$sideP["hp_max"]);

        return;
    }

    //オーバーライドしておくが親を実行してるだけ。
    public function getContinueInfo($battle) {

        $errorInfo = parent::getContinueInfo($battle);
        return $errorInfo;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * discontinuRecvBattleを実装。
     * バトルを途中から復帰できるようにする。
     */
    public function discontinuRecvBattle($battle, $param) {
        // プレイヤーキャラと相手キャラが挑戦側、防衛側のどちらになるのかを取得。
        $sideP = &$battle['ready_detail'][ $battle['side_reverse'] ? 'defender' : 'challenger' ];
        $sideE = &$battle['ready_detail'][ $battle['side_reverse'] ? 'challenger' : 'defender' ];

        $beforeHp = $sideP["hp"];

        //hpを書き換える
        $sideP["hp"] = (int)$param["hpP"]; //おそらく0
        $sideE["hp"] = (int)$param["hpE"]; //減ったまま
        $sideP["starcnt"] = (int)$param['starP']; 
        $sideE["starcnt"] = (int)$param["starE"]; 

        $sideP['summary']['tact0'] = (int)$param['tactP0'];
        $sideP['summary']['tact1'] = (int)$param['tactP1'];
        $sideP['summary']['tact2'] = (int)$param['tactP2'];
        $sideP['summary']['tact3'] = (int)$param['tactP3'];
        $sideP['summary']['nattCnt'] = (int)$param['nattCntP'];
        $sideP['summary']['nhitCnt'] = (int)$param['nhitCntP'];
        $sideP['summary']['ndam'] = (int)$param['ndamP'];
        $sideP['summary']['revCnt'] = (int)$param['revCntP'];
        $sideP['summary']['rattCnt'] = (int)$param['rattCntP'];
        $sideP['summary']['rhitCnt'] = (int)$param['rhitCntP'];
        $sideP['summary']['rdam'] = (int)$param['rdamP'];
        $sideP['summary']['odam'] = (int)$param['odamP'];
        $sideE['summary']['tact0']= (int)$param['tactE0'];
        $sideE['summary']['tact1']= (int)$param['tactE1'];
        $sideE['summary']['tact2']= (int)$param['tactE2'];
        $sideE['summary']['tact3']= (int)$param['tactE3'];
        $sideE['summary']['nattCnt'] = (int)$param['nattCntE'];
        $sideE['summary']['nhitCnt'] = (int)$param['nhitCntE'];
        $sideE['summary']['ndam'] = (int)$param['ndamE'];
        $sideE['summary']['revCnt']= (int)$param['revCntE'];
        $sideE['summary']['rattCnt'] = (int)$param['rattCntE'];
        $sideE['summary']['rhitCnt'] = (int)$param['rhitCntE'];
        $sideE['summary']['rdam'] = (int)$param['rdamE'];
        $sideE['summary']['odam'] = (int)$param['odamE'];


        //バトルに戻る時にエラーにしないようにバトルログのstatusをCREATEDに戻す
        $update["status"] = Battle_LogService::CREATED;
        //ただし、あくまでゲーム中なのだという情報は持っておかないと終了できない。
        $battle['ready_detail']['in_game_flg'] = 1;

        //バトルログを更新する
        $update['ready_detail'] = json_encode($battle['ready_detail']);

        Service::create('Battle_Log')->updateRecord($battle["battle_id"], $update);

        //スフィアにもhp更新を通知しないと復帰した時もとにもどってしまう。
        // バトル情報からスフィアを取得。
        $sphereData = Service::create('Sphere_Info')->needRecord($battle['relate_id']);
        $sphere = SphereCommon::load($sphereData);

        // スフィアにバトルコンティニューを通知。現在のHP分だけ減らして0にする。
        $sphere->battleContinue($battle, $beforeHp * -1);

        return;
        
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * finishBattleをオーバーライド。
     * スフィアへの連絡を行う。
     */
    public function finishBattle($battleId, $detail) {

        // とりあえずバトルを終了処理。
        $battle = parent::finishBattle($battleId, $detail);

        // バトル情報からスフィアを取得。
        $sphereData = Service::create('Sphere_Info')->needRecord($battle['relate_id']);
        $sphere = SphereCommon::load($sphereData);

        // スフィアにバトル終了を通知。
        $sphere->battleEnd($battle);

        return $battle;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * バトルの背景画像パスを返す。
     */
    public function getBattleBg($battle) {

        // バトル情報からスフィアを取得。
        $sphereData = Service::create('Sphere_Info')->needRecord($battle['relate_id']);

        // クエストIDからクエスト情報を検索。
        //$questData = Service::create('Quest_Master')->needRecord($sphereData["quest_id"]);

        if( !is_null($sphereData['state']['battle_bg']) )
            $image = $sphereData['state']['battle_bg'];
        else
            $image = "forest";

        return $image;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * updateCharacterをオーバーライド。
     */
    protected function updateCharacter($battle, $detail) {

        $result = parent::updateCharacter($battle, $detail);

        // 戻り値の「更新後のCharacter_Info」で、HPだけ反映させておく。
        $result['challenger']['character']['hp'] = $detail['challenger']['hp_on_end'];
        $result['defender']['character']['hp'] = $detail['defender']['hp_on_end'];

        // 勝敗が決しているなら...
        if($detail['result'] == Battle_LogService::CHA_WIN  ||  $detail['result'] == Battle_LogService::DEF_WIN) {

            // プレイヤー側と相手側を取得。
            $sideP = $battle['side_reverse'] ? 'defender' : 'challenger';
            $sideE = $battle['side_reverse'] ? 'challenger' : 'defender';

            // プレイヤー側が勝っているなら...
            if($battle['side_reverse'] ^ ($detail['result'] == Battle_LogService::CHA_WIN)) {

                // playerがシステムユーザの場合は処理しない。
                if($battle['ready_detail'][$sideP]['character_id'] > 0){

                    // モンスターキャプチャーを行う。成功しているなら戻り値にセット。
                    $result[$sideP]['gain_monster'] = Service::create('User_Monster')->captureMonster(
                        $battle['player_id'], $battle['ready_detail'][$sideE]['character_id']
                    );

                    //BTCキャンペーン期間中なら・・
                    if(strtotime(BTC_CAMPAIGN_START_DATE) <= strtotime(Common::getCurrentTime()) && strtotime(BTC_CAMPAIGN_END_DATE) > strtotime(Common::getCurrentTime())){

                        $user_id = $battle['player_id'];
                        $mon_character_id = $battle['ready_detail'][$sideE]['character_id'];

                        //図鑑登録対象かどうか調べる
                        $monsSvc = new Monster_MasterService();
                        $monster = $monsSvc->getRecord($mon_character_id);
                        //Common::varLog($monster);
                        if($monster != null){
                            if($monster['rare_level'] == 1)
                                $amount = BTC_AMOUNT_RARE1;
                            else if($monster['rare_level'] == 2)
                                $amount = BTC_AMOUNT_RARE2;
                            else if($monster['rare_level'] == 3)
                                $amount = BTC_AMOUNT_RARE3;

                            $vres = Service::create('User_Info')->setVirtualCoin($user_id, Vcoin_Flag_LogService::MONSTER, $amount, $mon_character_id);
                            if($vres)
                                $result["get_vcoin"] = $amount;
                        }
                    }

                    //レイドダンジョン中なら・・
                    $sphereData = Service::create('Sphere_Info')->needRecord($battle['relate_id']);
                    $user_id = $battle['player_id'];
                    $mon_character_id = $battle['ready_detail'][$sideE]['character_id'];

                    $res = self::getRaidDungeonResult($sphereData["quest_id"], $user_id, $mon_character_id);
                    $result["get_raid_point"] = $res["get_raid_point"];
                    $result["monster"] = $res["monster"];
                    $result["get_nft"] = $res["get_nft"];
                }
            }
        }

        // リターン
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBattleExpをオーバーライド。
     */
    protected function getBattleExp($battle, $detail) {

        $result = parent::getBattleExp($battle, $detail);

        // 負けた場合や相打ちの場合の経験値を 0 に。
        if($detail['result'] == Battle_LogService::CHA_WIN  ||  $detail['result'] == Battle_LogService::DRAW)
            $result['defender'] = 0;
        if($detail['result'] == Battle_LogService::DEF_WIN  ||  $detail['result'] == Battle_LogService::DRAW)
            $result['challenger'] = 0;

        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBaseExpをオーバーライド。
     */
    protected function getBaseExp($oppositeChara) {

        // "reward_exp" がセットされているならそこから。そうでないなら基底と同じとする。
        return isset($oppositeChara['reward_exp']) ?
            $oppositeChara['reward_exp'] :
            parent::getBaseExp($oppositeChara)
        ;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBattleGoldをオーバーライド。
     */
    protected function getBattleGold($battle, $detail) {

        // 戻り値初期化。
        $result = array('challenger'=>0, 'defender'=>0);

        // 相打ちや時間切れでは入らない。勝敗が決まっているなら...
        if($detail['result'] == Battle_LogService::CHA_WIN  ||  $detail['result'] == Battle_LogService::DEF_WIN) {

            // 勝ったほう、負けたほうを取得。
            $winSide =  ($detail['result'] == Battle_LogService::CHA_WIN) ? 'challenger' : 'defender';
            $loseSide = ($detail['result'] == Battle_LogService::CHA_WIN) ? 'defender' : 'challenger';

            // 負けたほうのデータからお金を計算して、勝ったほうへ設定する。
            $result[$winSide] = $this->getTerminatedGold($battle['ready_detail'][$loseSide]);
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBattleGold()から一部の処理を切り出したもの。
     * 引数で指定されたデータを持つキャラが倒されたときのお金を返す。
     */
    protected function getTerminatedGold($terminated) {

        // データに "reward_gold" が設定されているならそこから。
        if( isset($terminated['reward_gold']) )
            return $terminated['reward_gold'];

        // "reward_gold" がないなら、[10 + Lv x (3 + Lv/20)]。
        else
            return 10 + (int)($terminated['level'] * (3 + $terminated['level']/20));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBattleGradeをオーバーライド。
     */
    protected function getBattleGrade($battle, $detail) {

        $result = parent::getBattleGrade($battle, $detail);

        // マイナスにはならないようにする。
        if($result['challenger'] < 0)   $result['challenger'] = 0;
        if($result['defender'] < 0)     $result['defender'] = 0;

        // 値を挑戦側なら1/2、防衛側なら1/3する。
        $result['challenger'] = (int)($result['challenger'] / 2);
        $result['defender'] =   (int)($result['defender'] / 3);

        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBattleItemsをオーバーライド。
     */
    protected function getBattleItems($battle, $detail) {

        // 戻り値初期化。
        $result = array('challenger'=>array(), 'defender'=>array());

        // 相打ちや時間切れではアイテムはない。勝敗が決まっているなら...
        if($detail['result'] == Battle_LogService::CHA_WIN  ||  $detail['result'] == Battle_LogService::DEF_WIN) {

            // 勝ったほう、負けたほうを取得。
            $winer = $battle['ready_detail'][($detail['result'] == Battle_LogService::CHA_WIN) ? 'challenger' : 'defender'];
            $loser = $battle['ready_detail'][($detail['result'] == Battle_LogService::CHA_WIN) ? 'defender' : 'challenger'];

            // 勝ったほうがプレイヤー配下のユニットならば判定する。
            if($winer['player_owner']) {

                // 戻り値の勝ったほうへの参照を取得。
                $gain = &$result[($detail['result'] == Battle_LogService::CHA_WIN) ? 'challenger' : 'defender'];

                // "srare_drop", "rare_drop", "normal_drop" のうち設定されているものを、低確率のものから
                // 判定していく。
                if($loser['srare_drop']  &&  mt_rand(1, 1000) <= 1)
                    $gain[] = $loser['srare_drop'];
                else if($loser['rare_drop']  &&  mt_rand(1, 1000) <= 10)
                    $gain[] = $loser['rare_drop'];
                else if($loser['normal_drop']  &&  mt_rand(1, 1000) <= 100)
                    $gain[] = $loser['normal_drop'];
            }
        }

        // リターン。
        return $result;
    }
}
