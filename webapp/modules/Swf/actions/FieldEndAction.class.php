<?php

/**
 * フィールド結果を構成するアクション。
 * 
 */
class FieldEndAction extends SwfBaseAction {

    protected function doExecute() {

        //ヘッダー構成に必要な情報を得る
        $charaSvc = new Character_InfoService();
        $avatar = $charaSvc->needAvatar($this->user_id, true);
        $expInfo = $charaSvc->getExpInfo($avatar);
        $memberinfo = Service::create('User_Member')->getMemberInfo($this->user_id);

        // ユーザーの情報
        $this->replaceStrings['player_name'] = Text_LogService::get($avatar['name_id']);
        $this->replaceStrings['actionPt'] = (int)$this->userInfo['action_pt'];
        $this->replaceStrings['matchPt'] = (int)$this->userInfo['match_pt'];
        $this->replaceStrings['gold'] = (int)$this->userInfo['gold'];
//Common::varDump($avatar);

        //装備情報は一旦退避（配列が入れ子だとarrayToFlasmで処理できないため）
        $avatar["equip"] = NULL;

        $this->arrayToFlasm('chara_', $avatar);
        $this->arrayToFlasm('member_', $memberinfo);
        $this->arrayToFlasm('exp_', $expInfo);

        //サウンド設定。
        $this->use_web_audio = array(
            "se_btn",
        );
        $this->use_audio_tag = array(
            "bgm_menu",
        );

        //TOPへのURL
        $this->replaceStrings['urlOnTop'] = Common::genContainerUrl('User', 'Index', array(), true);
        //メニューへのURL
        $this->replaceStrings['urlOnMenu'] = Common::genContainerUrl('Swf', 'Main', array(), true);
        //メニュー内クエストへのURL
        $this->replaceStrings['urlOnQuest'] = Common::genContainerUrl('Swf', 'Main', array("firstscene" => "quest"), true);

        $this->replaceStrings['SPHERE_SUCCESS'] = Sphere_InfoService::SUCCESS;
        $this->replaceStrings['SPHERE_ESCAPE'] = Sphere_InfoService::ESCAPE;
        $this->replaceStrings['SPHERE_FAILURE'] = Sphere_InfoService::FAILURE;
        $this->replaceStrings['SPHERE_GIVEUP'] = Sphere_InfoService::GIVEUP;

        $questSvc = Service::create('Quest_Master');

        // スフィア情報をロード。
        $record = Service::create('Sphere_Info')->getRecord($_GET['sphereId']);
        $this->replaceStrings['sphere_result'] = $record['result'];
        if(!$record)
            Common::redirect('Swf', 'Main');

        // 他人のスフィアだったらエラー。
        if($record['user_id'] != $this->user_id)
            throw new MojaviException('他人のスフィアの結果を表示しようとした');

        // まだ終わっていないならエラー。
        if($record['result'] == Sphere_InfoService::ACTIVE)
            throw new MojaviException('まだ終わっていないスフィアの結果を表示しようとした');



        /**
         * スマホの場合はこちらにリダイレクト
         * 
         */
        if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone"){
            Common::redirect('Swf', 'Main', array("firstscene" => "quest", "sphereId" => $_GET['sphereId']));
        }


        // クエスト情報をロード。
        $quest = $questSvc->needRecord($record['quest_id']);
        $this->arrayToFlasm('quest_', $quest);

        // スフィアオブジェクトを作成。
        $sphere = SphereCommon::load($record);

        // フィールドサマリを取得。
        $summary = $sphere->getSummary();
        $this->arrayToFlasm('summary_', $summary);

        // クエスト中に手に入れたアイテムの情報を取得。
        $itemSvc = new Item_MasterService();
        $treasures = array();
        foreach($summary['treasures'] as $itemId)
            $treasures[] = $itemSvc->getExRecord($itemId);

        $this->arrayToFlasm('treasures_', $treasures);
        $this->replaceStrings['treasures_Num'] = count($treasures);

        // 終了後、連続実行したいクエストがあるかどうかをチェック。
        $nextId = $sphere->getNextQuest();
        if($nextId){
            $this->arrayToFlasm('next_', $questSvc->needRecord($nextId));
            $this->replaceStrings['urlOnNext'] = Common::genContainerUrl('Swf', 'QuestDrama', array("questId" => $nextId), true);       
        }else{
            $this->arrayToFlasm('next_', array());
        }

//Common::varlog($this->replaceStrings);

    }
}
