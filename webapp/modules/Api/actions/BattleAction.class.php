<?php

class BattleAction extends SmfBaseAction {

    protected function doExecute($params) {

        $battleSvc = new Battle_LogService();

        // 指定されているバトル情報をロード。
        $battle = $battleSvc->needRecord($_GET['battleId']);

        // 他人のバトルの場合はエラー。
        if($this->user_id != $battle['player_id'])
            throw new MojaviException('他人のバトルをロードしようとした');

        // すでに開始されているバトルの場合はエラー画面へ。ただしコンティニューの場合はOK。また、アイテム購入遷移から戻った場合もOK。
        if($battle['true_status'] != Battle_LogService::CREATED && $battle['true_status'] != Battle_LogService::IN_CONTINUE && $_GET['firstscene'] != 'result'){
            $array["result"] = "ok";
            $array["urlOnError"] = Common::genUrl('Api', 'Static', array('id'=>'BattleStartError'));
            return $array;
        }

        // ここまで来ればOK。

        // バトルを扱うユーティリティクラスを取得。
        $battleUtil = BattleCommon::factory($battle);
        $params = $battleUtil->getFlashParams($battle);

        // バトルFLASHで主に使用するパラメータ配列を取得。
        $charaP = &$params['sideP'];
        $charaE = &$params['sideE'];
        $other = &$params['other'];

        // 置換する値を連想配列で設定
        $this->replaceStrings['urlOnConfirm'] = TransmitBaseApiAction::getTransmitUrl(array(
            'action' => 'BattleOpen',
            'battleId' => $battle['battle_id'],
            'code' => $battle['validation_code'],
        ));

        //バトルをコンティニューした場合
        $this->replaceStrings['urlOnContinue'] = TransmitBaseApiAction::getTransmitUrl(array(
            'action' => 'BattleContinue',
            'battleId' => $battle['battle_id'],
            'code' => $battle['validation_code'],
        ));

        //復帰アイテム購入ページへ遷移する場合
        $itemSvc = new Item_MasterService();
        $continue = $itemSvc->needRecord(Item_MasterService::BATTLE_CONTINUE_ID);

        $this->replaceStrings['urlOnBuyItem'] = Common::genContainerUrl(
            'Api', 'BattleBuyItem', array(
                'battleId' => $battle['battle_id'],
                'code' => $battle['validation_code'],
                'item_id' => Item_MasterService::BATTLE_CONTINUE_ID,
                'item_name' => $continue["item_name"],
            ), true
        );

        //バトルが完全に終了した場合
        $this->replaceStrings['urlOnEnd'] = Common::genContainerUrl(
            'Api', 'BattleResult', array('battleId'=>$battle['battle_id'], 'repaireId' => $_GET['repaireId']), true
        );
        //フィールドに戻る場合
        $this->replaceStrings['urlOnQuest'] = Common::genContainerUrl(
            'Api', 'Sphere', array('id'=>$battle['relate_id']), true
        );

        //対戦相手一覧へ戻る場合
        $this->replaceStrings['urlOnList'] = Common::genContainerUrl(
            'Api', 'Rival', null, true
        );

        //対戦相手のページへの場合
        $this->replaceStrings['urlOnMypage'] = Common::genContainerUrl(
            'Api', 'HisPage', array('his_user_id'=>$charaE['user_id']), true
        );

        $charaSvc = new Character_InfoService();
        $avatar = $charaSvc->needAvatar($this->user_id, true);

        //ステータス振り分けのURL
        $this->replaceStrings['urlOnParamUp'] = Common::genContainerUrl(
            'Api', 'ParamUp', array('charaId'=>$avatar['character_id']), true
        );

        $this->replaceStrings['validationCode'] = $battle['validation_code'];
        $this->replaceStrings['battle_id'] = $battle['battle_id'];
        $this->replaceStrings['repaire_id'] = $_GET['repaireId'];


        $this->replaceStrings['CharaIdP'] =      $charaP["character_id"];
        $this->replaceStrings['CharaIdE'] =      $charaE["character_id"];

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
            $this->replaceStrings['navSerif_open'] =   AppUtil::getText("TEXT_NAVI_BATTLE_REVIVAL");

        if($charaP['hp'] <= 0)
            $this->replaceStrings['navSerif_open'] =   AppUtil::getText("TEXT_NAVI_BATTLE_IN_REVIVAL");

        $this->replaceStrings['navSerif_win'] =    $other['navi_win'];
        $this->replaceStrings['navSerif_lose'] =   $other['navi_lose'];
        $this->replaceStrings['navSerif_draw'] =   $other['navi_draw'];
        $this->replaceStrings['navSerif_timeup'] = $other['navi_timeup'];

        $this->replaceStrings['STR_PUSH_BUTTON_MESSAGE'] =  AppUtil::getText("TEXT_MESSAGE_BATTLE_TAP_SCREEN");
        $this->replaceStrings['STR_BATTLE_START'] =  AppUtil::getText("TEXT_MESSAGE_BATTLE_TAP_START");
            
        $this->replaceStrings['STR_CONFIRM_DATA'] =  AppUtil::getText("TEXT_MESSAGE_BATTLE_DATA_LOADING");
        $this->replaceStrings['STR_WAIT_PLEASE'] =  AppUtil::getText("TEXT_MESSAGE_BATTLE_WAIT");

        $this->replaceStrings['STR_ALREADY_START'] = AppUtil::getText("TEXT_MESSAGE_BATTLE_ALREADY_START");
        $this->replaceStrings['STR_ERROR'] = AppUtil::getText("TEXT_MESSAGE_BATTLE_ERROR");

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
        $this->buildDTech($charaP, $charaE, $battle);

        // バトル背景のデータ取得。
        $this->replaceStrings["battle_bg"] = $battleUtil->getBattleBg($battle);

        // 双方の画像情報を取得。
        $spec1 = $this->getFormation($charaP);
        $this->replaceStrings['equip_infoP'] = $spec1;

        $spec2 = $this->getFormation($charaE);
        $this->replaceStrings['equip_infoE'] = $spec2;

        //jsで使用する情報
        $charaP['image_url'] = $path1;
        $this->setAttribute('chara', $charaP);

        if($charaE["bgm"] == "bgm_bossbattle"){
            $this->replaceStrings['bgm_sound'] = "bgm_bossbattle";
        }else if($charaE["bgm"] == "bgm_bigboss"){
            $this->replaceStrings['bgm_sound'] = "bgm_bigboss";
        }else{
            $this->replaceStrings['bgm_sound'] = "bgm_battle";
        }

        //結果画面復旧処理系
        //最初に移動するシーンが定義されてる場合。今の所resultしかない。
        if(!empty($_GET['firstscene'])){
            $this->replaceStrings['firstscene'] = $_GET["firstscene"];
            if($_GET['firstscene'] == "result"){
                $this->replaceStrings['navSerif_open'] =   AppUtil::getText("TEXT_NAVI_BATTLE_WAIT");
                $this->setAttribute("bgm", "bgm_mute"); //再開時はBGM無し
            }
        }else{
            $this->replaceStrings['firstscene'] = "";
        }

        $this->replaceStrings['card']['P'] = null;
        $this->replaceStrings['card']['E'] = null;

//Common::varLog($this->replaceStrings);
        $this->replaceStrings["tournament_id"] = $battle["tournament_id"];

        $this->replaceStrings["result"] = "ok";

        return $this->replaceStrings;

    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 必殺技の組み込みを行う。
     * まだ仮実装。
     */
    private function buildDTech($charaP, $charaE, $battle) {
        $dtechSvc = new Dtech_MasterService();

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

            // 発動時の画像をセット。
            if($graphic_id >= 0){
                if ($sideName == "charaE"){

                    if($battle["tournament_id"] == Tournament_MasterService::TOUR_QUEST){

                        //スター付与以外の必殺技の場合、敵の必殺技はレベル補正をつける
                        if($dtech["code_id"] != 400){
                            // 指定されたスフィアの情報をロード。
                            $record = Service::create('Sphere_Info')->needRecord($battle['relate_id']);
                            // スフィアを制御するオブジェクトを作成。
                            $sphere = SphereCommon::load($record);
                            //いくつ補正されているかを得る
                            $transcend = $sphere->getTranscendLevel();

                            //0以上の場合補正する
                            if($transcend > 0){
                                $factor = (int)$dtech["value1"] / (int)Character_InfoService::INITIAL_HP;

                                $calc_hp = Character_InfoService::INITIAL_HP + ((($charaE["exp"] + $transcend) - 1) * 4);
                                $dtech["value1"] = (int)($calc_hp * $factor);
                            }
                        }
                    }

                    $this->replaceStrings["speaker_" . $sideName] = sprintf('%05d', $graphic_id);
                }else{
                    //ベイグの場合、必殺技がないからオートバトル時主人公の顔が出ないバグ対応
                    if($graphic_id == 0){
                        if($charaP["character_id"] > 0)
                            $graphic_id = 1;
                        else
                            $graphic_id = $charaP["character_id"] * -1;
                    }
                    $this->replaceStrings["speaker_" . $sideName] = sprintf('%05d', $graphic_id);
                }
            }

            $this->replaceStrings["dtech_" . $sideName] = $dtech;

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
            $dtech['dtech_desc'] = str_replace(array("{0}", "{1}"), array($dtech['dtech_desc'],Item_MasterService::DTECH_UPPER_POWER), AppUtil::getText("TEXT_MESSAGE_BATTLE_DTECH_UP"));
        }
    }

    //キャラごとにしゃべる内容を変える。
    //マニュアル操作のキャラが増えるたびに書き足す必要がある
    private function buildAutoSpeak($chara) {
        $pref = "";

        if($chara["character_id"] == -9902){
            //師匠（老人）
            $pref = "SHISYOU";

        }else if($chara["character_id"] == -9905 || $chara["character_id"] == -9121){
            //師匠（若い） or マルス・マルティーニ
            $pref = "SHISYOU2";

        }else if($chara["character_id"] == -20101){
            //エレナ
            $pref = "ELENA";

        }else if($chara["character_id"] == -20103){
            //レイラ
            $pref = "LEIRA";
        }else{
            //通常の主人公の場合
            $pref = "AVATAR";
		    }

        $this->replaceStrings['AUTO_SERIFU_UNISON'] =    AppUtil::getText("TEXT_BATTLE_" . $pref . "_AUTO_SERIFU_UNISON");
        $this->replaceStrings['AUTO_SERIFU_UNISONED'] =    AppUtil::getText("TEXT_BATTLE_" . $pref . "_AUTO_SERIFU_UNISONED");
        $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK'] =    AppUtil::getText("TEXT_BATTLE_" . $pref . "_AUTO_SERIFU_STRONG_ATTACK");
        $this->replaceStrings['AUTO_SERIFU_PRUDENCE'] =    AppUtil::getText("TEXT_BATTLE_" . $pref . "_AUTO_SERIFU_PRUDENCE");
        $this->replaceStrings['AUTO_SERIFU_ABSORPTION'] =    AppUtil::getText("TEXT_BATTLE_" . $pref . "_AUTO_SERIFU_ABSORPTION");
        $this->replaceStrings['AUTO_SERIFU_MIND_READING'] =    AppUtil::getText("TEXT_BATTLE_" . $pref . "_AUTO_SERIFU_MIND_READING");
        $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_1'] =    AppUtil::getText("TEXT_BATTLE_" . $pref . "_AUTO_SERIFU_NO_ABSORPTION_1");
        $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_2'] =    AppUtil::getText("TEXT_BATTLE_" . $pref . "_AUTO_SERIFU_NO_ABSORPTION_2");
        $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK_DESIDE'] =    AppUtil::getText("TEXT_BATTLE_" . $pref . "_AUTO_SERIFU_STRONG_ATTACK_DESIDE");
        $this->replaceStrings['AUTO_SERIFU_PRUDENCE_DESIDE'] =    AppUtil::getText("TEXT_BATTLE_" . $pref . "_AUTO_SERIFU_PRUDENCE_DESIDE");

        $this->replaceStrings['MANUAL_SERIFU_STRONG_ATTACK'] =    AppUtil::getText("TEXT_BATTLE_" . $pref . "_MANUAL_SERIFU_STRONG_ATTACK");
        $this->replaceStrings['MANUAL_SERIFU_PRUDENCE'] =    AppUtil::getText("TEXT_BATTLE_" . $pref . "_MANUAL_SERIFU_PRUDENCE");
        $this->replaceStrings['MANUAL_SERIFU_ABSORPTION'] =    AppUtil::getText("TEXT_BATTLE_" . $pref . "_MANUAL_SERIFU_ABSORPTION");


    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたキャラクターの画像構成(画像を作成するのに必要な最低限の情報)を返す。
     *
     * @param array     Character_InfoService::getExRecord で取得したキャラクター情報。
     * @return array    画像構成を格納した序数配列。
     *                  第0要素にraceが、第1以降の要素には画像を構成するアイテムIDが入る。
     *                  第1以降の各要素の意味は race によって異なる。
     */
    public function getFormation($chara) {


        // 装備なしの状態での装備アイテムIDを取得。
        $mounts = Service::create('Mount_Master')->getMounts($chara['race']);
        $equipGraphs = ResultsetUtil::colValues($mounts, 'default_id', 'mount_id');

        // 装備しているものがある場合はそのアイテムIDで上書き。
        foreach($chara['equip'] as $mountId => $uitem)
            $equipGraphs[$mountId] = $uitem['item_id'];

        // 種族によって切り替える。
        switch($chara['race']) {

            case 'PLA':
                $headId = $equipGraphs[Mount_MasterService::PLAYER_HEAD];
                $bodyId = $equipGraphs[Mount_MasterService::PLAYER_BODY];
                $weaponId = $equipGraphs[Mount_MasterService::PLAYER_WEAPON];
                $shieldId = $equipGraphs[Mount_MasterService::PLAYER_SHIELD];
                return array('PLA', $weaponId, $bodyId, $headId, $shieldId);

            case 'MOB':
                return array('MOB', $chara['graphic_id']);
        }
    }

}
