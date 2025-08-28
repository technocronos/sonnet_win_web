<?php

class TutorialBattleAction extends SmfBaseAction {

    protected function doExecute($params) {

        if( isset($_POST['result']) ){
            $this->replaceStrings['result'] = "ok";

            $this->processResult();

            return $this->replaceStrings;
        }

        // プレイヤーのパラメータ
        $this->replaceStrings['LvP'] =        1;
        $this->replaceStrings['hpMaxP'] =     120;
        $this->replaceStrings['hpStartP'] =   120;
        $this->replaceStrings['att1P'] =      30;
        $this->replaceStrings['att2P'] =      30;
        $this->replaceStrings['att3P'] =      30;
        $this->replaceStrings['def1P'] =      30;
        $this->replaceStrings['def2P'] =      30;
        $this->replaceStrings['def3P'] =      30;

        // 相手のパラメータ
        $this->replaceStrings['LvE'] =        1;
        $this->replaceStrings['hpMaxE'] =     50;
        $this->replaceStrings['hpStartE'] =   50;
        $this->replaceStrings['att1E'] =      29;
        $this->replaceStrings['att2E'] =      29;
        $this->replaceStrings['att3E'] =      29;
        $this->replaceStrings['def1E'] =      28;
        $this->replaceStrings['def2E'] =      28;
        $this->replaceStrings['def3E'] =      28;

        // 相手側思考ルーチンのレベル。0～100まで
        $this->replaceStrings['enemyBrainLv'] =      0;

        // スピードバランス。プレイヤー完全優位なら+1.0、敵側完全優位なら-1.0
        $this->replaceStrings['spdRate'] = 0.3;

        // 乱数のシード
        $this->replaceStrings['randomSeed'] =   31914;

        // タイムアップになるターン数
        $this->replaceStrings['timeupTurns'] =  2;

        //出てくるカードを指定
        $this->replaceStrings['card']['P'][1] =  array(1,3,1);
        $this->replaceStrings['card']['P'][2] =  array(1,2,3);
        $this->replaceStrings['card']['E'][1] =  array(2,2,1);
        $this->replaceStrings['card']['E'][2] =  array(2,2,2);

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

        //カラの必殺技
        $dtechSvc = new Dtech_MasterService();
        $this->replaceStrings["dtech_charaP"] = $dtechSvc->needRecord(Dtech_MasterService::NONE);
        $this->replaceStrings["dtech_charaE"] = $dtechSvc->needRecord(Dtech_MasterService::NONE);

        // 開始時と終了時のセリフをセット。
        $this->replaceStrings['tutOpen'] = AppUtil::getTexts("battle_text_tutOpen");

        $this->replaceStrings['tutTurn'] = AppUtil::getTexts("battle_text_tutTurn");

        $this->replaceStrings['tutUni'] = AppUtil::getTexts("battle_text_tutUni");

        $this->replaceStrings['tutStar'] = AppUtil::getTexts("battle_text_tutStar");

        $this->replaceStrings['tutRevP'] = AppUtil::getTexts("battle_text_tutRevP");

        $this->replaceStrings['tutClose0'] = AppUtil::getText("battle_text_tutClose0");
        $this->replaceStrings['tutClose1'] = AppUtil::getText("battle_text_tutClose1");
        $this->replaceStrings['tutClose2'] = AppUtil::getText("battle_text_tutClose2");

        $this->replaceStrings['tutClose']= AppUtil::getTexts("battle_text_tutClose");

        //通常の主人公の場合
        $this->replaceStrings['AUTO_SERIFU_UNISON'] =   AppUtil::getText("TEXT_BATTLE_AVATAR_AUTO_SERIFU_UNISON");
        $this->replaceStrings['AUTO_SERIFU_UNISONED'] =    AppUtil::getText("TEXT_BATTLE_AVATAR_AUTO_SERIFU_UNISONED");
        $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK'] =   AppUtil::getText("TEXT_BATTLE_AVATAR_AUTO_SERIFU_STRONG_ATTACK");
        $this->replaceStrings['AUTO_SERIFU_PRUDENCE'] =    AppUtil::getText("TEXT_BATTLE_AVATAR_AUTO_SERIFU_PRUDENCE");
        $this->replaceStrings['AUTO_SERIFU_ABSORPTION'] =    AppUtil::getText("TEXT_BATTLE_AVATAR_AUTO_SERIFU_ABSORPTION");
        $this->replaceStrings['AUTO_SERIFU_MIND_READING'] =    AppUtil::getText("TEXT_BATTLE_AVATAR_AUTO_SERIFU_MIND_READING");
        $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_1'] =   AppUtil::getText("TEXT_BATTLE_AVATAR_AUTO_SERIFU_NO_ABSORPTION_1");
        $this->replaceStrings['AUTO_SERIFU_NO_ABSORPTION_2'] =   AppUtil::getText("TEXT_BATTLE_AVATAR_AUTO_SERIFU_NO_ABSORPTION_2");
        $this->replaceStrings['AUTO_SERIFU_STRONG_ATTACK_DESIDE'] =    AppUtil::getText("TEXT_BATTLE_AVATAR_AUTO_SERIFU_STRONG_ATTACK_DESIDE");
        $this->replaceStrings['AUTO_SERIFU_PRUDENCE_DESIDE'] =    AppUtil::getText("TEXT_BATTLE_AVATAR_AUTO_SERIFU_PRUDENCE_DESIDE");

        $this->replaceStrings['MANUAL_SERIFU_STRONG_ATTACK'] =    AppUtil::getText("TEXT_BATTLE_AVATAR_MANUAL_SERIFU_STRONG_ATTACK");
        $this->replaceStrings['MANUAL_SERIFU_PRUDENCE'] =    AppUtil::getText("TEXT_BATTLE_AVATAR_MANUAL_SERIFU_PRUDENCE");
        $this->replaceStrings['MANUAL_SERIFU_ABSORPTION'] =    AppUtil::getText("TEXT_BATTLE_AVATAR_MANUAL_SERIFU_ABSORPTION");

        if( empty($_GET['from']) ) {
            $this->replaceStrings['navSerif_end'] = AppUtil::getText("battle_text_navSerif_end1");
        }else {
            $this->replaceStrings['navSerif_end'] = AppUtil::getText("battle_text_navSerif_end2");
        }

        // 置換する値を連想配列で設定
        $this->replaceStrings['urlOnEnd'] = Common::genContainerUrl(
            'Api', 'TutorialBattle', array('_self'=>true), true
        );

        $charaSvc = new Character_InfoService();
        $avatar = $charaSvc->needAvatar($this->user_id, true);

        $this->replaceStrings['nameP'] =      Text_LogService::get($avatar['name_id']);
        $this->replaceStrings['nameE'] =      AppUtil::getText("text_log_body_-1100");

        $this->replaceStrings['result'] = "ok";

        return $this->replaceStrings;

    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * チュートリアルバトルが終わったときの処理を行う。
     */
    private function processResult() {

        // 全体チュートリアルの一環として実行されていたなら。
        if( empty($_GET['from']) ) {

            // チュートリアルを次段階へ。
            Service::create('User_Info')->tutorialStepUp($this->user_id, User_InfoService::TUTORIAL_BATTLE);

            // ユーザレコードを取り直す
            $this->userInfo = Service::create('User_Info')->needRecord($this->user_id);

            $array = array();
            //次の遷移先を取得
            $array = $this->getTutorialInfo($array);

            // リダイレクト。
            $this->replaceStrings['urlOnEnd'] = Common::genContainerUrl(
                'Api', $array["nextscene"], array("dramaId" => $array["dramaId"], "tutorial_step" => $this->userInfo['tutorial_step']), true
            );

        // ヘルプから実行されていたならヘルプ一覧へ戻る。
        }else {
            $this->replaceStrings['urlOnEnd'] = Common::genContainerUrl(
                'Api', 'Home', array(), true
            );
        }
    }
}
