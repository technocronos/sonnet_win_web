<?php

/**
 * メインフラッシュを構成するアクション。
 * ナビのセリフの表示幅は半角22文字分、高さは3行であることに留意。
 */
class MainAction extends SwfBaseAction {

    public $PexPartialDraw = 'true';

    const kaisyuu = 1;

    protected function doExecute() {
        if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone"){
            // ユーザが以下のチュートリアルステップである場合はTutorialアクションに飛ばす。
            switch($this->userInfo['tutorial_step']) {
                case User_InfoService::TUTORIAL_MORNING:        // オープニング
                case User_InfoService::TUTORIAL_BATTLE:         // バトルチュートリアル
                case User_InfoService::TUTORIAL_AFTERBATTLE:    // バトル後
                case User_InfoService::TUTORIAL_PRESHOP:        // ショップ前
                case User_InfoService::TUTORIAL_LAST:           // チュートリアル完了直後
                    Common::redirect('Swf', 'Tutorial');
            }
        }else{

            // ユーザが以下のチュートリアルステップである場合はTutorialアクションに飛ばす。
            switch($this->userInfo['tutorial_step']) {
                case User_InfoService::TUTORIAL_MORNING:        // オープニング
                case User_InfoService::TUTORIAL_BATTLE:         // バトルチュートリアル
                case User_InfoService::TUTORIAL_AFTERBATTLE:    // バトル後
                case User_InfoService::TUTORIAL_PRESHOP:        // ショップ前
                case User_InfoService::TUTORIAL_GACHA:          // ガチャ
                case User_InfoService::TUTORIAL_LAST:           // チュートリアル完了直後
                    Common::redirect('Swf', 'Tutorial');
            }
        }

        $this->processSummerCampaign();

        // 各メニューのURLをセット。
        $this->replaceStrings['menu0Url'] = Common::genContainerUrl('User', 'QuestList', array(), true);
        $this->replaceStrings['menu1Url'] = Common::genContainerUrl('User', 'Shop', array(), true);
        $this->replaceStrings['menu2Url'] = Common::genContainerUrl('User', 'Status', array(), true);
        $this->replaceStrings['menu3Url'] = Common::genContainerUrl('User', 'RivalList', array(), true);
        $this->replaceStrings['menu4Url'] = Common::genContainerUrl('User', 'MemberList', array(), true);
        $this->replaceStrings['menu5Url'] = Common::genContainerUrl('User', 'Information', array(), true);
        $this->replaceStrings['menu6Url'] = Common::genContainerUrl('User', 'GradeList', array(), true);
        $this->replaceStrings['menu7Url'] = Common::genContainerUrl('User', 'Help', array(), true);
        $this->replaceStrings['menu8Url'] = Common::genContainerUrl('User', 'Index', array(), true);

        // 各メニューアイコンの状態を初期化。
        for($i = 0 ; $i < 9 ; $i++)
            $this->replaceStrings["menu{$i}State"] = '';

        // 各メニューの標準ガイドテキストをセット。
        $this->replaceStrings['guide0'] = "ここでなんかしたり\n移動したりするのだ\nﾊﾀﾗｹなのだ";
        $this->replaceStrings['guide1'] = "装備とかアイテム買うのだ\nブッシホキューなのだ";
        $this->replaceStrings['guide2'] = "装備変えたりアイテム\n使ったりいろいろなのだ";
        if(Common::getCarrier() != "android")
            $this->replaceStrings['guide3'] = "他のユーザと対戦して\n階級あげるのだ\nしちゃうのだ";
        else
            $this->replaceStrings['guide3'] = "他のユーザと対戦して\n階級あげるのだ\nバトルしちゃうのだ";

        if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone"){
            $this->replaceStrings['guide4'] = "仲間を確認したり\n増やしたりするのだ\nジンルイみなトモダチなのだ";
            $this->replaceStrings['guide5'] = "履歴とかメッセージみるのだ\nカコを振り返って\nハンセイなのだ";
            $this->replaceStrings['guide6'] = "みんなどんな階級か\n見てやるのだ";
            $this->replaceStrings['guide7'] = "もじょがテトリアシトリ\n教えてやるのだ\nこわがることはないのだ";
            $this->replaceStrings['guide8'] = "タイトル画面に戻るのだ";
        }else{
            $this->replaceStrings['guide4'] = "タイトル画面に戻るのだ";
            $this->replaceStrings['guide5'] = "仲間を確認したり\n増やしたりするのだ\nジンルイみなトモダチなのだ";
            $this->replaceStrings['guide6'] = "履歴とかメッセージみるのだ\nカコを振り返って\nハンセイなのだ";
            $this->replaceStrings['guide7'] = "みんなどんな階級か\n見てやるのだ";
            $this->replaceStrings['guide8'] = "もじょがテトリアシトリ\n教えてやるのだ\nこわがることはないのだ";
        }

        $this->replaceStrings['STR_ACTION_PT'] = "行動pt";
        if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone")
            $this->replaceStrings['start_speak1'] = "なんかボタン押すのだ";
        else
            $this->replaceStrings['start_speak1'] = "どっかタップするのだ";
            
        if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone")
            $this->replaceStrings['start_speak2'] = "実行したいメニューを\n選ぶのだ。";
        else
            $this->replaceStrings['start_speak2'] = "自分のアイコンあたりを\nタップすると説明が移動\nするのだ";

        $this->replaceStrings['trans_late_speak'] = "電波悪いのだ？\n読み込み遅いのだ";

        // 各メニューの名称
        $this->replaceStrings['CAPTION0'] = "クエスト";
        $this->replaceStrings['CAPTION1'] = "ショップ";
        $this->replaceStrings['CAPTION2'] = "ステータス";
        $this->replaceStrings['CAPTION3'] = "対戦";
        $this->replaceStrings['CAPTION4'] = "仲間";
        $this->replaceStrings['CAPTION5'] = "インフォメーション";
        $this->replaceStrings['CAPTION6'] = "階級表";
        $this->replaceStrings['CAPTION7'] = "ヘルプ";
        $this->replaceStrings['CAPTION8'] = "ソネットトップ";

        // 標準的なカスタマイズ。
        $this->normalCustomize();

        //ホームで使う情報をセットする
        $this->HomeInfo();
        //ステータス情報をセットする
        $this->StatusInfo();
        //ショップ情報をセットする。
        $this->ShopInfo();

        if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone"){

            //flashはいらない
            $this->skip_flasm = true;


            //武器、アイテム情報をセットする
            $this->WeaponInfo();
            //クエスト情報をセットする
            $this->QuestInfo();

            //ライバル情報をセットする。
            $this->RivalListInfo();
            //モンスター図鑑情報をセットする
            $this->MonsterInfo();

            $charaSvc = new Character_InfoService();
            $avatar = $charaSvc->needAvatar($this->user_id, true);

            //プリロードしておく画像リスト
            $filepath = MO_HTDOCS . "/img/parts/sp/preload/";
            $preload_tmp = scandir($filepath);
            foreach($preload_tmp as $img){
                $ext = pathinfo($filepath . "/" . $img, PATHINFO_EXTENSION);

                if($ext == "png" || $ext == "gif" || $ext == "jpeg" || $ext == "jpg" ){
                    $this->img_list[basename($img, "." . $ext)] = "img/parts/sp/preload/" . $img;
                }
            }

            $this->setAttribute('img_list', $this->img_list);

            //この画面で使用されるHTMLリスト
            $filepath = MO_HTDOCS . "/html";
            $html_tmp = scandir($filepath);

            foreach($html_tmp as $html){
                $ext = pathinfo($filepath . "/" . $html, PATHINFO_EXTENSION);

                if($ext == "html" || $ext == "htm" ){
                    if(Common::isTablet() == "tablet" && file_exists($filepath . "/tablet/" . $html)){
                        $this->html_list[basename($html, ".html")] = $filepath . "/tablet/" . $html;
                    }else{
                        $this->html_list[basename($html, ".html")] = $filepath . "/" . $html;
                    }
                }
            }

            $this->setAttribute('html_list', $this->html_list);

            //この画面で使用されるJSをインクルードしないHTMLリスト
            $filepath = MO_HTDOCS . "/html/no_include_file";
            $no_inc_html_tmp = scandir($filepath);

            foreach($no_inc_html_tmp as $html){
                $ext = pathinfo($filepath . "/" . $html, PATHINFO_EXTENSION);

                if($ext == "html" || $ext == "htm" ){
                    $no_inc_html_list[basename($html, ".html")] = $filepath . "/" . $html;
                }
            }

            $this->setAttribute('no_inc_html_list', $no_inc_html_list);

            //この画面で使用されるcanvasリスト
            $filepath = MO_HTDOCS . "/js/canvas";
            $html_tmp = scandir($filepath);

            foreach($html_tmp as $html){
                $ext = pathinfo($filepath . "/" . $html, PATHINFO_EXTENSION);

                if($ext == "js"){
                    $canvas_list[basename($html, ".js")] = $filepath . "/" . $html;
                }
            }

            $this->setAttribute('canvas_list', $canvas_list);

            $avatar['gold'] = (int)$this->userInfo['gold'];

            // 双方の画像情報を取得。
            $spec1 = CharaImageUtil::getSpec($avatar);
            $path1 = sprintf('%s.%s.gif', $spec1, 'full');
            $this->replaceStrings['imageUrlP'] = $path1;

            $avatar['image_url'] = $path1;

            //自キャラのリスト
            $this->setAttribute('chara', $avatar);

            //初期化
            $this->replaceStrings['firstscene'] = "";
            $this->replaceStrings['label'] = "";
            $this->replaceStrings['buy_effect'] = "";
            $this->replaceStrings['buy_user_item_id0'] = "";
            $this->replaceStrings['buy_currency'] = "";
            $this->replaceStrings['buy_price'] = "";
            $this->arrayToFlasm('buy_', array());

            //最初に移動しておきたいswfのｼｰﾝとラベルがあれば指定する。ショップの決算から戻った時用
            if( !empty($_GET['dataId']) ) {
                $this->setAttribute('dataId', $_GET['dataId']);
            }else if(!empty($_GET['firstscene'])){
                $this->replaceStrings['firstscene'] = $_GET["firstscene"];
            }

            $this->setAttribute('firstscene', $this->replaceStrings['firstscene']);

            //他人のページ遷移が指定されている場合
            if(!empty($_GET['his_user_id'])){
                $this->replaceStrings['his_user_id'] = $_GET["his_user_id"];
            }

            //他人のページ遷移が指定されている場合
            if(!empty($_GET['sphereId'])){
                $this->setAttribute('sphereId', $_GET["sphereId"]);
            }

            $this->setAttribute('his_user_id', $_GET["his_user_id"]);


            $itemSvc = new Item_MasterService();

            // 招待用URLを取得。
            $this->setAttribute('invite_url', PlatformApi::getInvitationUrl(array(
                'finish' =>  Common::genURL('User', 'Help', array('id'=>'invite', 'backto'=>$_GET['backto']), true),
                'subject' => SITE_NAME."友だち招待",
                'body' =>    SITE_NAME."で一緒に遊ぼう!",
            )));
            $this->setAttribute('PLATFORM_TYPE', PLATFORM_TYPE);

            // 特典のアイテムIDをテンプレートで使えるようにする。
            $this->setAttribute( 'ibonus', $itemSvc->getRecordsIn(array_keys(Invitation_LogService::$INVITE_BONUS)) );

            // 特典のアイテムIDをテンプレートで使えるようにする。
            $this->setAttribute( 'abonus', $itemSvc->getRecordsIn(array_keys(Invitation_LogService::$ANSWER_BONUS)) );

        }else{
            // ユーザの現在地点の地点を取得。
            $placeSvc = new Place_MasterService();
            $currentPlace = $placeSvc->needRecord($this->userInfo['place_id']);

            //場所を渡す
            $this->replaceStrings['place_caption'] = $currentPlace['place_name'];


            // 背景差し替え
            $this->replaceImages[1] = Place_MasterService::getBgImage($this->userInfo['place_id']);

            //キャラグラのイメージID
            $image_id = 11;

            // キャラグラ差し替え。
            $chara = Service::create('Character_Info')->needAvatar($this->user_id, true);

            $this->replaceImages[$image_id] = CharaImageUtil::getImageFromChara($chara, 'swf');

            // 特殊なセリフがある場合はそれを取得。
            $this->setupSpecialMessage();

            // 特殊なセリフがある場合はそれを取得。
            $this->setupNaviTouchMessage();

            // チュートリアル等でカスタマイズの必要がある場合は行う。
            $this->specialCustomize();
        }

        //サウンド設定。
        $this->use_web_audio = array(
            "se_btn",
            "se_hover",
            "se_congrats",
        );
        $this->use_audio_tag = array(
            "bgm_menu",
        );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ホームの表示を行う。
     */
    private function HomeInfo() {
        // ステータス画面、装備の情報をセット
        $charaSvc = new Character_InfoService();
        $avatar = $charaSvc->needAvatar($this->user_id, true);
        $expInfo = $charaSvc->getExpInfo($avatar);
        $memberinfo = Service::create('User_Member')->getMemberInfo($this->user_id);
        $gradeinfo = Service::create('Grade_Master')->needRecord($avatar['grade_id']);

        //装備情報は一旦退避（配列が入れ子だとarrayToFlasmで処理できないため）
        $equip = $avatar["equip"];
        $avatar["equip"] = NULL;

        $this->arrayToFlasm('chara_', $avatar);
        $this->arrayToFlasm('member_', $memberinfo);
        $this->arrayToFlasm('exp_', $expInfo);
        $this->arrayToFlasm('grade_', $gradeinfo);

        //ステータス画面
        $this->replaceStrings['player_name'] = Text_LogService::get($avatar['name_id']);

        // その他の値をセット
        $this->replaceStrings['actionPt'] = (int)$this->userInfo['action_pt'];
        $this->replaceStrings['matchPt'] = (int)$this->userInfo['match_pt'];
        $this->replaceStrings['gold'] = (int)$this->userInfo['gold'];
        $this->replaceStrings['MaxActionPt'] = ACTION_PT_MAX;
        $this->replaceStrings['MaxMatchPt'] = MATCH_PT_MAX;

        // 平時の行動ptが1秒でいくつ回復するか。
        $this->replaceStrings['ACTION_PT_RECOVERY'] = ACTION_PT_RECOVERY;
        // 平時の対戦ptが1秒でいくつ回復するか。
        $this->replaceStrings['MATCH_PT_RECOVERY'] = MATCH_PT_RECOVERY;

        //TOPページへのリンク
        $this->replaceStrings['urlOnTop'] = Common::genContainerUrl(
            'User', 'Index', array(), true
        );

        $user_infoSvc = new User_InfoService();

        // レコード取得。なかったらnullリターン。
        $platformUid = $user_infoSvc->getPlatformUid($this->user_id);
        $record = $user_infoSvc->getRecordByPuid($platformUid);
        if(!$record)
            return null;

        // 現在時と最終計算日時を取得。
        $now = time();
        $lastAffected = strtotime($record['last_affected']);

        $this->replaceStrings['lastAffected'] = $lastAffected;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * マイページ画面の表示を行う。
     */
    private function StatusInfo() {
        // ステータス画面、装備の情報をセット
        $charaSvc = new Character_InfoService();
        $avatar = $charaSvc->needAvatar($this->user_id, true);

        //ステータス振り分けのURL
        $this->replaceStrings['urlOnParamUp'] = Common::genContainerUrl(
            'Swf', 'ParamUp', array('charaId'=>$avatar['character_id']), true
        );
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 装備、アイテム画面の表示を行う。
     */
    private function WeaponInfo() {
        $charaSvc = new Character_InfoService();
        $avatar = $charaSvc->needAvatar($this->user_id, true);

        //アイテム使用のURL
        $this->replaceStrings['urlOnItemUseFire'] = Common::genContainerUrl(
            'Swf', 'ItemUseFire', array(), true
        );
        //アイテム廃棄のURL
        $this->replaceStrings['urlOnDiscard'] = Common::genContainerUrl(
            'Swf', 'Discard', array(), true
        );

        //合成計算のための重みテーブルをswfに渡しておく
        $eqpSvc = new Equippable_MasterService();
        $this->arrayToFlasm('rear_weight_table', $eqpSvc->rear_weight_table);

        //item_masterの定数
        $this->replaceStrings['ITEM_RECV_HP'] = Item_MasterService::RECV_HP;
        $this->replaceStrings['ITEM_RECV_AP'] = Item_MasterService::RECV_AP;
        $this->replaceStrings['ITEM_INCR_PARAM'] = Item_MasterService::INCR_PARAM;
        $this->replaceStrings['ITEM_DECR_PARAM'] = Item_MasterService::DECR_PARAM;
        $this->replaceStrings['ITEM_INCR_EXP'] = Item_MasterService::INCR_EXP;
        $this->replaceStrings['ITEM_REPAIRE'] = Item_MasterService::REPAIRE;
        $this->replaceStrings['ITEM_TACT_ATT'] = Item_MasterService::TACT_ATT;
        $this->replaceStrings['ITEM_ATTRACT'] = Item_MasterService::ATTRACT;
        $this->replaceStrings['ITEM_DTECH_UPPER'] = Item_MasterService::DTECH_UPPER;
        $this->replaceStrings['ITEM_RECV_MP'] = Item_MasterService::RECV_MP;
        $this->replaceStrings['ITEM_CONTINUE_BATTLE'] = Item_MasterService::CONTINUE_BATTLE;

        $this->replaceStrings['TYPE_EXP_INCREASE'] = Character_EffectService::TYPE_EXP_INCREASE;
        $this->replaceStrings['TYPE_HP_RECOVER'] = Character_EffectService::TYPE_HP_RECOVER;
        $this->replaceStrings['TYPE_ATTRACT'] = Character_EffectService::TYPE_ATTRACT;
        $this->replaceStrings['TYPE_DTECH_POWUP'] = Character_EffectService::TYPE_DTECH_POWUP;

    }
    //-----------------------------------------------------------------------------------------------------
    /**
     * ショップ一覧の表示を行う。
     */
    private function ShopInfo() {
        //購入のURL
        $this->replaceStrings['urlOnShop'] = Common::genContainerUrl(
            'Swf', 'ShopApi', array(), true
        );
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * モンスター図鑑一覧の表示を行う。
     */
    private function MonsterInfo() {
        // キャプチャ率を取得。
        $this->setAttribute('monster_capture', Service::create('User_Monster')->getCaptureCount($this->user_id));
        $this->setAttribute('monster_count', Service::create('Monster_Master')->getMonsterCount());
    }
    //-----------------------------------------------------------------------------------------------------
    /**
     * クエスト一覧とマップの表示を行う。
     */
    private function QuestInfo() {
        $placeSvc = new Place_MasterService();

        // ユーザの現在地点の地点を取得。
        $currentPlace = $placeSvc->needRecord($this->userInfo['place_id']);

        $region_id = $currentPlace['region_id'];
        $this->replaceStrings['currRegion'] = $region_id ;

        // すでにフィールドクエストに出ているかどうか
        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);
        if($avatar['sally_sphere']){
            $sphere = Service::create('Sphere_Info')->needCoreRecord($avatar['sally_sphere']);
            $questObj = Service::create('Quest_Master')->getRecord($sphere['quest_id']);

            //再出発のURL
            $url = Common::genContainerUrl(
                 'Swf', 'Sphere', array('id'=>$avatar['sally_sphere'], 'reopen' => 'resume'), true
            );

            $this->replaceStrings["sally_quest_id"] = $sphere['quest_id'];
            $this->replaceStrings["sally_quest_name"] = $questObj["quest_name"];
            $this->replaceStrings["sally_quest_text"] = $questObj["flavor_text"];
            $this->replaceStrings["sally_quest_type"] = $questObj["type"];
            $this->replaceStrings["sally_quest_consume_pt"] = $questObj["consume_pt"];
            $this->replaceStrings["sally_quest_status"] = 4; //実行中なんだから確実に4
            $this->replaceStrings["sally_quest_url"] = $url;

            //ギブアップ完了後の結果画面URL
            $this->replaceStrings['urlOnFieldEnd'] = Common::genContainerUrl(
                'Swf', 'FieldEnd', array('sphereId'=>$avatar['sally_sphere']), true
            );

        }else{
            $this->replaceStrings["sally_quest_id"] = "";
        }

        // 移動可能な箇所がある地域の一覧を取得。
        $regions = $placeSvc->getMovableRegions($this->user_id);

        //マップ、クエストの実行可能なリストをすべて取得する。
        $g_points= array();
        $points_string = "";
        $quest_string = "";
        $questnum_string = "";

        foreach($regions as $reg) {

            // 指定の地域の、移動可能なポイント一覧を取得。
            $points = $this->getPoints($reg["place_id"]);

            if($reg["place_id"] == $currentPlace['region_id']){
                // 現在地点の番号。
                $this->replaceStrings['currPlace'] = $this->searchPointNo($points, $reg["place_id"] , $this->userInfo['place_id']);
            }

            // ポイント一覧をSWFに渡す。
            $this->arrayToFlasm('place', $points);
            //セットしたものを連結して保存しておく
            $points_string .= $this->replaceStrings["place"];

            $this->replaceStrings['placeNum' . $reg["place_id"]] = max(array_keys($points));

            // 指定の地域の、移動可能なクエスト一覧を取得。
            $quests = $this->getQuests($points, $reg["place_id"]);
            //セットしたものを連結して保存しておく
            $quest_string .= $this->replaceStrings["quest"];
            $questnum_string .= $this->replaceStrings["questNum"];

            //グローバルマップ
            $g_points[$reg["place_id"]] = array(
                'X0' => $reg["map_x"],
                'Y0' => $reg["map_y"],
                'Name0' => $reg['place_name'],
                'Id0' => $reg['place_id'],
            );

        }

        $this->GachaInfo();

        //グローバルマップをSWFに渡すために連結する
        $this->arrayToFlasm('place', $g_points);
        //セットしたものを連結して保存しておく
        $points_string = $points_string . $this->replaceStrings["place"];
        $this->replaceStrings['placeNum0'] = max(array_keys($g_points));

        //すべての情報を再セット
        $this->replaceStrings["place"] = $points_string;
        $this->replaceStrings["quest"] = $quest_string;
        $this->replaceStrings["questNum"] = $questnum_string;

        // グローバルマップのURLとキャンセル時のURLをセット
        $this->replaceStrings['globalUrl'] = Common::genContainerUrl('Swf', 'Move', array('region'=>'0'), true);
        $this->replaceStrings['cancelUrl'] = Common::genContainerUrl('Swf', 'Main', null, true);

        // 初期表示マップの地域名。
        $region = $placeSvc->needRecord($region_id);
        $this->replaceStrings['regionName'] = $region['place_name'];

        // 決定動作などの送信先URLを渡す。
        $this->replaceStrings['decideUrl'] = Common::genContainerUrl('Swf', 'Move', null, true);

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定の地域の、実行可能なクエスト一覧を返す。
     *
     * @param int       地域ID
     * @return array    SWFに渡すポイント一覧
     */
    private function getQuests($points, $region_id) {

        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);
        if($avatar['sally_sphere'])
            $sphere = Service::create('Sphere_Info')->needCoreRecord($avatar['sally_sphere']);

        $quests = array();
        $quest_num = array();

//Common::varLog($addquestlist);

        foreach($points as $key => $point) {
            //クエストリスト取得
            $questlist = QuestCommon::getExecutableList($this->user_id, $point['Id' . $region_id]);

            //地点無しクエスト
            $wildquestlist = QuestCommon::getExecutableList($this->user_id, Quest_MasterService::WILD_PLACE);
            $questlist[] = $wildquestlist[0];

            //イベントクエスト
            $eventquestlist = QuestCommon::getExecutableList($this->user_id, Quest_MasterService::EVENT_QUEST);

            foreach($eventquestlist as $eventquest){
                if($eventquest["type"] == "FLD")
                    $questlist[] = $eventquest;
            }

//Common::varLog($questlist);
            $numcount = 0;

            foreach($questlist as $quest) {
                if($quest["type"] == "FLD"){
                    if(!$avatar['sally_sphere'])
                        //まだどこにも出発していない
                        $url = Common::genContainerUrl(
                             'Swf', 'Ready', array('questId'=>$quest['quest_id'], 'placeId' => $point['Id' . $region_id]), true
                        );
                    else if($sphere["quest_id"] == $quest['quest_id'])
                        //すでに出発している
                        $url = Common::genContainerUrl(
                             'Swf', 'Sphere', array('id'=>$avatar['sally_sphere'], 'reopen' => 'resume'), true
                        );
                    else
                        //このクエをギブアップするURL
                        $url = Common::genContainerUrl(
                             'Swf', 'Ready', array('questId'=>$quest['quest_id'], 'giveup' => 1), true
                        );

                }else{
                    $url = Common::genContainerUrl(
                         'Swf', 'QuestDrama', array('questId'=>$quest['quest_id'], 'placeId' => $point['Id' . $region_id]), true
                    );
                }

                $quests = $quests + array(
                    $numcount . 'Id_' . $key . "_" . $region_id => $quest['quest_id'],
                    $numcount . 'Title_' . $key . "_" . $region_id => $quest['quest_name'],
                    $numcount . 'Text_' . $key . "_" . $region_id => $quest['flavor_text'],
                    $numcount . 'URL_' . $key . "_" . $region_id => $url,
                    $numcount . 'Type_' . $key . "_" . $region_id => $quest['type'],
                    $numcount . 'Status_' . $key . "_" . $region_id => $quest['status'],
                    $numcount . 'Place_' . $key . "_" . $region_id => (int)$quest['place_id'],
                    $numcount . 'ConsumePt_' . $key . "_" . $region_id => (int)$quest['consume_pt'],
                );

                $numcount++;
            }
            $quest_num = $quest_num + array("_" . $key . "_" . $region_id => $numcount);
        }

//Common::varDump($quests);
        // クエスト一覧とクエスト数をSWFに渡す。
        $this->arrayToFlasm('quest', $quests);
        $this->arrayToFlasm('questNum',  $quest_num );

        return $quests;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定の地域の、移動可能なポイント一覧を返す。
     *
     * @param int       地域ID
     * @return array    SWFに渡すポイント一覧
     */
    private function getPoints($regionId) {

        $placeSvc = new Place_MasterService();

        // 指定のマップで移動可能なポイントの一覧を取得。
        $places = $placeSvc->getMovablePlaces($this->user_id, $regionId);

        // 移動可能な地点の一覧をSWFに伝達するポイント一覧に変換する。
        $points = array();
        foreach($places as $place) {
            $points[] = array(
                'X' . $regionId => $place['map_x'],
                'Y' . $regionId => $place['map_y'],
                'Name' . $regionId => $place['place_name'],
                'Id' . $regionId => $place['place_id'],
            );
        }

        // リターン
        return $points;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたポイント一覧から、指定のIDを持つものを返す。
     *
     * @param array     ポイントデータの配列。
     * @param int       探すポイントのID
     * @return int      見つけたポイントのインデックス値。見付からなかった場合は -1。
     */
    private function searchPointNo($points, $region_id ,$searchId) {

        foreach($points as $no => $point) {
            if($point['Id' . $region_id] == $searchId)
                return $no;
        }

        return -1;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ガチャ一覧の表示を行う。
     */
    private function GachaInfo() {
        $gachaSvc = new Gacha_MasterService();

        // 以下、ガチャの内容を表示する場合。

        // ガチャの一覧を取得。
        $gachaSvc = new Gacha_MasterService();
        $gacha = $gachaSvc->getGachaList($this->user_id, 10000, 0);

        $this->arrayToFlasm('gacha_', $gacha["resultset"]);
        $this->replaceStrings['gacha_num'] = count($gacha["resultset"]);

        // 共通フリーチケットの数を数える。
        $count = Service::create('User_Item')->getHoldCount($this->user_id, Gacha_MasterService::FREETICKET_ID);
        $this->replaceStrings['ticketCount'] = $count;

        // 無料ガチャをまわせるかどうかを取得。
        $tryable = (int)date('Ymd') > Service::create('User_Property')->getProperty($this->user_id, 'free_gacha_date');
        $this->replaceStrings['freeGacha'] = $tryable;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * バトル一覧の表示を行う。
     */
    private function RivalListInfo() {
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 標準的なカスタマイズを行う。
     */
    private function normalCustomize() {

        // アバターキャラを取得。
        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);

        // アバターキャラがフィールドに出ている場合...
        if($avatar['sally_sphere']) {

            $sphere = Service::create('Sphere_Info')->needCoreRecord($avatar['sally_sphere']);

            // それが自分のスフィアなら...
            if($sphere['user_id'] == $this->user_id) {
                //$this->replaceStrings['menu0State'] = 'hot';
                //$this->replaceStrings['menu0Url'] = Common::genContainerUrl('User', 'FieldReopen', null, true);
                //$this->replaceStrings['guide0'] = "クエスト再開するのだ\nｷﾞﾌﾞｱｯﾌﾟも\nできるのだ";

            // 援護しているなら...
            }else {
                //$this->replaceStrings['menu0State'] = 'disable';
                //$this->replaceStrings['guide0'] = "援護中なのだ\n撤退したいなら\n｢ステータス｣なのだ";
            }
        }

        // ステータスptある場合。
        if($avatar['param_seed'] > 0) {
            $this->replaceStrings['menu1State'] = 'hot';
            $this->replaceStrings['guide1'] = "ｽﾃｰﾀｽptあるのだ\nそのままじゃｲﾐないのだ\nﾌﾘﾜｹするのだ";
        }

        //通常はクエスト選択
        $this->replaceStrings['selectmenu'] = 0;


    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 特殊なセリフのセットアップを行う。
     */
    private function setupSpecialMessage() {

        // 今のとこ、利用予定ナシ。
/*
        $this->arrayToFlasm('special', array(
            "とってもスペシャルな\nお知らせなのだ",
            "まだまだ\nお知らせなのだ",
            "まだまだまだまだ\nお知らせなのだ",
            "こんなもんで勘弁してやるのだ",
            )
        );
        $this->replaceStrings['specialNum'] = 4;
*/

        $this->arrayToFlasm('special', array());
        $this->replaceStrings['specialNum'] = 0;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * ナビにタッチした時の特殊なセリフのセットアップを行う。スマホのみ。
     */
    private function setupNaviTouchMessage() {
        $this->arrayToFlasm('navitouch', array(
            "なにするのだ\nさわるななのだ",
            "ちょ・・\nくすぐったいのだ・・！",
            "さ・・さわるななのだ・・",
            "ﾊｱﾊｱ・・",
            "・・・",
            )
        );
        $this->replaceStrings['navitouchNum'] = 5;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * チュートリアル等によるカスタマイズを行う。
     */
    private function specialCustomize() {

        // チュートリアル中の場合。
        if($this->userInfo['tutorial_step'] < User_InfoService::TUTORIAL_END) {

            switch($this->userInfo['tutorial_step']) {

                // メインメニュー案内
                case User_InfoService::TUTORIAL_MAINMENU:
                    if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone")
                        $opening = array(
                            "だ～れがクソ生意気な\nミドリ猫なのだ",
                            "そもそももじょは\n猫ではないのだ\nまったく…",
                            "ココがソネットメニューなのだ",
                            "上下キーかキーで\n選択して、決定キーか\nキーで実行なのだ",
                            "とりあえず､いまは\nクエストを選ぶのだ\n他のは後なのだ",
                        );
                    else
                        $opening = array(
                            "だ～れがクソ生意気な\nミドリ猫なのだ",
                            "そもそももじょは\n猫ではないのだ\nまったく…",
                            "ココがソネットメニューなのだ",
                            "行きたい所を\nタップすればいいのだ",
                            "自分のアバターを\nタップすればメニューの\n説明するのだ",
                            "とりあえず､いまは\nクエストを選ぶのだ\n他のは後なのだ",
                        );

                    $this->replaceStrings['menu0State'] = 'hot';

                    if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone"){
                        $lockdown = array(1, 2, 3, 4, 5, 6, 7);
                    }else{
                        $lockdown = array(1, 2, 3, 4, 5, 6, 7, 8);
                    }
                    break;

                // ファーストクエスト中
                case User_InfoService::TUTORIAL_FIELD:
                    $opening = array(
                        "ブラブラしてないで\n精霊の洞窟クリアするのだ",
                        "一番奥まで行けば\nいいのだ",
                    );
                    $this->replaceStrings['menu0State'] = 'hot';
                    if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone"){
                        $lockdown = array(1, 2, 3, 4, 5, 6, 7);
                    }else{
                        $lockdown = array(1, 2, 3, 4, 5, 6, 7, 8);
                    }
                    break;

                // ステータス案内(廃止)
                case User_InfoService::TUTORIAL_STATUS:
                    $opening = array(
                        "もじょは忙しいのだ\nブラブラしたり…\n飲んだり…",
                        "……これからｽﾃｰﾀｽ\n見に行くのだ\nおまえも来るのだ",
                        "｢ｽﾃｰﾀｽ｣を選択\nするのだ",
                    );
                    $this->replaceStrings['menu2State'] = 'hot';
                    $lockdown = array(1, 3, 4, 5, 6);
                    break;

                // ショップ案内
                case User_InfoService::TUTORIAL_SHOPPING:
                    $opening = array(
                        "あのじじぃ…\nヒゲもハゲにして\n泣かせてやるのだ",
                        "ショップを選択して\n永久脱毛剤買うのだ!",
                    );
                    $this->replaceStrings['menu1State'] = 'hot';
                    if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone"){
                        $lockdown = array(2, 3, 4, 5, 6);
                    }else{
                        $lockdown = array(0, 2, 3, 4, 5, 6, 7, 8);
                    }
                    //ショップ選択
                    $this->replaceStrings['selectmenu'] = 1;
                    break;

                // 対戦案内(廃止)
                case User_InfoService::TUTORIAL_RIVAL:
                    $opening = array(
                        "対戦するときは\n｢対戦｣を選択するのだ",
                        "他のユーザのﾍﾟｰｼﾞにも\n行けるのだ",
                    );
                    $this->replaceStrings['menu3State'] = 'hot';
                    //対戦選択
                    $this->replaceStrings['selectmenu'] = 3;
                    $lockdown = array(4, 5, 6);
                    break;
            }

        // それ以外ではいまのとこナシ。
        }else {
            $opening = array();
            $lockdown = array();
        }

        // オープニングメッセージをSWFにセット。
        $this->arrayToFlasm('opening', $opening);
        $this->replaceStrings['openingNum'] = count($opening);

        // ロックダウンするメニューを反映。
        foreach($lockdown as $index)
            $this->replaceStrings["menu{$index}State"] = 'disable';
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 夏休みキャンペーン
     */
    private function processSummerCampaign() {

        // チュートリアル終了の場合。
        if($this->userInfo['tutorial_step'] >= User_InfoService::TUTORIAL_END) {
            //夏休みキャンペーン
            if(strtotime('2021-08-22 00:00:00') <= strtotime($this->getCurrentTime()) && strtotime('2021-09-01 00:00:00') > strtotime($this->getCurrentTime())){

                // 今日の夏休みキャンペーンを得ているか取得。
                $tryable = (int)date('Ymd') > Service::create('User_Property')->getProperty($this->user_id, 'summer_bonus_date');

                if($tryable){
                    $uitemId = Service::create('User_Item')->gainItem($this->user_id, COIN_ITEM_ID, 500);
                    Service::create('User_Property')->updateProperty($this->user_id, 'summer_bonus_date', date('Ymd'));

                    // 結果画面にリダイレクト
                    Common::redirect('Swf', 'SummerCampaign');
                }
            }
        }
    }

    /**
     * ----------------------------------------------------------
     * getCurrentTime()
     * 現在時間を取得する
     * ----------------------------------------------------------
     */
    function getCurrentTime() {
      $dt = new DateTime();
      $dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
     
      return $dt->format('Y-m-d H:i:s');
    }
}
