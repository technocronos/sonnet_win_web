<?php

/**
 * ---------------------------------------------------------------------------------
 * ホームのサマリーを送信する
 * @param dataId            決済から戻ってきたときのデータID
 *        firstscene        最初に遷移したい画面
 *        his_user_id       他人のユーザーID
 * ---------------------------------------------------------------------------------
 */
class HomeSummaryApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        $array = array();

        // ステータス画面、装備の情報をセット
        $charaSvc = new Character_InfoService();
        $user_infoSvc = new User_InfoService();
        $avatar = $charaSvc->needAvatar($this->user_id, true);

        // 画像情報を取得。
        $spec1 = CharaImageUtil::getSpec($avatar);
        $path1 = sprintf('%s.%s.gif', $spec1, 'full');
        $avatar['image_url'] = $path1;

        $expInfo = $charaSvc->getExpInfo($avatar);
        $memberinfo = Service::create('User_Member')->getMemberInfo($this->user_id);
        $gradeinfo = Service::create('Grade_Master')->needRecord($avatar['grade_id']);

        //装備情報は一旦退避（配列が入れ子だとarrayToFlasmで処理できないため）
        $equip = $avatar["equip"];
        $avatar["equip"] = NULL;

        $array['chara'] = $avatar;
        $array['member'] = $memberinfo;
        $array['exp'] = $expInfo;
        $array['grade'] = $gradeinfo;

        //ユーザー名
        $array['player_name'] = Text_LogService::get($avatar['name_id']);

        // その他の値をセット
        $array['actionPt'] = (int)$this->userInfo['action_pt'];
        $array['matchPt'] = (int)$this->userInfo['match_pt'];
        $array['gold'] = (int)$this->userInfo['gold'];
        $array['vcoin'] = (float)$this->userInfo['virtual_coin'];

        $array['MaxActionPt'] = ACTION_PT_MAX;
        $array['MaxMatchPt'] = MATCH_PT_MAX;

        $array['bitcoin_show'] = VCOIN_RELEASE_FLG;

        $uitem_svc = new User_ItemService();

        //コイン数を返す。
        if(PLATFORM_TYPE == "nati"){
            $uitem = $uitem_svc->getRecordByItemId($this->user_id ,COIN_ITEM_ID);
            $array['coin'] = (int)$uitem['num'];
        }

        // 平時の行動ptが1秒でいくつ回復するか。
        $array['ACTION_PT_RECOVERY'] = ACTION_PT_RECOVERY;
        // 平時の対戦ptが1秒でいくつ回復するか。
        $array['MATCH_PT_RECOVERY'] = MATCH_PT_RECOVERY;

        //TOPページへのリンク
        $array['urlOnTop'] = Common::genContainerUrl(
            'User', 'Index', array(), true
        );

        //チュートリアルページへのリンク
        $array['urlOnTutorial'] = Common::genContainerUrl(
            'Swf', 'Tutorial', array(), true
        );

        // レコード取得。なかったらnullリターン。
        $platformUid = $user_infoSvc->getPlatformUid($this->user_id);
        $record = $user_infoSvc->getRecordByPuid($platformUid);
        if(!$record)
            return null;

        // 現在時と最終計算日時を取得。
        $now = time();
        $lastAffected = strtotime($record['last_affected']);

        // "absence_days" を計算する。
        $array['lastAffected'] = $lastAffected;

        //最初に移動しておきたいswfのｼｰﾝとラベルがあれば指定する。ショップの決算から戻った時用
        if( !empty($_GET['dataId']) ) {
            $data = Service::create('Mini_Session')->getData($_GET['dataId']);

            // 購入したアイテムの user_item レコードを取得。
            $uitem = $uitem_svc->getRecord($data['uitemId']);

            $array["buy"] = $uitem;

            $array['firstscene'] = $data["firstscene"];
            $array['label'] = $data["label"];

            $str = AppUtil::itemEffectStr($uitem);
            $array['buy_effect'] = $str;
            $array['buy_user_item_id0'] = $data["uitemId"];
            $array['buy_currency'] = 2;
            $array['buy_price'] = $data["price"];
        }else{
            $array['firstscene'] = $_GET["firstscene"];            
        }

        //他人のページ遷移が指定されている場合
        if(!empty($_GET['his_user_id'])){
            $array['his_user_id'] = $_GET["his_user_id"];
        }

        //クエスト結果遷移が指定されている場合
        if(!empty($_GET['sphereId'])){
            $array['sphereId'] = $_GET["sphereId"];
        }

        // 最も新しいお知らせを数件取得。
        $oshiraseSvc = new Oshirase_LogService();
        $array['oshiraseList'] = $oshiraseSvc->getNewestEntries();

        $array["bannar"] = array();
        $array["history"] = array();
        $array["tutorial_step"] = $this->userInfo['tutorial_step'];

        // まだチュートリアル中でないなら...
        if($this->userInfo['tutorial_step'] >= User_InfoService::TUTORIAL_END) {

            $histSvc = new History_LogService();

            // 最も適しているガチャを取得。
            $fitGacha = Service::create('Gacha_Master')->getFitGacha($this->user_id);
            $array['fitGacha'] = $fitGacha;

            // 未読メッセージ件数を取得。
            $array['unreadCount'] = Service::create('Message_Log')->getUnreadCount($this->user_id);

            // 未回答の被申請数、未確認の回答数を取得。
            $invSvc = new Approach_LogService();
            $unansweredCount = $invSvc->getUnansweredCount($this->user_id);
            $array['unanswerCount'] = $unansweredCount['receive'];
            $array['unconfirmCount'] = $invSvc->getUnconfirmedCount($this->user_id);

            // プレゼント履歴、招待成功履歴、履歴称賛、履歴レスのうち未チェックのものを取得。
            $attentions = $histSvc->checkHistory($this->user_id, array(
                History_LogService::TYPE_PRESENTED, History_LogService::TYPE_INVITE_SUCCESS,
                History_LogService::TYPE_ADMIRED, History_LogService::TYPE_REPLIED
            ));
            $array['attentions'] = $attentions;

            // 無料ガチャをまわせるかどうかを取得。
            $tryable = (int)date('Ymd') > Service::create('User_Property')->getProperty($this->user_id, 'free_gacha_date');
            $array['freeGacha'] = $tryable;

            //バナーに表示するクエストの情報を得る
            $this->getBannarQuest($array);

            //最新の友達の履歴を得る
            $this->getLatestHistoryList($array);

        }

        // すでにフィールドクエストに出ているかどうか
        if($avatar['sally_sphere']){
            $sphere = Service::create('Sphere_Info')->needCoreRecord($avatar['sally_sphere']);
            $questObj = Service::create('Quest_Master')->getRecord($sphere['quest_id']);

            //再出発のURL
            $url = Common::genContainerUrl(
                 'Swf', 'Sphere', array('id'=>$avatar['sally_sphere'], 'reopen' => 'resume'), true
            );

            $questObj["status"] = 4;
            $questObj["url"] = $url;

            $array["sally_quest"] = $questObj;

        }else{
            $array["sally_quest"] = null;
        }

        // 各メニューのURLを上書きセット
        $array['menu0Url'] = "menu";
        $array['menu1Url'] = "status";
        $array['menu2Url'] = "weapon";
        $array['menu3Url'] = "gacha";
        $array['menu4Url'] = "shop";
        $array['menu5Url'] = "zukan";
        $array['menu6Url'] = "quest";
        $array['menu7Url'] = "battle";

        // 各メニューの標準ガイドテキストをセット。
        $array['guide0'] = "ここでなんかしたり\n移動したりするのだ\nﾊﾀﾗｹなのだ";
        $array['guide1'] = "装備とかｱｲﾃﾑ買うのだ\nﾌﾞｯｼﾎｷｭｰなのだ";
        $array['guide2'] = "装備変えたりｱｲﾃﾑ\n使ったりいろいろなのだ";
        $array['guide3'] = "他のﾕｰｻﾞと対戦して\n階級あげるのだ\nバトルしちゃうのだ";

        $array['guide4'] = "ﾀｲﾄﾙ画面に戻るのだ";
        $array['guide5'] = "仲間を確認したり\n増やしたりするのだ\nｼﾞﾝﾙｲみなﾄﾓﾀﾞﾁなのだ";
        $array['guide6'] = "履歴とかﾒｯｾｰｼﾞみるのだ\nｶｺを振り返って\nﾊﾝｾｲなのだ";
        $array['guide7'] = "みんなどんな階級か\n見てやるのだ";
        $array['guide8'] = "もじょがﾃﾄﾘｱｼﾄﾘ\n教えてやるのだ\nこわがることはないのだ";

        $dt = new DateTime();
        $dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
        $currenttime = $dt->format('Y-m-d H:i:s');

        $enddate = new DateTime(BTC_CAMPAIGN_END_DATE);

//        if(strtotime(BTC_CAMPAIGN_START_DATE) <= strtotime($currenttime) && strtotime(BTC_CAMPAIGN_END_DATE) > strtotime($currenttime)){
//            $array['start_speak1'] = $enddate->format('m月d日') . "まで総額100万円！\nビットコインがゲットできるキャンペーン中なのだ。詳しくはビットコインページを見るのだ！";
//        }else{

            //イベント告知
            $eventlist = Service::create('Quest_Master')->onPlace(Quest_MasterService::EVENT_QUEST, "FLD");
            foreach($eventlist as $event){
                //開始、終了日時チェック
                if($event['start_date'] != NULL){
                    //開始日1日前
                    if(strtotime($event['start_date']) > time() && (strtotime($event['start_date']) - (60 * 60 * 24 * 1)) < time()){
                        //予告を出す
                        $array['start_speak1'] = DateTimeUtil::dateEx("d", strtotime($event['start_date'])) . "日" .DateTimeUtil::dateEx("H", strtotime($event['start_date'])). "時からイベント" . $event["quest_name"] . "が開催されるのだ。";
                        if($event["gacha_id"] != null)
                            $array['start_speak1'] .= "期間中はイベントガチャを引けるのだ！詳しくはガチャページに行くのだ。";
                    //開催中の場合
                    }else if(strtotime($event['start_date']) <= time() && strtotime($event['end_date']) > time()){
                        $array['start_speak1'] = "[" . $event["quest_name"] . "]公開中なのだ。\n" . DateTimeUtil::dateEx("d", strtotime($event['end_date'])) . "日までなのだ。";
                        if($event["gacha_id"] != null)
                            $array['start_speak1'] .= "\n期間中はイベントガチャを引くことができるのだ！いい、急ぐのだ！";
                    }
                }
            }
//        }

        $array['start_speak2'] = "自分のアイコンあたりを\nタップすると説明が移動\nするのだ";

        $array['trans_late_speak'] = "電波悪いのだ？\n読み込み遅いのだ";

        $array['battle_rank_info'] = Service::create('Ranking_Log')->getRankingStatus();

        $this->normalCustomize($array);
        $this->specialCustomize($array);
        $this->setupSpecialMessage($array);


        return $array;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 仲間の履歴を得る
     */
    private function getLatestHistoryList(&$array) {
        $count = 1;

        // 指定された履歴を取得。
        $list = Service::create('History_Log')->getFriendsHistory($this->user_id, $count, 1);

        // ユーザ名を一覧の列に追加する。
        if($list["resultset"]){
            foreach($list["resultset"] as &$row){
                $avatar = Service::create('Character_Info')->needAvatar($row["user_id"], true);
                $row["player_name"] = Text_LogService::get($avatar['name_id']);

                if($row["monster"]){
                    $row["monster"]["monster_name"] = Text_LogService::get($row["monster"]["name_id"]);
                }
            }
        }

        $array["history"] = $list["resultset"];

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * バナーに表示するクエストを決定する
     */
    private function getBannarQuest(&$array) {
        $flagSvc = new Flag_LogService();

        $avatar = Service::create('Character_Info')->needAvatar($this->user_id, true);

        //イベントクエスト
        $ev = QuestCommon::getExecutableList($this->user_id, Quest_MasterService::EVENT_QUEST);

        $eventquestlist = [];

        if($ev != null){
            foreach($ev as $list){
                if($list["type"] == "FLD")
                    $eventquestlist[] = $list;
            }
        }

//Common::varLog($eventquestlist);

        //イベントクエストを取得する
        if($eventquestlist != null){
            $bannar["quest_id"] = $eventquestlist[0]["quest_id"];
            $bannar["explain"] = "只今イベント開催中！";

        }else{
            $bannar["quest_id"] = 99999;
            //曜日クエの場合は内容を書き換える
          	$reword = FieldBattle99999Util::getRewordDay();
                $bannar["explain"] = $reword['str'];

            //曜日クエスト
            $eventquestlist = QuestCommon::getExecutableList($this->user_id, Quest_MasterService::WILD_PLACE);
        }

        foreach($eventquestlist as $eventquest){
            if($eventquest["place_id"] == Quest_MasterService::EVENT_QUEST || $eventquest["place_id"] == Quest_MasterService::WILD_PLACE){

                if($eventquest["type"] == "FLD"){
                    if(!$avatar['sally_sphere'])
                        //まだどこにも出発していない
                        $url = Common::genContainerUrl(
                             'Swf', 'Ready', array('questId'=>$eventquest['quest_id']), true
                        );
                    else if($avatar["sally_sphere"] == $eventquest['quest_id'])
                        //すでに出発している
                        $url = Common::genContainerUrl(
                             'Swf', 'Sphere', array('id'=>$avatar['sally_sphere'], 'reopen' => 'resume'), true
                        );
                    else
                        //このクエをギブアップするURL
                        $url = Common::genContainerUrl(
                             'Swf', 'Ready', array('questId'=>$eventquest['quest_id'], 'giveup' => 1), true
                        );
                }
                $eventquest["url"] = $url;
                $questlist[] = $eventquest;
            }
        }

        $bannar["quest"] = $questlist;

        $array["bannar"] = $bannar;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 標準的なカスタマイズを行う。
     */
    private function normalCustomize(&$array) {

        // 各メニューアイコンの状態を初期化。
        for($i = 0 ; $i < 9 ; $i++)
            $array["menu{$i}State"] = '';

        // アバターキャラを取得。
        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);

        // アバターキャラがフィールドに出ている場合...
        if($avatar['sally_sphere']) {

            $sphere = Service::create('Sphere_Info')->needCoreRecord($avatar['sally_sphere']);

            // それが自分のスフィアなら...
            if($sphere['user_id'] == $this->user_id) {
                $array['menu6State'] = 'hot';
                $array['guide6'] = "ｸｴｽﾄ再開するのだ\nｷﾞﾌﾞｱｯﾌﾟも\nできるのだ";
            }
        }

        // ステータスptある場合。
        if($avatar['param_seed'] > 0) {
            $array['menu1State'] = 'hot';
            $array['start_speak1'] = "ｽﾃｰﾀｽptあるのだ\nそのままじゃｲﾐないのだ\nﾌﾘﾜｹするのだ";
        }

        if($array["unconfirmCount"] > 0){
            $array['menu1State'] = 'hot';
            $array['start_speak1'] = "仲間申請の返事があるのだ\nマイページに行くのだ";
        }

        if($array["unreadCount"] > 0){
            $array['menu1State'] = 'hot';
            $array['start_speak1'] = "未読メッセージがあるのだ\nマイページに行くのだ"; 
        }

        if($array["unanswerCount"] > 0){
            $array['menu1State'] = 'hot';
            $array['start_speak1'] = "未回答の仲間申請があるのだ\nマイページに行くのだ"; 
        }

        //フッターは通常はホーム選択
        $array['selectmenu'] = $array['menu0Url'];

        //課金から戻った場合はショップ
        if( !empty($_GET['dataId']) ) {
            $array['selectmenu'] = $array['menu4Url'];
        }

        //他人のページの場合はフッターのマイページは他人のページにする
        if( $_GET['firstscene'] == "his_page" ) {
            $array['menu1State'] = 'hispageselect';
            $array['menu1Url'] = 'his_page';
        }
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 特殊なセリフのセットアップを行う。
     */
    private function setupSpecialMessage(&$array) {

        // 今のとこ、利用予定ナシ。
/*
        $array['special'] = array(
            "とってもスペシャルな\nお知らせなのだ",
            "まだまだ\nお知らせなのだ",
            "まだまだまだまだ\nお知らせなのだ",
            "こんなもんで勘弁してやるのだ",
            );

        $array['specialNum'] = count($array['special']);
*/
        $array['special'] = array();
        $array['specialNum'] = 0;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * チュートリアル等によるカスタマイズを行う。
     */
    private function specialCustomize(&$array) {

        $opening = array();
        $lockdown = array();

        // チュートリアル中の場合。
        if($this->userInfo['tutorial_step'] < User_InfoService::TUTORIAL_END) {

            switch($this->userInfo['tutorial_step']) {

                // メインメニュー案内
                case User_InfoService::TUTORIAL_MAINMENU:
                    $opening = array(
                        "だ～れがクソ生意気なミドリ猫\nなのだ",
                        "そもそももじょは猫ではないのだ\nまったく…",
                        "ココがソネットメニューなのだ",
                        "行きたい所をタップすればいいのだ",
                        "とりあえず､いまはクエストを選ぶのだ\n他のは後なのだ",
                    );

                    $array['menu6State'] = 'hot';

                    $array["end_function"] = "HomeDisplay.tutorial_mainmenu_navi_speak_end";

                    //クライアントのタイミングでアンロック解除
                    $lockdown = array(1, 2, 3, 4, 5, 6, 7, 8);
                    break;

                // ファーストクエスト中
                case User_InfoService::TUTORIAL_FIELD:
                    $opening = array(
                        "ﾌﾞﾗﾌﾞﾗしてないで\n精霊の洞窟ｸﾘｱするのだ",
                        "再開するにはクエストにもっかい行くか下の実行ボタンから再開できるのだ",
                        "一番奥まで行けば\nいいのだ",
                    );

                    $array['menu6State'] = 'hot';
                    $lockdown = array(1, 2, 3, 4, 5, 7, 8);
                    break;

                // ステータス案内(廃止)
                case User_InfoService::TUTORIAL_STATUS:
                    $opening = array(
                        "もじょは忙しいのだ\nﾌﾞﾗﾌﾞﾗしたり…\n飲んだり…",
                        "……これからｽﾃｰﾀｽ\n見に行くのだ\nおまえも来るのだ",
                        "｢ｽﾃｰﾀｽ｣を選択\nするのだ",
                    );
                    $array['menu1State'] = 'hot';
                    $lockdown = array(0, 3, 4, 5, 6, 7);
                    break;

                // ショップ案内
                case User_InfoService::TUTORIAL_SHOPPING:
                    $opening = array(
                        "ショップを選択して\nくすりびん買うのだ!",
                    );
                    $array['menu4State'] = 'hot';

                    $lockdown = array(0, 1, 2, 3, 5, 6, 7);

                    //ショップ選択
                    $array['selectmenu'] = $array['menu4Url'];
                    break;

                // ガチャ案内
                case User_InfoService::TUTORIAL_GACHA:
                    $opening = array(
                        "お次はガチャを案内するのだ",
                        "下のメニューからガチャを選択するのだ",
                    );
                    $array['menu3State'] = 'hot';

                    $lockdown = array(1, 2, 4, 5, 6, 7, 8);

                    //ガチャ選択
                    $array['selectmenu'] = $array['menu0Url'];
                    break;

                // 対戦案内(廃止)
                case User_InfoService::TUTORIAL_RIVAL:
                    $opening = array(
                        "対戦するときは\n｢対戦｣を選択するのだ",
                        "他のﾕｰｻﾞのﾍﾟｰｼﾞにも\n行けるのだ",
                    );
                    $array['menu3State'] = 'hot';
                    //対戦選択
                    $array['selectmenu'] = $array['menu8Url'];
                    $lockdown = array(1, 2, 3, 4, 5, 6, 7);
                    break;

                // 装備案内
                case User_InfoService::TUTORIAL_EQUIP:
                    $opening = array(
                        "いいもん出たのだ？\nでもゲットしても装備しないと意味\nないのだ",
                        "装備はマイページでできるのだ\nさっそく行ってみるのだ",
                    );
                    $array['menu1State'] = 'hot';

                    //ホーム選択
                    $array['selectmenu'] = $array['menu0Url'];
                    $lockdown = array(2, 3, 4, 5, 6, 7, 8);
                    break;

            }

        // それ以外ではいまのとこナシ。
        }else {
            $opening = array();
            $lockdown = array();
        }

        // オープニングメッセージをSWFにセット。
        $array['opening'] = $opening;
        $array['openingNum'] = count($opening);

        // ロックダウンするメニューを反映。
        foreach($lockdown as $index)
            $array["menu{$index}State"] = 'disable';
    }

}
