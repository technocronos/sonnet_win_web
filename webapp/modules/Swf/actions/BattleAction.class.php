<?php

class BattleAction extends SwfBaseAction {

    protected function doExecute() {

        $battleSvc = new Battle_LogService();

        // 指定されているバトル情報をロード。
        $battle = $battleSvc->needRecord($_GET['battleId']);

        // 他人のバトルの場合はエラー。
        if($this->user_id != $battle['player_id'])
            throw new MojaviException('他人のバトルをロードしようとした');

        // すでに開始されているバトルの場合はエラー画面へ。ただしコンティニューの場合はOK。また、アイテム購入遷移から戻った場合もOK。
        if($battle['true_status'] != Battle_LogService::CREATED && $battle['true_status'] != Battle_LogService::IN_CONTINUE && $_GET['firstscene'] != 'result')
            Common::redirect('User', 'Static', array('id'=>'BattleStartError'));
        // ここまで来ればOK。

        // バトルを扱うユーティリティクラスを取得。
        $battleUtil = BattleCommon::factory($battle);
        $params = $battleUtil->getFlashParams($battle);

        // バトルFLASHで主に使用するパラメータ配列を取得。
        $charaP = &$params['sideP'];
        $charaE = &$params['sideE'];
        $other = &$params['other'];

        // 置換する値を連想配列で設定
        $this->replaceStrings['urlOnConfirm'] = TransmitBaseAction::getTransmitUrl(array(
            'action' => 'BattleOpen',
            'battleId' => $battle['battle_id'],
            'code' => $battle['validation_code'],
        ));

        //バトルをコンティニューした場合
        $this->replaceStrings['urlOnContinue'] = TransmitBaseAction::getTransmitUrl(array(
            'action' => 'BattleContinue',
            'battleId' => $battle['battle_id'],
            'code' => $battle['validation_code'],
        ));

        //復帰アイテム購入ページへ遷移する場合
        $itemSvc = new Item_MasterService();
        $continue = $itemSvc->needRecord(Item_MasterService::BATTLE_CONTINUE_ID);

        $this->replaceStrings['urlOnBuyItem'] = Common::genContainerUrl(
            'User', 'BattleBuyItem', array(
                'battleId' => $battle['battle_id'],
                'code' => $battle['validation_code'],
                'item_id' => Item_MasterService::BATTLE_CONTINUE_ID,
                'item_name' => $continue["item_name"],
            ), true
        );

        //ネイティブの場合は上を詰め詰めにしないで35px空ける
        if(PLATFORM_TYPE == "nati")
            $this->replaceStrings['main_position'] = 35;
        else
            $this->replaceStrings['main_position'] = 0;

        if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone"){
            //バトルが完全に終了した場合
            $this->replaceStrings['urlOnEnd'] = Common::genContainerUrl(
                'User', 'BattleResult', array('battleId'=>$battle['battle_id']), true
            );
        }else{
            //バトルが完全に終了した場合
            $this->replaceStrings['urlOnEnd'] = Common::genContainerUrl(
                'Swf', 'BattleResult', array('battleId'=>$battle['battle_id'], 'repaireId' => $_GET['repaireId']), true
            );
            //フィールドに戻る場合
            $this->replaceStrings['urlOnQuest'] = Common::genContainerUrl(
                'Swf', 'Sphere', array('id'=>$battle['relate_id']), true
            );

            //対戦相手一覧へ戻る場合
            $this->replaceStrings['urlOnList'] = Common::genContainerUrl(
                'Swf', 'Main', array('firstscene' => 'rival'), true
            );

            //対戦相手のページへの場合
            $this->replaceStrings['urlOnMypage'] = Common::genContainerUrl(
                'Swf', 'Main', array('firstscene' => 'hispage', 'his_user_id'=>$charaE['user_id']), true
            );

            $charaSvc = new Character_InfoService();
            $avatar = $charaSvc->needAvatar($this->user_id, true);

            //ステータス振り分けのURL
            $this->replaceStrings['urlOnParamUp'] = Common::genContainerUrl(
                'Swf', 'ParamUp', array('charaId'=>$avatar['character_id']), true
            );
        }

        $this->replaceStrings['validationCode'] = $battle['validation_code'];
        $this->replaceStrings['battle_id'] = $battle['battle_id'];
        $this->replaceStrings['repaire_id'] = $_GET['repaireId'];


        $this->replaceStrings['nameP'] =      Text_LogService::get($charaP['name_id']);
        $this->replaceStrings['LvP'] =        $charaP['level'];
        $this->replaceStrings['hpMaxP'] =     (int)$charaP['hp_max'];
        $this->replaceStrings['hpStartP'] =   (int)$charaP['hp'];
        $this->replaceStrings['att1P'] =      $charaP['total_attack1'];
        $this->replaceStrings['att2P'] =      $charaP['total_attack2'];
        $this->replaceStrings['att3P'] =      $charaP['total_attack3'];
        $this->replaceStrings['def1P'] =      $charaP['total_defence1'];
        $this->replaceStrings['def2P'] =      $charaP['total_defence2'];
        $this->replaceStrings['def3P'] =      $charaP['total_defence3'];

        //コンティニューアイテム関連
        $this->replaceStrings['continueError'] =   $charaP["continueInfo"]['continueError'];
        $this->replaceStrings['continueItemCnt'] =   $charaP["continueInfo"]['continueItemCnt'];
        $this->replaceStrings['continueItemName'] =   $continue["item_name"];
        $this->replaceStrings['continue_count'] =   $battle['ready_detail']["continue_count"];
        $this->replaceStrings['CONTINUE_COUNT_LIMIT'] =   BattleCommon::CONTINUE_LIMIT_COUNT;

        $this->replaceStrings['nameE'] =      Text_LogService::get($charaE['name_id']);
        $this->replaceStrings['LvE'] =        $charaE['level'];
        $this->replaceStrings['hpMaxE'] =     (int)$charaE['hp_max'];
        $this->replaceStrings['hpStartE'] =   (int)$charaE['hp'];
        $this->replaceStrings['att1E'] =      $charaE['total_attack1'];
        $this->replaceStrings['att2E'] =      $charaE['total_attack2'];
        $this->replaceStrings['att3E'] =      $charaE['total_attack3'];
        $this->replaceStrings['def1E'] =      $charaE['total_defence1'];
        $this->replaceStrings['def2E'] =      $charaE['total_defence2'];
        $this->replaceStrings['def3E'] =      $charaE['total_defence3'];

        $this->replaceStrings['playerBrainLv'] = $charaP["battle_brain"];

        $this->replaceStrings['enemyBrainLv'] = $other['brain_level'];
        $this->replaceStrings['spdRate'] =      $other['speed_balance'];
        $this->replaceStrings['timeupTurns'] =  $other['timeup_turns'];
        $this->replaceStrings['randomSeed'] =   $other['rand_seed'];

        if($battle['ready_detail']["continue_count"] <= 0)
            $this->replaceStrings['navSerif_open'] =   $other['navi_open'];
        else
            $this->replaceStrings['navSerif_open'] =   "復活したのだ！今度こそ勝つのだ！";

        if($charaP['hp'] <= 0)
            $this->replaceStrings['navSerif_open'] =   "復旧してるのだ。";

        $this->replaceStrings['navSerif_win'] =    $other['navi_win'];
        $this->replaceStrings['navSerif_lose'] =   $other['navi_lose'];
        $this->replaceStrings['navSerif_draw'] =   $other['navi_draw'];
        $this->replaceStrings['navSerif_timeup'] = $other['navi_timeup'];

        $this->replaceStrings['STR_TACTICS_1'] =   "強攻";
        $this->replaceStrings['STR_TACTICS_2'] =   "慎重";
        $this->replaceStrings['STR_TACTICS_3'] =   "吸収";
        $this->replaceStrings['STR_TACTICS_4'] =   "ユニゾン";

        $this->replaceStrings['STR_TACTICS_MESSAGE'] =  "④吸収　⑤慎重　⑥強攻";

        if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone")
            $this->replaceStrings['STR_PUSH_BUTTON_MESSAGE'] =  "ボタンを押してください";
        else
            $this->replaceStrings['STR_PUSH_BUTTON_MESSAGE'] =  "画面をタップしてください";

        if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone")
            $this->replaceStrings['STR_BATTLE_START'] =  "決定ボタンでバトル開始";
        else
            $this->replaceStrings['STR_BATTLE_START'] =  "画面タップでバトル開始";
            
        $this->replaceStrings['STR_CONFIRM_DATA'] =  "データ確認中...";
        $this->replaceStrings['STR_WAIT_PLEASE'] =  "お待ちください...";

        $this->replaceStrings['STR_ALREADY_START'] = "すでに開始されています";
        $this->replaceStrings['STR_ERROR'] = "エラー";

        // 統計値を初期化
        $this->replaceStrings['statTactP0'] =   $charaP['summary']['tact0'];     // プレイヤーが「ユニゾン」した回数
        $this->replaceStrings['statTactP1'] =   $charaP['summary']['tact1'];     // プレイヤーが「強攻」を選択した回数
        $this->replaceStrings['statTactP2'] =   $charaP['summary']['tact2'];     // プレイヤーが「慎重」を選択した回数
        $this->replaceStrings['statTactP3'] =   $charaP['summary']['tact3'];     // プレイヤーが「吸収」を選択した回数
        $this->replaceStrings['statTactE0'] =   $charaE['summary']['tact0'];     // 同、相手側
        $this->replaceStrings['statTactE1'] =   $charaE['summary']['tact1'];     // 
        $this->replaceStrings['statTactE2'] =   $charaE['summary']['tact2'];     // 
        $this->replaceStrings['statTactE3'] =   $charaE['summary']['tact3'];     // 
        $this->replaceStrings['statNattCntP'] = $charaP['summary']['nattCnt'];   // プレイヤーが通常攻撃を繰り出した回数
        $this->replaceStrings['statNattCntE'] = $charaE['summary']['nattCnt'];   // 同、相手側
        $this->replaceStrings['statNhitCntP'] = $charaP['summary']['nhitCnt'];   // プレイヤーが通常攻撃を当てた回数
        $this->replaceStrings['statNhitCntE'] = $charaE['summary']['nhitCnt'];   // 同、相手側
        $this->replaceStrings['statNdamP'] =    $charaP['summary']['ndam'];      // プレイヤーが通常攻撃によって与えたダメージ
        $this->replaceStrings['statNdamE'] =    $charaE['summary']['ndam'];      // 同、相手側
        $this->replaceStrings['statRevCntP'] =  $charaP['summary']['revCnt'];    // プレイヤーがリベンジを発動した回数
        $this->replaceStrings['statRevCntE'] =  $charaE['summary']['revCnt'];    // 同、相手側
        $this->replaceStrings['statRattCntP'] = $charaP['summary']['rattCnt'];   // プレイヤーがリベンジ攻撃を繰り出した回数
        $this->replaceStrings['statRattCntE'] = $charaE['summary']['rattCnt'];   // 同、相手側
        $this->replaceStrings['statRhitCntP'] = $charaP['summary']['rhitCnt'];   // プレイヤーがリベンジ攻撃を当てた回数
        $this->replaceStrings['statRhitCntE'] = $charaE['summary']['rhitCnt'];   // 同、相手側
        $this->replaceStrings['statRdamP'] =    $charaP['summary']['rdam'];      // プレイヤーがリベンジ攻撃によって与えたダメージ
        $this->replaceStrings['statRdamE'] =    $charaE['summary']['rdam'];      // 同、相手側
        $this->replaceStrings['statOdamP'] =    $charaP['summary']['odam'];      // プレイヤーがその他攻撃によって与えたダメージ
        $this->replaceStrings['statOdamE'] =    $charaE['summary']['odam'];      // 同、相手側

        // オート時のセリフ組み込みを行う。
        $this->buildAutoSpeak($charaP);

        // 必殺技の組み込みを行う。
        $this->buildDTech($charaP, $charaE);

        // イメージの差し替え
        if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone"){
            $this->replaceImages[17] = CharaImageUtil::getImageFromChara($charaP, 'swf');
            $this->replaceImages[19] = CharaImageUtil::getImageFromChara($charaE, 'swf');
        }else{
            // バトル背景のデータ取得。
            $this->img_list["battle_bg"] = "img/battleBg/" . $battleUtil->getBattleBg($battle) . ".png";

            // 双方の画像情報を取得。
            $spec1 = CharaImageUtil::getSpec($charaP);
            $path1 = sprintf('%s.%s.gif', $spec1, 'full');
            $this->replaceStrings['imageUrlP'] = $path1;

            $spec2 = CharaImageUtil::getSpec($charaE);
            $path2 = sprintf('%s.%s.gif', $spec2, 'full');
            $this->replaceStrings['imageUrlE'] = $path2;
        }

        //jsで使用する情報
        $charaP['image_url'] = $path1;
        $this->setAttribute('chara', $charaP);

        //先に読み込んでおく画像を定義する
        $this->img_list["bg_none"] = "img/parts/sp/preload/bg_none.png";
        $this->setAttribute('img_list', $this->img_list);

        //必要なHTMLを定義する。
        $this->html_list["BattleResult"] = MO_HTDOCS . "/html/BattleResult.html";
        //ネイティブ用HTMLがある場合はそれを使う
        if(PLATFORM_TYPE == "nati"){
            $this->html_list["BattleResult"] = MO_HTDOCS . "/html/native/BattleResult.html";
            if(!file_exists($this->html_list["BattleResult"])){
                $this->html_list["BattleResult"] = MO_HTDOCS . "/html/BattleResult.html";
            }
        }

        $this->html_list["LevelUp"] = MO_HTDOCS . "/html/LevelUp.html";
        $this->html_list["GradeUp"] = MO_HTDOCS . "/html/GradeUp.html";
        $this->html_list["ZukanGet"] = MO_HTDOCS . "/html/ZukanGet.html";
        $this->html_list["BattleItemGet"] = MO_HTDOCS . "/html/BattleItemGet.html";
        $this->html_list["ParamSeed"] = MO_HTDOCS . "/html/ParamSeed.html";
        $this->html_list["VcoinGet"] = MO_HTDOCS . "/html/VcoinGet.html";

        $this->setAttribute('html_list', $this->html_list);

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

        //バトルのアニメーションが動かないのでfalse
        $this->PexPartialDraw = 'false';

        //サウンド設定。バトルはweb_audio_apiのみを使う
        $this->use_web_audio = array(
            "se_btn",
            "se_hit",
            "se_damage",
            "se_retire",
            "se_battle_end",
            "se_gachawhiteout",
            "se_gachashutter",
            "se_beam",
            "se_getprice",
            "se_airassaultdown",
            "se_combo",
            "se_kyoka",
            "se_win",
            "se_kyoka2",
            "se_explosionshort",
            "se_hover",
        );

        //BGMはaudioタグを使う
        $this->use_audio_tag = array(
            "bgm_mute",
        );

        if($charaE["bgm"] == "bgm_bossbattle"){
            array_push($this->use_audio_tag,"bgm_bossbattle");
            $this->setAttribute("bgm", "bgm_bossbattle");
            $this->replaceStrings['bgm_sound'] = "bgm_bossbattle";
        }else if($charaE["bgm"] == "bgm_bigboss"){
            array_push($this->use_audio_tag,"bgm_bigboss");
            $this->setAttribute("bgm", "bgm_bigboss");
            $this->replaceStrings['bgm_sound'] = "bgm_bigboss";
        }else{
            array_push($this->use_audio_tag,"bgm_battle");
            $this->setAttribute("bgm", "bgm_battle");
            $this->replaceStrings['bgm_sound'] = "bgm_battle";
        }

        //結果画面復旧処理系
        //最初に移動するシーンが定義されてる場合。今の所resultしかない。
        if(!empty($_GET['firstscene'])){
            $this->replaceStrings['firstscene'] = $_GET["firstscene"];
            if($_GET['firstscene'] == "result"){
                $this->replaceStrings['navSerif_open'] =   "ちょっと待つのだ";
                $this->setAttribute("bgm", "bgm_mute"); //再開時はBGM無し
            }
        }else{
            $this->replaceStrings['firstscene'] = "";
        }

//Common::varLog($this->replaceStrings);

    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 必殺技の組み込みを行う。
     * まだ仮実装。
     */
    private function buildDTech($charaP, $charaE) {
//Common::varLog($charaP);
        //114だったが画像を足したためID変更
        $dtech_image_id = (Common::getCarrier() != "android" && Common::getCarrier() != "iphone") ? 120 : 138;

        $dtechSvc = new Dtech_MasterService();

        // 必殺技のデータが置かれているディレクトリを取得。
        $dir = MO_BASE_DIR.'/resources/dtech/';

        // プレイヤー側、敵側の順で処理する。
        for($s = 0 ; $s <= 1 ; $s++) {

            $sideName = $s ? 'charaE' : 'charaP';
            $side = $$sideName;

            // 適用する必殺技の初期化。
            $dtech = null;
            $graphic_id = 0;

            // 持っている必殺技のIDを取得。必殺技があるなら...
            $dtechId = $this->getDTechId($side);
            if($dtechId) {

                $dtech = $dtechSvc->needRecord($dtechId);
                $this->reviseDtech($dtech, $side);

                //graphic_idをここでとっておく。必殺技発動してもしなくても画像は差し替えたいため。
                if($dtech['graphic_id'] >= 0)
                    $graphic_id = $dtech['graphic_id'];

                // 発動判定。発動しなかったらキャンセルする。
                if($dtech['invoke_rate'] < mt_rand(1, 100)){
                    $dtech = null;
                }
            }

            // 必殺技がない場合はカラの必殺技データを読み込む。
            if( is_null($dtech) )
                $dtech = $dtechSvc->needRecord(Dtech_MasterService::NONE);

            // 必殺技のコード読み込み。
            $code = file_get_contents( sprintf($dir.'code/%05d.flm', $dtech['code_id']) );

            // コードの置き換え部分を置き換える。
            $code = self::expand($code, $dtech);

            // 必殺技のコードを埋め込む。
            $this->replaceStrings["dtech{$s}"] = $code;
            $this->noTranslate["dtech{$s}"] = true;

            // 発動時の画像をセット。
            if($graphic_id >= 0){
                if(Common::getCarrier() != "android" && Common::getCarrier() != "iphone"){
                    $this->replaceImages[$dtech_image_id + $s*2] = sprintf($dir.'image/%05d.png', $graphic_id);
                }else{
                    if ($sideName == "charaE"){
                        $this->replaceStrings["speaker_" . $sideName] = sprintf('img/dtech/%05d.png?' . WIDE_STAMP, $graphic_id);
                    }else{
                        //ベイグの場合、必殺技がないからオートバトル時主人公の顔が出ないバグ対応
                        if($graphic_id == 0){
                            if($charaP["character_id"] > 0)
                                $graphic_id = 1;
                            else
                                $graphic_id = $charaP["character_id"] * -1;
                        }
                        $this->replaceStrings["speaker_" . $sideName] = sprintf('img/dtech/%05d.png?' . WIDE_STAMP, $graphic_id);
                    }
                }
            }
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたキャラが持っている必殺技のIDを返す
     */
    private function getDTechId($chara) {

        // キャラデータに直接必殺技が設定されている場合はそれを読み出す。
        if($chara['dtech1_id'])
            return $chara['dtech1_id'];

        // プレイヤーキャラの場合...
        if($chara['race'] == 'PLA') {

            // 階級に必殺技が設定されているならそれ。
            $grade = Service::create('Grade_Master')->needRecord($chara['grade_id']);
            if($grade['dtech_id'])
                return $grade['dtech_id'];
        }

        // ここまで来たら必殺技ナシ。
        return null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された必殺技データに、引数で指定されたキャラによる補正を施す。
     */
    private function reviseDtech(&$dtech, $chara) {

        // 階級による必殺技以外は補正なし。
        if($dtech['dtech_id'] < 32000)
            return;

        // 必殺技の補正効果を取得。
        $powup = Service::create('Character_Effect')->getEffectValue($chara['character_id'], Character_EffectService::TYPE_DTECH_POWUP);

        // Level1以上なら確率UP
        if($powup >= 1)
            $dtech['invoke_rate'] = Item_MasterService::DTECH_UPPER_INVOKE;

        // Level2以上なら威力UP
        if($powup >= 2){
            $dtech['value1'] += (int)($dtech['value1'] * Item_MasterService::DTECH_UPPER_POWER/100);
            $dtech['dtech_desc'] = $dtech['dtech_desc'] . "威力" . Item_MasterService::DTECH_UPPER_POWER . "%UP！";
        }
    }

    //キャラごとにしゃべる内容を変える。
    //マニュアル操作のキャラが増えるたびに書き足す必要がある
    private function buildAutoSpeak($chara) {
        if($chara["code"] == "avatar"){
            //通常の主人公の場合
            $this->replaceStrings['AUTO_SERIFU_UNISON'] =    "おっ！カード揃った！ユニゾンだ！！";
            $this->replaceStrings['AUTO_SERIFU_UNISONED'] =    "あーん・・相手のユニゾンだ・・";
            $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK'] =    "うーん・・わっかんないからとりあえず攻め気で行くぞいオリャ！";
            $this->replaceStrings['AUTO_SERIFU_PRUDENCE'] =    "うーん・・わっかんないからとりあえず様子見するか・・";
            $this->replaceStrings['AUTO_SERIFU_ABSORPTION'] =    "うーん・・わっかんないからとりあえず吸収狙いだ！";
            $this->replaceStrings['AUTO_SERIFU_MIND_READING'] =    "むむむ・・第六感が働いたぞ・・！戦術が読めた！";
            $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_1'] =    "スピードじゃ勝てないから吸収は無いかな・・";
            $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_2'] =    "カードの相性がいいから吸収は意味ないかな・・";
            $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK_DESIDE'] =    "じゃ！強攻だ！";
            $this->replaceStrings['AUTO_SERIFU_PRUDENCE_DESIDE'] =    "ここは慎重に・・";

            $this->replaceStrings['MANUAL_SERIFU_STRONG_ATTACK'] =    "よーし・・ガンガン攻め気で行くぞいオリャ！";
            $this->replaceStrings['MANUAL_SERIFU_PRUDENCE'] =    "うーん・・とりあえず様子見するか・・";
            $this->replaceStrings['MANUAL_SERIFU_ABSORPTION'] =    "よーし・・スピードを生かして吸収狙いだ！";

        }else if($chara["character_id"] == -9902){
            //師匠（老人）
            $this->replaceStrings['AUTO_SERIFU_UNISON'] =    "おっ！カードが揃った！ユニゾンじゃ！！";
            $this->replaceStrings['AUTO_SERIFU_UNISONED'] =    "むむむ・・相手のユニゾンじゃ・・";
            $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK'] =    "ふむ・・分からんからとりあえず攻め気で行くぞい！";
            $this->replaceStrings['AUTO_SERIFU_PRUDENCE'] =    "ふむ・・ここはとりあえず慎重に様子見するか・・";
            $this->replaceStrings['AUTO_SERIFU_ABSORPTION'] =    "ふむ・・スピードも劣らんし、吸収狙いじゃな";
            $this->replaceStrings['AUTO_SERIFU_MIND_READING'] =    "むむむ・・第六感が働いたぞ・・！戦術が読めた！";
            $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_1'] =    "スピードでは勝てないのう・・吸収は無理じゃ・・";
            $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_2'] =    "カードの相性がいいから吸収は意味ないのう・・";
            $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK_DESIDE'] =    "強攻じゃ！";
            $this->replaceStrings['AUTO_SERIFU_PRUDENCE_DESIDE'] =    "ここは慎重に・・";

            $this->replaceStrings['MANUAL_SERIFU_STRONG_ATTACK'] =    "ふむ・・ここは攻め気で行くぞい！";
            $this->replaceStrings['MANUAL_SERIFU_PRUDENCE'] =    "ふむ・・ここはとりあえず慎重に様子見するか・・";
            $this->replaceStrings['MANUAL_SERIFU_ABSORPTION'] =    "ふむ・・スピードも劣らんし、吸収狙いじゃな";

        }else if($chara["character_id"] == -9905){
            //師匠（若い）
            $this->replaceStrings['AUTO_SERIFU_UNISON'] =    "フッ！カードが揃ったな。ユニゾンをくらえ！！";
            $this->replaceStrings['AUTO_SERIFU_UNISONED'] =    "フッ・・相手のユニゾンとはしゃらくさい・・";
            $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK'] =    "よし・・攻め気で行くぞ！";
            $this->replaceStrings['AUTO_SERIFU_PRUDENCE'] =    "ふむ・・ここはとりあえず慎重に様子見するか・・";
            $this->replaceStrings['AUTO_SERIFU_ABSORPTION'] =    "よし・・スピードも劣らないし、吸収狙いだ！";
            $this->replaceStrings['AUTO_SERIFU_MIND_READING'] =    "フッ・・お前の戦術などお見通しだ！";
            $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_1'] =    "すばしっこい奴だ・・吸収はやめておこう・・";
            $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_2'] =    "カードの相性がいい。吸収は意味ないな・・";
            $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK_DESIDE'] =    "強攻だ！";
            $this->replaceStrings['AUTO_SERIFU_PRUDENCE_DESIDE'] =    "ここは様子見だ・・";

            $this->replaceStrings['MANUAL_SERIFU_STRONG_ATTACK'] =    "よし・・攻め気で行くぞ！";
            $this->replaceStrings['MANUAL_SERIFU_PRUDENCE'] =    "ふむ・・ここはとりあえず慎重に様子見するか・・";
            $this->replaceStrings['MANUAL_SERIFU_ABSORPTION'] =    "よし・・スピードも劣らないし、吸収狙いだ！";
        }else if($chara["character_id"] == -9121){
            //マルティーニ王
            $this->replaceStrings['AUTO_SERIFU_UNISON'] =    "フッ！カードが揃ったな。ユニゾンをくらえ！！";
            $this->replaceStrings['AUTO_SERIFU_UNISONED'] =    "フッ・・相手のユニゾンとはしゃらくさい・・";
            $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK'] =    "よし・・攻め気で行くぞ！";
            $this->replaceStrings['AUTO_SERIFU_PRUDENCE'] =    "ふむ・・ここはとりあえず慎重に様子見するか・・";
            $this->replaceStrings['AUTO_SERIFU_ABSORPTION'] =    "よし・・スピードも劣らないし、吸収狙いだ！";
            $this->replaceStrings['AUTO_SERIFU_MIND_READING'] =    "フッ・・お前の戦術などお見通しだ！";
            $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_1'] =    "すばしっこい奴だ・・吸収はやめておこう・・";
            $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_2'] =    "カードの相性がいい。吸収は意味ないな・・";
            $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK_DESIDE'] =    "強攻だ！";
            $this->replaceStrings['AUTO_SERIFU_PRUDENCE_DESIDE'] =    "ここは様子見だ・・";

            $this->replaceStrings['MANUAL_SERIFU_STRONG_ATTACK'] =    "よし・・攻め気で行くぞ！";
            $this->replaceStrings['MANUAL_SERIFU_PRUDENCE'] =    "ふむ・・ここはとりあえず慎重に様子見するか・・";
            $this->replaceStrings['MANUAL_SERIFU_ABSORPTION'] =    "よし・・スピードも劣らないし、吸収狙いだ！";
        }else if($chara["character_id"] == -20101){
            //エレナ
            $this->replaceStrings['AUTO_SERIFU_UNISON'] =    "やった！カード揃った！ユニゾンよ！！";
            $this->replaceStrings['AUTO_SERIFU_UNISONED'] =    "あーん・・相手のユニゾンだぁ・・";
            $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK'] =    "うーん・・わっかんないからとりあえず攻め気で行くよ！";
            $this->replaceStrings['AUTO_SERIFU_PRUDENCE'] =    "うーん・・わっかんないからとりあえず様子見しよ・・";
            $this->replaceStrings['AUTO_SERIFU_ABSORPTION'] =    "うーん・・わっかんないからとりあえず吸収狙いね！";
            $this->replaceStrings['AUTO_SERIFU_MIND_READING'] =    "むむむ・・第六感が働いたわ・・！戦術が読めたよ！";
            $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_1'] =    "スピードじゃ勝てないから吸収は無いかな・・";
            $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_2'] =    "カードの相性がいいから吸収は意味ないかな・・";
            $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK_DESIDE'] =    "じゃ！強攻だ！";
            $this->replaceStrings['AUTO_SERIFU_PRUDENCE_DESIDE'] =    "ここは慎重に・・";

            $this->replaceStrings['MANUAL_SERIFU_STRONG_ATTACK'] =    "よーし・・ガンガン攻め気で行くよ！";
            $this->replaceStrings['MANUAL_SERIFU_PRUDENCE'] =    "うーん・・とりあえず様子見しよ・・";
            $this->replaceStrings['MANUAL_SERIFU_ABSORPTION'] =    "よーし・・スピードを生かして吸収狙いね！";

        }else if($chara["character_id"] == -20103){
            //レイラ
            $this->replaceStrings['AUTO_SERIFU_UNISON'] =    "あら！カード揃ったわね！ユニゾンよ！！";
            $this->replaceStrings['AUTO_SERIFU_UNISONED'] =    "あら・・相手のユニゾンだわ・・";
            $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK'] =    "よし・・ここは攻め気で行くわよ！";
            $this->replaceStrings['AUTO_SERIFU_PRUDENCE'] =    "うーん・・わかんないからとりあえず様子見ね・・";
            $this->replaceStrings['AUTO_SERIFU_ABSORPTION'] =    "よし・・ここはとりあえず吸収狙いね！";
            $this->replaceStrings['AUTO_SERIFU_MIND_READING'] =    "むむむ・・第六感が働いたわ・・！戦術が読めわよ！";
            $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_1'] =    "スピードじゃ勝てないわね・・吸収は無いかな・・";
            $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_2'] =    "カードの相性がいいから吸収は意味ないわね・・";
            $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK_DESIDE'] =    "じゃ！強攻よ！";
            $this->replaceStrings['AUTO_SERIFU_PRUDENCE_DESIDE'] =    "ここは慎重に・・";

            $this->replaceStrings['MANUAL_SERIFU_STRONG_ATTACK'] =    "よーし・・ガンガン攻め気で行くわよ！";
            $this->replaceStrings['MANUAL_SERIFU_PRUDENCE'] =    "うーん・・とりあえず様子見ね・・";
            $this->replaceStrings['MANUAL_SERIFU_ABSORPTION'] =    "よーし・・スピードを生かして吸収狙いよ！";

        }else{
            //通常の主人公の場合
            $this->replaceStrings['AUTO_SERIFU_UNISON'] =    "おっ！カード揃った！ユニゾンだ！！";
            $this->replaceStrings['AUTO_SERIFU_UNISONED'] =    "あーん・・相手のユニゾンだ・・";
            $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK'] =    "うーん・・わっかんないからとりあえず攻め気で行くぞいオリャ！";
            $this->replaceStrings['AUTO_SERIFU_PRUDENCE'] =    "うーん・・わっかんないからとりあえず様子見するか・・";
            $this->replaceStrings['AUTO_SERIFU_ABSORPTION'] =    "うーん・・わっかんないからとりあえず吸収狙いだ！";
            $this->replaceStrings['AUTO_SERIFU_MIND_READING'] =    "むむむ・・第六感が働いたぞ・・！戦術が読めた！";
            $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_1'] =    "スピードじゃ勝てないから吸収は無いかな・・";
            $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_2'] =    "カードの相性がいいから吸収は意味ないかな・・";
            $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK_DESIDE'] =    "じゃ！強攻だ！";
            $this->replaceStrings['AUTO_SERIFU_PRUDENCE_DESIDE'] =    "ここは慎重に・・";

            $this->replaceStrings['MANUAL_SERIFU_STRONG_ATTACK'] =    "よーし・・ガンガン攻め気で行くぞいオリャ！";
            $this->replaceStrings['MANUAL_SERIFU_PRUDENCE'] =    "うーん・・とりあえず様子見するか・・";
            $this->replaceStrings['MANUAL_SERIFU_ABSORPTION'] =    "よーし・・スピードを生かして吸収狙いだ！";

		}
    }
}
