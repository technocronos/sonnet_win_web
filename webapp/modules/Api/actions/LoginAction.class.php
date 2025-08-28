<?php

/**
 * ステータス画面の情報を取得する
 */
class LoginAction extends SmfBaseAction {

    // ユーザ登録していなくてもアクセス可能にする。
    protected $guestAccess = true;

    protected function doExecute($params) {
        $charaSvc = new Character_InfoService();

        $oauth = $params["oauth"];

        // まだ未登録
        if(!$this->userInfo) {
            if(!isset($_REQUEST["opensocial_owner_id"]) || $_REQUEST["opensocial_owner_id"] == ""){
                //ユーザーIDが無い
                $array['result'] = 'error';
                $array['err_code'] = 'error_session_expire';
            }else{
                //未登録
                $array['result'] = 'ok';
                $array['regist'] = '0';
                $array['oauth'] = $params["oauth"];
                $array['constants'] = $this->makeconst();
            }
        // エラー等で、ユーザレコードは出来てるのに、キャラクターレコードが出来てない場合。
        }else if( !$charaSvc->getAvatarId($this->userInfo['user_id']) ) {
                $array['result'] = 'ok';
                $array['regist'] = '2';
                $array['oauth'] = $params["oauth"];
                $array['constants'] = $this->makeconst();
        // まだチュートリアル中なら...
        }else if($this->userInfo['tutorial_step'] < User_InfoService::TUTORIAL_END) {
                $array = $this->getTutorialInfo($array);
                $array['result'] = 'ok';
                $array['regist'] = '3';
                $array['oauth'] = $params["oauth"];
                $array['constants'] = $this->makeconst();
        }else{
            $array['result'] = 'ok';
            $array['regist'] = '1';
            $array['oauth'] = $params["oauth"];
            $array['constants'] = $this->makeconst();

        }

        if(!isset($_REQUEST["opensocial_owner_id"]) || $_REQUEST["opensocial_owner_id"] == ""){
            $array['appsflyer'] = false;
        }else{
            $appsflyer = new AppsflyerService();
            $record = $appsflyer->getRecord($_REQUEST["opensocial_owner_id"]);

            if($record == null)
                $array['appsflyer'] = false;
            else 
                $array['appsflyer'] = true;
        }

        $textSvc = new Text_MasterService();

        $array['text_master'] = $textSvc->getAll();

        return $array;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 「ゲーム開始」のリンク先のアクションをビュー用変数にセットする。
     */
    private function decideStartAction() {

        $charaSvc = new Character_InfoService();

        // まだ未登録ならオープニング。
        if(!$this->userInfo) {
            $this->setAttribute('startAction', Common::genContainerURL('Swf', 'Prologue'));

        // エラー等で、ユーザレコードは出来てるのに、キャラクターレコードが出来てない場合。
        }else if( !$charaSvc->getAvatarId($this->userInfo['user_id']) ) {
            $this->setAttribute('startAction', Common::genContainerURL('User', 'AvatarCreate'));

        // まだチュートリアル中なら...
        }else if($this->userInfo['tutorial_step'] < User_InfoService::TUTORIAL_END) {
            $this->setAttribute('startAction', Common::genContainerURL('Swf', 'Tutorial'));

        // いずれでもないならメイン。
        }else {
            $this->setAttribute('startAction', Common::genContainerURL('Swf', 'Main'));
        }
    }

    /*
     *必要な定数を渡す
    */
    private function makeconst(){
        $array = [];

        // 行動ptの最大値
        $array["ACTION_PT_MAX"] = ACTION_PT_MAX;
        // 平時の行動ptが1秒でいくつ回復するか。3->2.5時間で全快するペース
        $array["ACTION_PT_RECOVERY"] = ACTION_PT_RECOVERY;
        // 対戦ptの最大値
        $array["MATCH_PT_MAX"] = MATCH_PT_MAX;
        // 平時の対戦ptが1秒でいくつ回復するか。60->90分で全快するペース
        $array["MATCH_PT_RECOVERY"] = MATCH_PT_RECOVERY;
        // 平時のHPが1秒で何%回復するかを小数で。8時間で全快するペース
        $array["HP_RECOVERY"] = HP_RECOVERY;
        // ユーザ対戦で消費するpt数
        $array["USER_BATTLE_CONSUME"] = USER_BATTLE_CONSUME;
        // プラットフォームのユーザ投稿で得られる行動pt
        $array["ARTICLE_AP"] = ARTICLE_AP;
        // ゲーム内通貨の名称・単位
        $array["GOLD_NAME"] = GOLD_NAME;
        // 同じ相手との一日バトル制限数
        $array["DUEL_LIMIT_ON_DAY_RIVAL"] = DUEL_LIMIT_ON_DAY_RIVAL;
        // フィールドクエスト進行中、ユーザ対戦から保護される時間(時間)
        $array["DUEL_SPHERE_PROTECT_HOURS"] = DUEL_SPHERE_PROTECT_HOURS;
        // メッセージの文字数制限(全角換算)
        $array["MESSAGE_LENGTH_LIMIT"] = MESSAGE_LENGTH_LIMIT;
        // メッセージ機能有効、無効
        $array["MESSAGE_ENABLE"] = MESSAGE_ENABLE;
        // ユーザ名の最大表示幅。
        $array["USERNAME_DISPLAY_WIDTH"] = USERNAME_DISPLAY_WIDTH;
        // キャラクタ名の最大長(半角換算)。
        $array["CHARACTER_NAME_LENGTH"] = CHARACTER_NAME_LENGTH;

        $array["ENVIRONMENT_TYPE"] = ENVIRONMENT_TYPE;
        $array["VCOIN_FEE"] = VCOIN_FEE;
        $array["VCOIN_MINIMAM"] = VCOIN_MINIMAM;
        $array["VCOIN_MINIMAM_PAYMENT"] = VCOIN_MINIMAM_PAYMENT;
        $array["VCOIN_RELEASE_FLG"] = VCOIN_RELEASE_FLG;

        //付与するビットコイン
        $array["BTC_AMOUNT_RARE1"] = BTC_AMOUNT_RARE1;//ザコ
        $array["BTC_AMOUNT_RARE2"] = BTC_AMOUNT_RARE2;//ボス
        $array["BTC_AMOUNT_RARE3"] = BTC_AMOUNT_RARE3;//大ボス

        $array["BTC_APPLY_RESTRICT_DATE"] = BTC_APPLY_RESTRICT_DATE;

        //付与するraidpoint
        $array["RAID_AMOUNT_RARE1"] = RAID_AMOUNT_RARE1;//ザコ
        $array["RAID_AMOUNT_RARE2"] = RAID_AMOUNT_RARE2;//ボス
        $array["RAID_AMOUNT_RARE3"] = RAID_AMOUNT_RARE3;//大ボス

        //スタートダッシュキャンペーン
        $array["STARTDUSH_CAMPAIGN_START_DATE"] = STARTDUSH_CAMPAIGN_START_DATE;
        $array["STARTDUSH_CAMPAIGN_END_DATE"] = STARTDUSH_CAMPAIGN_END_DATE;

        $array["STARTDUSH_CAMPAIGN_GET_ITEM"] = STARTDUSH_CAMPAIGN_GET_ITEM;
        $array["STARTDUSH_CAMPAIGN_GET_AMOUNT"] = STARTDUSH_CAMPAIGN_GET_AMOUNT;

        //ビットコインキャンペーンタイトル
        $array["BTC_CAMPAIGN_NAME"] = BTC_CAMPAIGN_NAME;

        //ビットコインの出金停止フラグ
        //trueにすると出金停止となる。総額を超えた時などに使用。併せて期間も短くしてBTC獲得も終了とされたい。
        $array["BTC_CAMPAIGN_PAYMENT_STOP"] = BTC_CAMPAIGN_PAYMENT_STOP;

        //ビットコインキャンペーン期間
        //これが切れるとモンスターやバトルランキング集計でBTCが手に入らなくなる。
        //表示がなくなるわけではないし、出金も可能。
        $array["BTC_CAMPAIGN_START_DATE"] = BTC_CAMPAIGN_START_DATE;
        $array["BTC_CAMPAIGN_END_DATE"] = BTC_CAMPAIGN_END_DATE;

        //ethアドレス登録可能フラグ
        $array["ETH_ADDR_OPEN"] = ETH_ADDR_OPEN;

        //第何週に開始するか。0の場合は毎週。
        $array["BATTLE_RANK_WEEK"] = BATTLE_RANK_WEEK;

        //twitterURL
        $array["TWITTER_URI"] = TWITTER_URI;


        $array["History_Log"] = array(
                "TYPE_BATTLE_CHALLENGE" => History_LogService::TYPE_BATTLE_CHALLENGE,
                "TYPE_BATTLE_DEFENCE" => History_LogService::TYPE_BATTLE_DEFENCE,
                "TYPE_CHANGE_GRADE" => History_LogService::TYPE_CHANGE_GRADE,
                "TYPE_LEVEL_UP" => History_LogService::TYPE_LEVEL_UP,
                "TYPE_EFFECT_TIMEUP" => History_LogService::TYPE_EFFECT_TIMEUP,
                "TYPE_INVITE_SUCCESS" => History_LogService::TYPE_INVITE_SUCCESS,
                "TYPE_PRESENTED" => History_LogService::TYPE_PRESENTED,
                "TYPE_QUEST_FIN" => History_LogService::TYPE_QUEST_FIN,           // 廃止
                "TYPE_ITEM_BREAK" => History_LogService::TYPE_ITEM_BREAK,
                "TYPE_ITEM_LVUP" => History_LogService::TYPE_ITEM_LVUP,
                "TYPE_WEEKLY_HIGHER" => History_LogService::TYPE_WEEKLY_HIGHER,
                "TYPE_CAPTURE" => History_LogService::TYPE_CAPTURE,
                "TYPE_ADMIRED" => History_LogService::TYPE_ADMIRED,
                "TYPE_REPLIED" => History_LogService::TYPE_REPLIED,
                "TYPE_COMMENT" => History_LogService::TYPE_COMMENT,
                "TYPE_QUEST_FIN2" => History_LogService::TYPE_QUEST_FIN2,
                "TYPE_TEAM_BATTLE" => History_LogService::TYPE_TEAM_BATTLE,
            );
        $array["Tournament_Master"] = array(
              "TOUR_MAIN" => Tournament_MasterService::TOUR_MAIN,
              "TOUR_QUEST" => Tournament_MasterService::TOUR_QUEST,
            );
        $array["Item_Master"] = array(
                'ITEM_RECV_HP' => Item_MasterService::RECV_HP,
                'ITEM_RECV_AP' => Item_MasterService::RECV_AP,
                'ITEM_INCR_PARAM' => Item_MasterService::INCR_PARAM,
                'ITEM_DECR_PARAM' => Item_MasterService::DECR_PARAM,
                'ITEM_INCR_EXP' => Item_MasterService::INCR_EXP,
                'ITEM_REPAIRE' => Item_MasterService::REPAIRE,
                'ITEM_TACT_ATT' => Item_MasterService::TACT_ATT,
                'ITEM_ATTRACT' => Item_MasterService::ATTRACT,
                'ITEM_DTECH_UPPER' => Item_MasterService::DTECH_UPPER,
                'ITEM_RECV_MP' => Item_MasterService::RECV_MP,
                'ITEM_CONTINUE_BATTLE' => Item_MasterService::CONTINUE_BATTLE,
                'ITEM_DTECH_UPPER_INVOKE' => Item_MasterService::DTECH_UPPER_INVOKE,
                'ITEM_DTECH_UPPER_POWER' => Item_MasterService::DTECH_UPPER_POWER,
                'ITEM_RARE_ENCOUNT_LV1' => Item_MasterService::RARE_ENCOUNT_LV1,
                'ITEM_RARE_ENCOUNT_LV2' => Item_MasterService::RARE_ENCOUNT_LV2,
                'ITEM_RARE_ENCOUNT_LV3' => Item_MasterService::RARE_ENCOUNT_LV3,
                'ITEM_SRARE_ENCOUNT_LV1' => Item_MasterService::SRARE_ENCOUNT_LV1,
                'ITEM_SRARE_ENCOUNT_LV2' => Item_MasterService::SRARE_ENCOUNT_LV2,
                'ITEM_SRARE_ENCOUNT_LV3' => Item_MasterService::SRARE_ENCOUNT_LV3,
                'INFINITE_DURABILITY' => Item_MasterService::INFINITE_DURABILITY,
            );
        $array["Character_Effect"] = array(
                'TYPE_EXP_INCREASE' => Character_EffectService::TYPE_EXP_INCREASE,
                'TYPE_HP_RECOVER' => Character_EffectService::TYPE_HP_RECOVER,
                'TYPE_ATTRACT' => Character_EffectService::TYPE_ATTRACT,
                'TYPE_DTECH_POWUP' => Character_EffectService::TYPE_DTECH_POWUP,
            );
        $array["Character_Info"] = array(
                'INITIAL_HP' => Character_InfoService::INITIAL_HP,
                'INITIAL_ATTACK' => Character_InfoService::INITIAL_ATTACK,
                'INITIAL_DEFENCE' => Character_InfoService::INITIAL_DEFENCE,
                'INITIAL_SPEED' => Character_InfoService::INITIAL_SPEED,
                'INITIAL_FACE' => Character_InfoService::INITIAL_FACE,
                'HP_SCALE' => Character_InfoService::HP_SCALE,
            );
        //userinfo.tutorial_stepの定数リスト
        $array["User_Info_Tutorial"] = array(
                'TUTORIAL_MORNING' => User_InfoService::TUTORIAL_MORNING,
                'TUTORIAL_MAINMENU' => User_InfoService::TUTORIAL_MAINMENU,
                'TUTORIAL_FIELD' => User_InfoService::TUTORIAL_FIELD,
                'TUTORIAL_BATTLE' => User_InfoService::TUTORIAL_BATTLE,
                'TUTORIAL_AFTERBATTLE' => User_InfoService::TUTORIAL_AFTERBATTLE,
                'TUTORIAL_STATUS' => User_InfoService::TUTORIAL_STATUS,
                'TUTORIAL_PRESHOP' => User_InfoService::TUTORIAL_PRESHOP,
                'TUTORIAL_SHOPPING' => User_InfoService::TUTORIAL_SHOPPING,
                'TUTORIAL_GACHA' => User_InfoService::TUTORIAL_GACHA,
                'TUTORIAL_RIVAL' => User_InfoService::TUTORIAL_RIVAL,
                'TUTORIAL_EQUIP' => User_InfoService::TUTORIAL_EQUIP,
                'TUTORIAL_LAST' => User_InfoService::TUTORIAL_LAST,
                'TUTORIAL_END' => User_InfoService::TUTORIAL_END,
                'TUTORIAL_MOVE' => User_InfoService::TUTORIAL_MOVE,
                'TUTORIAL_GLOBALMOVE' => User_InfoService::TUTORIAL_GLOBALMOVE,
                'TUTORIAL_FINISH' => User_InfoService::TUTORIAL_FINISH,
            );
        //Drama_MasterServiceの定数リスト
        $array["Drama_Master_Tutorial"] = array(
                'PROLOGUE' => Drama_MasterService::PROLOGUE,
                'TUTORIAL0' => Drama_MasterService::TUTORIAL0,
                'TUTORIAL30' => Drama_MasterService::TUTORIAL30,
                'TUTORIAL40' => Drama_MasterService::TUTORIAL40,
                'TUTORIAL60' => Drama_MasterService::TUTORIAL60,
                'TUTORIAL90' => Drama_MasterService::TUTORIAL90,
            );
        //Vcoin_Payment_LogServiceの定数リスト
        $array["Vcoin_Payment_Log"] = array(
                'INITIAL' => Vcoin_Payment_LogService::STATUS_INITIAL,
                'RECEIVE' => Vcoin_Payment_LogService::STATUS_RECEIVE,
                'COMPLETE' => Vcoin_Payment_LogService::STATUS_COMPLETE,
                'CANCEL' => Vcoin_Payment_LogService::STATUS_CANCEL,
            );
        //Quest_MasterServiceの定数リスト
        $array["Quest_Master"] = array(
                'EVENT_QUEST' => Quest_MasterService::EVENT_QUEST,
                'WILD_PLACE' => Quest_MasterService::WILD_PLACE,
                'MONSTER_DUNGEON' => Quest_MasterService::MONSTER_DUNGEON,
                'TEAM_BATTLE' => Quest_MasterService::TEAM_BATTLE,
            );
        //Raid_DungeonServiceの定数リスト
        $array["Raid_Dungeon"] = array(
                'READY' => Raid_DungeonService::READY,
                'START' => Raid_DungeonService::START,
                'SUCCESS' => Raid_DungeonService::SUCCESS,
                'FAILURE' => Raid_DungeonService::FAILURE,
                'REQUIRE_NONE' => Raid_DungeonService::REQUIRE_NONE,
                'REQUIRE_ETHADDR' => Raid_DungeonService::REQUIRE_ETHADDR,
            );

        $itemSvc = new Item_MasterService();
        $INVITE_BONUS = array();
        $i = 0;
        foreach(Invitation_LogService::$INVITE_BONUS as $key=>$value){
            $item = $itemSvc->getExRecord($key);

            $INVITE_BONUS[$i]["item_name"] = $item["item_name"];
            $INVITE_BONUS[$i]["count"] = $value;
            $i++;
        }

        $ANSWER_BONUS = array();
        $i = 0;
        foreach(Invitation_LogService::$ANSWER_BONUS as $key=>$value){
            $item = $itemSvc->getExRecord($key);

            $ANSWER_BONUS[$i]["item_name"] = $item["item_name"];
            $ANSWER_BONUS[$i]["count"] = $value;
            $i++;
        }

        //Invitation_LogServiceの定数リスト
        $array["Invitation_Log"] = array(
                'INVITE_BTC' => Invitation_LogService::INVITE_BTC,
                'INVITED_BTC' => Invitation_LogService::INVITED_BTC,
                'INVITE_BONUS' => $INVITE_BONUS,
                'ANSWER_BONUS' => $ANSWER_BONUS,
            );

        //ランキング景品
        $itemSvc = new Item_MasterService();
        $setSvc = new Set_MasterService();
        foreach(Ranking_LogService::$PRIZES[Ranking_LogService::GRADEPT_WEEKLY] as &$row){
            $item = $itemSvc->getExRecord($row["id"]);
            $set = $setSvc->getRecord($item["set_id"]);

            $row["item_name"] = $item["item_name"];
            $row["set_name"] = $set["set_name"];
        }

        $array["Ranking_Log_Prize_Week"] = Ranking_LogService::$PRIZES[Ranking_LogService::GRADEPT_WEEKLY];

        return $array;
    }

}
