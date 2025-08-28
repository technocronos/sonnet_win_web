<?php

/**
 * FLASHを返すアクションの基底クラス。
 */
abstract class SwfBaseAction extends UserBaseAction {

    // trueにすると、作業ディレクトリの削除を行わなくなる。
    protected static $DEBUG = false;

    //pexのPartialDrawをfalseにする場合は上書きして使う
    //・・だったのだがtrueだとfirefoxでちらつくのでデフォルトfalseに変更・・
    protected $PexPartialDraw = 'true';

    protected $replaceStrings = array();
    protected $noTranslate = array();
    protected $replaceImages = array();
    protected $swfName = '';
    protected $skip_flasm = false;

    //子クラスでweb audio apiを使う時はサウンドファイル名のみをこの変数に指定する
    protected $use_web_audio = array();
    //子クラスでaudioタグを使う時はサウンドファイル名のみをこの変数に指定する
    protected $use_audio_tag = array();

    //サウンドリスト。とりあえず使うサウンドは全部ここに書いておく。
    public $web_audio_list = array(
                'bgm_mute'  => "bgm/bgm_mute",
                'bgm_dungeon'  => "bgm/bgm_dungeon",
                'bgm_quest_horror'  => "bgm/bgm_quest_horror",
                'bgm_dungeon3'  => "bgm/bgm_dungeon3",
                'bgm_battle' => "bgm/bgm_battle",
                'bgm_wasteland' => "bgm/bgm_wasteland",
                'bgm_bossbattle' => "bgm/bgm_bossbattle",
                'bgm_bigboss' => "bgm/bgm_bigboss",
                'bgm_menu'   => "bgm/bgm_menu",
                'bgm_home'   => "bgm/bgm_home",
                'bgm_op'   => "bgm/bgm_op",
                'bgm_theme'   => "bgm/bgm_theme",
                'bgm_registance'   => "bgm/bgm_registance",
                'bgm_bright'   => "bgm/bgm_bright",
                'se_btn' => "sfx/se_btn",
                'se_hit'    => "sfx/se_hit",
                'se_damage' => "sfx/se_damage",
                'se_retire'   => "sfx/se_retire",
                'se_win'   => "sfx/se_win",
                'se_battle_end' => "sfx/se_battle_end",
                'se_gachawhiteout' => "sfx/se_gachawhiteout",
                'se_gachashutter' => "sfx/se_gachashutter",
                'se_beam' => "sfx/se_beam",
                'se_getprice' => "sfx/se_getprice",
                'se_combo' => "sfx/se_combo",
                'se_airassaultdown' => "sfx/se_airassaultdown",
                'se_kyoka' => "sfx/se_kyoka",
                'se_kyoka2' => "sfx/se_kyoka2",
                'se_explosionshort' => "sfx/se_explosionshort",
                'se_pallet_rotate' => "sfx/se_pallet_rotate",
                'se_explosionlong' => "sfx/se_explosionlong",
                'se_repair' => "sfx/se_repair",
                'se_flash' => "sfx/se_flash",
                'se_thunder' => "sfx/se_thunder",
                'se_zazaza' => "sfx/jin_zazaza",
                'se_gotoquest' => "sfx/se_gotoquest",
                'se_gaugeUp' => "sfx/se_gaugeup",
                'se_pallet_fall' => "sfx/se_pallet_fall",
                'se_hover' => "sfx/se_hover",
                'se_consolidation' => "sfx/se_consolidation",
                'se_congrats' => "sfx/se_congrats",
                'se_coin' => "sfx/se_coin",
                'se_scream' => "sfx/se_scream",
            );

    //-----------------------------------------------------------------------------------------------------
    /**
     * 派生クラスでオーバーライドして、出力するswfの調整を行う。
     *     ・文字列の置き換え       $this->replaceStrings に設定する。
     *                              FLASMコードに変換するときのエスケープや文字コード変換は自動で
     *                              行われるので必要ない。すでに行われている文字列をセットする場合は
     *                              $this->noTranslate 配列にキーを作成して、値をtrueにセットすること。
     *     ・イメージの置き換え     $this->replaceImages に設定する。
     *     ・元になるswfファイル    $this->swfName に設定する。拡張子は不要。
     *                              アクション名と同一なら設定する必要はない。
     */
    abstract protected function doExecute();


    //-----------------------------------------------------------------------------------------------------
    /**
     * execute()をオーバーライド。基本となる処理を行う。
     */
    public function execute() {

        // デフォルトの、元になるswfファイル名を設定。
        $this->swfName = strtolower($this->context->getActionName());

        // 子クラス個別の処理を行う。
        $this->doExecute();

        if($this->skip_flasm == false){

            // 作業用の一時ディレクトリを作成。
            $workingDir = $this->createWorkingDir();

            // 出力するswfの作成。
            $this->makeFlash($workingDir);

            $swfName = (Common::getCarrier() != "android" && Common::getCarrier() != "iphone" && Controller::getInstance()->getContext()->getModuleName() != 'Admin') ? $this->swfName : $this->swfName . "_sm";

            // 作成したswfを出力。
            $swfPath = $this->respond($workingDir . '/' . $swfName . '.swf');

            // 作業ディレクトリを削除。
            if(!self::$DEBUG)
                $this->cleanUp($workingDir);

        }

        if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone" || Controller::getInstance()->getContext()->getModuleName() == 'Admin'){
            $this->setAttribute('URL_TYPE', URL_TYPE);

            $api_list = array();

            if($this->userInfo) {
                $charaSvc = new Character_InfoService();
                $avatar = $charaSvc->needAvatar($this->user_id, true);
                //APIリスト
                $api_list = array(
                    'apiOnStatus' => Common::genContainerUrl('Swf', 'StatusApi', array(), true), //装備アイテムのリスト
                    'apiOnHisPage' => Common::genContainerUrl('Swf', 'HisPageApi', array(), true), //他人ページのURL
                    'apiOnMessageList' => Common::genContainerUrl('Swf', 'MessageListApi', array(), true), //メッセージリストAPIのURL
                    'apiOnMessage' => Common::genContainerUrl('Swf', 'MessageApi', array(), true), //メッセージAPIのURL
                    'apiOnApproach' => Common::genContainerUrl('Swf', 'ApproachApi', array(), true), //申請送信APIのURL
                    'apiOnApproachList' => Common::genContainerUrl('Swf', 'ApproachListApi', array(), true), //申請送信APIのURL
                    'apiOnMemberList' => Common::genContainerUrl('Swf', 'MemberListApi', array(), true), //仲間一覧APIのURL
                    'apiOnEquipList' => Common::genContainerUrl('Swf', 'EquipListApi', array(), true), //装備アイテムのリスト
                    'apiOnEquipChange' => Common::genContainerUrl('Swf', 'EquipChangeApi', array('charaId'=>$avatar['character_id'], '_sign' => true), true),//装備アイテムのリスト
                    'apiOnShopList' => Common::genContainerUrl('Swf', 'ShopListApi', array(), true), //ショップリストのURL
                    'apiOnShop' => Common::genContainerUrl('Swf', 'ShopApi', array(), true), //ショップリストのURL
                    'apiOnMonsterList' => Common::genContainerUrl('Swf', 'MonsterListApi', array(), true), //モンスターリストのURL
                    'apiOnGiveup' => Common::genContainerUrl('Swf', 'GiveupApi', array(), true), //ギブアップのURL
                    'apiOnGachaLineup' => Common::genContainerUrl('Swf', 'GachaLineupApi', array(), true), //ガチャラインナップ画面のURL
                    'apiOnGachaPlay' => Common::genContainerUrl('Swf', 'GachaPlayApi', array(), true), //ガチャ実行のURL
                    'apiOnRivalList' => Common::genContainerUrl('Swf', 'RivalListApi', array(), true), //バトル一覧のURL
                    'apiOnBattleConfirm' => Common::genContainerUrl('Swf', 'BattleConfirmApi', array(), true), //バトル確認のURL
                    'apiOnNotice' => Common::genContainerUrl('Swf', 'NoticeApi', array(), true), //お知らせAPIのURL
                    'apiOnMemberSearch' => Common::genContainerUrl('Swf', 'MemberSearchApi', array(), true), //仲間検索APIのURL
                    'apiOnHistoryList' => Common::genContainerUrl('Swf', 'HistoryListApi', array(), true), //履歴リストAPIのURL
                    'apiOnBattleHistory' => Common::genContainerUrl('Swf', 'BattleHistoryApi', array(), true), //戦歴リストAPIのURL
                    'apiOnHelpList' => Common::genContainerUrl('Swf', 'HelpListApi', array(), true), //ヘルプリストAPIのURL
                    'apiOnHomeSummary' => Common::genContainerUrl('Swf', 'HomeSummaryApi', array(), true), //サマリーAPIのURL
                    'apiOnCharaImg' => Common::genContainerUrl('Swf', 'CharaImgApi', array(), true), //キャラAPIのURL
                    'apiOnUserItem' => Common::genContainerUrl('Swf', 'UserItemApi', array(), true), //キャラAPIのURL
                    'apiOnItemUseFire' => Common::genContainerUrl('Swf', 'ItemUseFire', array(), true), //キャラAPIのURL
                    'apiOnDiscard' => Common::genContainerUrl('Swf', 'Discard', array(), true), //キャラAPIのURL
                    'apiOnSwfResource' => Common::genContainerUrl('Task', 'GetSwfResourse', array("swf_name" => 'swf_name_string'), true), //キャラAPIのURL
                    'apiOnQuestList' => Common::genContainerUrl('Swf', 'QuestListApi', array(), true), //キャラAPIのURL
                    'apiOnFieldEnd' => Common::genContainerUrl('Swf', 'FieldEndApi', array(), true), //キャラAPIのURL
                    'apiOnReady' => Common::genContainerUrl('Swf', 'ReadyApi', array(), true), //キャラAPIのURL
                    'apiOnBattleResult' => Common::genContainerUrl('Swf', 'BattleResultApi', array(), true), //キャラAPIのURL
                    'apiOnGacha' => Common::genContainerUrl('Swf', 'GachaApi', array(), true), //キャラAPIのURL
                    'apiOnParamUp' => Common::genContainerUrl('Swf', 'ParamUpApi', array(), true), //キャラAPIのURL
                    'apiOnGradeList' => Common::genContainerUrl('Swf', 'GradeListApi', array(), true), //キャラAPIのURL
                    'apiOnGradeUser' => Common::genContainerUrl('Swf', 'GradeUserApi', array(), true), //キャラAPIのURL
                    'apiOnBattleRanking' => Common::genContainerUrl('Swf', 'BattleRankingApi', array(), true), //キャラAPIのURL
                    'apiOnSphereCommand' => Common::genContainerUrl('Swf', 'SphereCommandApi', array(), true), //キャラAPIのURL
                    'apiOnSphereItemList' => Common::genContainerUrl('Swf', 'SphereItemListApi', array(), true), //キャラAPIのURL
                    'apiVcoinSend' => Common::genContainerUrl('Swf', 'VcoinSendApi', array(), true), //キャラAPIのURL
                    'apiVcoinList' => Common::genContainerUrl('Swf', 'VcoinListApi', array(), true), //キャラAPIのURL
                    'apiVcoinLog' => Common::genContainerUrl('Swf', 'VcoinLogApi', array(), true), //キャラAPIのURL
                    'apiPreRegLogSet' => Common::genContainerUrl('Swf', 'PreRegLogApi', array(), true), //
                    'apiOnUserRegist' => Common::genContainerUrl('Swf', 'UserRegistApi', array(), true), //ユーザー登録のURL
                    'apiOnHomeInherit' => Common::genContainerUrl('Swf', 'HomeInheritApi', array(), true), //引き継ぎのURL
                );
            }else{
                //APIリスト
                $api_list = array(
                    'apiOnSwfResource' => Common::genContainerUrl('Task', 'GetSwfResourse', array("swf_name" => 'swf_name_string'), true), //キャラAPIのURL
                    'apiOnUserRegist' => Common::genContainerUrl('Swf', 'UserRegistApi', array(), true), //ユーザー登録のURL
                    'apiOnHomeInherit' => Common::genContainerUrl('Swf', 'HomeInheritApi', array(), true), //引き継ぎのURL
                );
            }

            $this->setAttribute('api_list', $api_list);

            //History_Log定数リスト
            $History_Log_Const = array(
                "History_LogService_TYPE_BATTLE_CHALLENGE" => History_LogService::TYPE_BATTLE_CHALLENGE,
                "History_LogService_TYPE_BATTLE_DEFENCE" => History_LogService::TYPE_BATTLE_DEFENCE,
                "History_LogService_TYPE_CHANGE_GRADE" => History_LogService::TYPE_CHANGE_GRADE,
                "History_LogService_TYPE_LEVEL_UP" => History_LogService::TYPE_LEVEL_UP,
                "History_LogService_TYPE_EFFECT_TIMEUP" => History_LogService::TYPE_EFFECT_TIMEUP,
                "History_LogService_TYPE_INVITE_SUCCESS" => History_LogService::TYPE_INVITE_SUCCESS,
                "History_LogService_TYPE_PRESENTED" => History_LogService::TYPE_PRESENTED,
                "History_LogService_TYPE_QUEST_FIN" => History_LogService::TYPE_QUEST_FIN,           // 廃止
                "History_LogService_TYPE_ITEM_BREAK" => History_LogService::TYPE_ITEM_BREAK,
                "History_LogService_TYPE_ITEM_LVUP" => History_LogService::TYPE_ITEM_LVUP,
                "History_LogService_TYPE_WEEKLY_HIGHER" => History_LogService::TYPE_WEEKLY_HIGHER,
                "History_LogService_TYPE_CAPTURE" => History_LogService::TYPE_CAPTURE,
                "History_LogService_TYPE_ADMIRED" => History_LogService::TYPE_ADMIRED,
                "History_LogService_TYPE_REPLIED" => History_LogService::TYPE_REPLIED,
                "History_LogService_TYPE_COMMENT" => History_LogService::TYPE_COMMENT,
                "History_LogService_TYPE_QUEST_FIN2" => History_LogService::TYPE_QUEST_FIN2,
                "History_LogService_TYPE_TEAM_BATTLE" => History_LogService::TYPE_TEAM_BATTLE,
            );
            $this->setAttribute('History_Log_Const', $History_Log_Const);

            //ランキング景品
            $itemSvc = new Item_MasterService();
            $setSvc = new Set_MasterService();
            foreach(Ranking_LogService::$PRIZES[Ranking_LogService::GRADEPT_WEEKLY] as &$row){
                $item = $itemSvc->getExRecord($row["id"]);
                $set = $setSvc->getRecord($item["set_id"]);

                $row["item_name"] = $item["item_name"];
                $row["set_name"] = $set["set_name"];
            }

            $this->setAttribute('Ranking_Log_Prize_Week', Ranking_LogService::$PRIZES[Ranking_LogService::GRADEPT_WEEKLY]);

            //Tournament_Master定数リスト
            $Tournament_Master_Const = array(
              "Tournament_MasterService_TOUR_MAIN" => Tournament_MasterService::TOUR_MAIN,
              "Tournament_MasterService_TOUR_QUEST" => Tournament_MasterService::TOUR_QUEST,
            );
            $this->setAttribute('Tournament_Master_Const', $Tournament_Master_Const);

            //Item_Master定数リスト
            $Item_Master_Const = array(
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
            );
            $this->setAttribute('Item_Master_Const', $Item_Master_Const);

            //Character_Effect定数リスト
            $Character_Effect_Const = array(
                'TYPE_EXP_INCREASE' => Character_EffectService::TYPE_EXP_INCREASE,
                'TYPE_HP_RECOVER' => Character_EffectService::TYPE_HP_RECOVER,
                'TYPE_ATTRACT' => Character_EffectService::TYPE_ATTRACT,
                'TYPE_DTECH_POWUP' => Character_EffectService::TYPE_DTECH_POWUP,
            );
            $this->setAttribute('Character_Effect_Const', $Character_Effect_Const);

            //userinfo.tutorial_stepの定数リスト
            $User_Info_Tutorial_Const = array(
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
            $this->setAttribute('User_Info_Tutorial_Const', $User_Info_Tutorial_Const);

            //Vcoin_Payment_LogServiceの定数リスト
            $Vcoin_Payment_Log_Const = array(
                'VCOIN_STATUS_INITIAL' => Vcoin_Payment_LogService::STATUS_INITIAL,
                'VCOIN_STATUS_RECEIVE' => Vcoin_Payment_LogService::STATUS_RECEIVE,
                'VCOIN_STATUS_COMPLETE' => Vcoin_Payment_LogService::STATUS_COMPLETE,
                'VCOIN_STATUS_CANCEL' => Vcoin_Payment_LogService::STATUS_CANCEL,
            );
            $this->setAttribute('Vcoin_Payment_Log_Const', $Vcoin_Payment_Log_Const);

            $this->setAttribute('swfUrl', Common::adaptUrl($swfPath));
            $this->setAttribute('PartialDraw', $this->PexPartialDraw);
            $this->setAttribute('carrier', Common::getCarrier());
            $this->setAttribute('jsFileName', $this->getContext()->getActionName());

            $this->setAttribute('web_audio_list', $this->web_audio_list);
            $this->setAttribute('use_web_audio', $this->use_web_audio);
            $this->setAttribute('use_audio_tag', $this->use_audio_tag);

            $this->setAttribute('inhearid_code', $_REQUEST['opensocial_owner_id']);

            //メインページURL
            $this->setAttribute('urlOnMain', Common::genContainerUrl('Swf', 'Main', array(), true));

            return View::SUCCESS;
        }else{
            return View::NONE;
        }


        return View::NONE;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された配列をSWFに渡すためのFLASMコードを生成する。
     *
     * 例えばつぎのように呼び出すと...
     *
     *     $array = array(
     *         'あああああ',
     *         'いいいい',
     *         'ABC' => 'ううううう',
     *         array('aaa'=>'AAA', 'bbb'=>'BBB'),
     *     );
     *     $this->arrayToFlasm('test', $array);
     *
     * $this->replaceStrings['test'] に次のような文字列がセットされる。
     *
     *     push 'test0'
     *     push 'あああああ'
     *     setVariable
     *     push 'test1'
     *     push 'いいいい'
     *     setVariable
     *     push 'testABC'
     *     push 'ううううう'
     *     setVariable
     *     push 'test2aaa'
     *     push 'AAA'
     *     setVariable
     *     push 'test2bbb'
     *     push 'BBB'
     *     setVariable
     *
     * @param string    SWFの変数名、兼、flmファイル上での置換名
     * @param array     SWFに渡したい配列
     */
    protected function arrayToFlasm($swfVarName, $array) {

        // FLASMコード初期化。
        $flasmCode = '';

        // FLASMアセンブラに変換する。
        foreach($array as $index => $value) {

            // 値が配列になっている場合。
            if(is_array($value)) {
                foreach($value as $name => $deepVal) {
                    $flasmCode .= "    push '{$swfVarName}{$index}{$name}'\n"
                               . sprintf("    push '%s'\n", self::translate($deepVal))
                               . "    setVariable\n";
                }

            // 値がスカラー値の場合。
            }else {
                $flasmCode .= "    push '{$swfVarName}{$index}'\n"
                           . sprintf("    push '%s'\n", self::translate($value))
                           . "    setVariable\n";
            }
        }

        // $this->replaceStringsにセット。
        $this->replaceStrings[$swfVarName] = $flasmCode;
        $this->noTranslate[$swfVarName] = true;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 置き換え後のFLASHを作成する。
     *
     * @param string    作業用ディレクトリへのパス
     */
    protected function makeFlash($workingDir) {

        $swfName = (Common::getCarrier() != "android" && Common::getCarrier() != "iphone" && Controller::getInstance()->getContext()->getModuleName() != 'Admin') ? $this->swfName : $this->swfName . "_sm";

        // 変換前、変換後に扱うファイルのパスをベース名(拡張子の直前)まで作成。
        if(PLATFORM_TYPE == "nati" || Controller::getInstance()->getContext()->getModuleName() == 'Admin'){
            //ネイティブ用がある場合はそちらを使う
            $srcPath =  SWF_PATH_N . '/' . $swfName;
            if(!file_exists($srcPath . '.swf')){
                $srcPath =  SWF_PATH . '/' . $swfName;
            }
        }else{
            $srcPath =  SWF_PATH . '/' . $swfName;
        }
        $flmPath =  SWF_PATH . '/' . $swfName;
        $destPath = $workingDir . '/' . $swfName;

        // swf元ファイルをコピー。
        copy($srcPath.'.swf', $destPath.'.swf');

        // 置き換える文字列があるのなら、処理する。
        if($this->replaceStrings) {

            // flmファイルを読み込む。
            $flmString = file_get_contents($flmPath.'.flm');

            // 置き換え部分を展開。
            $replacedFlm = self::expand($flmString, $this->replaceStrings, $this->noTranslate);

            // 置き換えた内容を作業ディレクトリにflmファイルとして書き出す。
            file_put_contents($destPath.'.flm', $replacedFlm);

            // 作業ディレクトリに移動して、flasm実行。
            chdir($workingDir);
            $command = sprintf('"%s" -a "%s.flm" 2>&1', FLASM_COMMAND, $swfName);
            exec($command, $output, $commVal);
            if($commVal)
                throw new MojaviException(implode("\n", $output));

            sleep(0.05);
        }

        // 置き換える画像があるなら、処理する。
        if($this->replaceImages) {

            // swf元ファイルを読み込み。
            $editor = new SWFEditor();
            if( !$editor->input(file_get_contents($destPath.'.swf')) )
                throw new MojaviException('SWFEditorがswfファイルの読み込みに失敗しました：' . $destPath.'.swf');

            // swfの構成要素を一つずつ見ていく。
            foreach($editor->getTagList() as $index => $tag) {

                // 要素のタイプを取得。
                if(strncmp($tag['tagName'], 'DefineBitsLossless', 18) == 0)     $tagType = 'png';
                else if(strncmp($tag['tagName'], 'DefineBitsJPEG', 14) == 0)    $tagType = 'jpg';
                else                                                            $tagType = 'other';

                // jpgかpngならば...
                if($tagType == 'jpg'  ||  $tagType == 'png') {

                    // 要素の objectID を取得。
                    $detail = $editor->getTagDetail($index);
                    $imageId = $detail["image_id"];
if(($tagType == 'png' || $tagType = 'jpg' )&& ENVIRONMENT_TYPE == 'test'){
    //Common::varLog($imageId . ":" . $tagType);
    //Common::varLog($this->replaceImages[$imageId]);
}
                    // replaceImagesプロパティに差し替え画像がセットされているなら、差し替え。
                    if(isset($this->replaceImages[$imageId])) {

                        // セットされている画像のデータを取得。
                        $imgData = file_get_contents($this->replaceImages[$imageId]);

                        // jpgかpngかで差し替えメソッドが違う。
                        if($tagType == 'jpg')      $methodName = 'replaceJpegData';
                        else if($tagType == 'png') $methodName = 'replacePNGData';

                        // 差し替え。
                        $editor->$methodName($imageId, $imgData);
                    }
                }
            }

            //swfファイル書き出し
            file_put_contents($destPath.'.swf', $editor->output());
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * swf作成用、作業ディレクトリを作成する。
     *
     * @return string   作成した作業ディレクトリへのパス
     */
    protected function createWorkingDir() {

        // 定数 SWF_WORKING_DIR で定められているディレクトリの下にサブディレクトリを作って、
        // それを作業ディレクトリとする。

        // 作業ディレクトリへのパスを決定。
        $path = sprintf('%s/%s_%s', SWF_WORKING_DIR, $_REQUEST['opensocial_owner_id'], uniqid());

        // ないなら作成。
        if( !file_exists($path) )
            mkdir($path);

        // パスをリターン。
        return $path;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたswfファイルをクライアントに送信する。
     *
     * @return string   送信するswfファイルの物理パス。
     */
    protected function respond($swfPath) {


        if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone" || Controller::getInstance()->getContext()->getModuleName() == 'Admin'){
            // 1% の確率で古いファイルのクリーンアップを行う。
            if(mt_rand(1, 100) <= 1)
                $this->cleanSwfTmp();

            // 保存先のファイル名を決定。
            $fileName = sprintf('%s_%s.swf', $_REQUEST['opensocial_owner_id'], Common::createRandomString());

            // スマホ用の一時ディレクトリにコピー。
            copy($swfPath, SWF_SM_TMPDIR.'/'.$fileName);
            
            $url = APP_WEB_ROOT."swf/".$fileName;

            return $url;
        }


        // プラットフォームが mixi でなければ、作成した SWF をコンテンツとして返す。
        if(PLATFORM_TYPE != 'mixi') {

            // レスポンスヘッダの調整。
            header("Content-Type: application/x-shockwave-flash");
            header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
            header("Cache-Control: no-cache");
            header("Pragma: no-cache");

            // レスポンスボディにswfを書き出す。
            readfile($swfPath);

        // mixi の場合は話が難しい。
        // mixi のガジェットサーバには、ma.mixi.net と mm.mixi.net がある。通常、ma. を使用するわけだが、
        // 画像やSWFなどの静的コンテンツは mm. を使用しなければならない。ma. を使ってしまうと、HTMLと
        // 同じように変換されて、SWFが壊されてしまう。
        // …単純に発想すると、「SWFを返すアクションは mm. を経由するようにURLを生成する」という対処法に
        // なるが、そのような変更・分岐は手間がかかる上に、mm. を経由している場合はリダイレクトができない
        // という致命的な問題がある。
        // したがって、とりあえず ma. でアクセスさせておいて、作成したSWFをHTTPドキュメントルートから
        // 見える場所に保存して、リダイレクトをかけて保存したSWFを mm. 経由で見にくるように誘導する。
        }else {

            // 1% の確率で古いファイルのクリーンアップを行う。
            if(mt_rand(1, 100) <= 1)
                $this->cleanMixiTmp();

            // 保存先のファイル名を決定。
            $fileName = sprintf('%s_%s.swf', $_REQUEST['opensocial_owner_id'], Common::createRandomString());

            // mixi用の一時ディレクトリにコピー。
            copy($swfPath, SWF_MIXI_TMPDIR.'/'.$fileName);

            // コンテナ経由のリダイレクト先URLを取得。ただし、"ma." を "mm." に書き換える。
            $url = Common::viaContainer(APP_WEB_ROOT."swf/".$fileName, true);
            $url = str_replace('ma.', 'mm.', $url);

            // リダイレクト用のヘッダを出力。
            header("Location: $url");
        }
    }


    /**
     * mixi用の一時ディレクトリの古いファイルを削除する。
     */
    private function cleanMixiTmp() {

        $this->cleanTmp(SWF_MIXI_TMPDIR);

    }


    /**
     * スマホ用の一時ディレクトリの古いファイルを削除する。
     */
    private function cleanSwfTmp() {

        $this->cleanTmp(SWF_SM_TMPDIR);

    }

    /**
     * スマホ用の一時ディレクトリの古いファイルを削除する。
     */
    private function cleanTmp($dir) {
        // 変更時刻がコレより前のファイルを削除する。
        $threshold = time() - 3*60;

        // mixi用の一時ディレクトリにあるエントリをすべて取得して、一つずつみていく。
        foreach(scandir($dir) as $entry) {

            // お決まりのアレは無視。
            if($entry == '.'  ||  $entry == '..')
                continue;

            // 絶対パスを取得。
            $path = $dir.'/'.$entry;

            // 更新時刻が閾値より古ければ削除。
            if(filemtime($path) <= $threshold)
                unlink($path);
        }

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された一時ディレクトリを削除。
     *
     * @return string   一時ディレクトリへのパス。
     */
    protected function cleanUp($workingDir) {

        $swfName = (Common::getCarrier() != "android" && Common::getCarrier() != "iphone" && Controller::getInstance()->getContext()->getModuleName() != 'Admin') ? $this->swfName : $this->swfName . "_sm";

        // 一時ディレクトリの中にあるファイルのパスをベース名(拡張子の直前)まで作成。
        $basePath = $workingDir . '/' . $swfName;
//Common::varDump($basePath);
        // ディレクトリの中にあると思われるファイルを削除。
        // エラー抑制しているのは存在しない場合もあるから。
        @unlink($basePath.'.swf');
        @unlink($basePath.'.flm');
        @unlink($basePath.'.$wf');

        // ディレクトリ削除。エラー抑制しているのは念のため。
        @rmdir($workingDir);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された文字列を、FLASMコード上での文字列表現に変換する。
     *
     * @param string    FLASMコードでの文字列値にしたい文字列。
     * @return string   FLASMコードに埋め込めるように変換した文字列。
     */
    protected static function translate($string) {

        // 改行コードを\nに統一する。
        $string = str_replace("\r\n", "\n", $string);

        if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone" || Controller::getInstance()->getContext()->getModuleName() == 'Admin'){
            $purpose = 'html5';
        }else{
            $purpose = 'swf';
        }

        // SJISに変換して、エスケープ。
        return addcslashes(Common::adaptString($string, $purpose), "\r\n\\'\"");
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 第一引数に指定したFLASMコードに含まれる置き換え部分を、指定した文字列で置き換える。
     *
     * @param string    置き換え部分を含んでいるFLASMコード
     * @param array     置き換えキーとその値を含んでいる配列。
     * @param array     置き換えにあたって、文字コードの変換やエスケープを行う必要がないキーがある場合は、
     *                  そのキーをキーとして含む配列を指定する。
     * @return string   置き換え後のFLASMコード
     */
    protected static function expand($flasm, $values, $rawKeys = array()) {

        // 置き換え文字列の文字コード変換＆「'」のエスケープ
        foreach($values as $index => $string) {
            if( empty($rawKeys[$index]) )
                $values[$index] = self::translate($string);
        }

        // 読み込んだflmの内容のうち、置き換え部分を置き換える。
        $expander = new TemplateExpander($flasm);
        $expander->placeHolder = '/\[:(\S+):\]/';
        $expander->showCannotExpand = true;
        return $expander->expand($values);
    }
}
