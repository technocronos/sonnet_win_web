<?php

class SphereAction extends ApiSphereBaseAction {

    protected function onExecute() {

        // 指定されたスフィアの情報をロード。
        $this->record = Service::create('Sphere_Info')->needRecord($_GET['id']);

        // 他人のスフィアならエラー。
        if($this->record['user_id'] != $this->user_id)
            throw new MojaviException('他人のスフィアをロードしようとした');

        // その他の情報をセット。
        $this->replaceStrings['suspUrl'] = Common::genContainerUrl('Api', 'Home', null, true);
        $this->replaceStrings['reloadUrl'] = Common::genContainerUrl('Api', 'Sphere', array('id'=>$_GET['id'], 'reopen'=>'resume', '_nocache'=>true), true);
        $this->replaceStrings['transmitUrl'] = TransmitBaseApiAction::getTransmitUrl(array('action'=>'SphereCommand', 'id'=>$_GET['id'], 'code'=>$this->record['validation_code']));
        $this->replaceStrings['apShortUrl'] = Common::genContainerUrl('Api', 'Suggest', array('type'=>'ap', 'backto'=>ViewUtil::serializeBackto(array('scene'=>'Sphere' ,'reopen'=>null))), true);
        $this->replaceStrings['readonly'] = 0;

        $this->replaceStrings['validation_code'] = $this->record['validation_code'];

        // レイドダンジョン
        $currentraid = Service::create('Raid_Dungeon')->getCurrent();

        if($currentraid != null){
            if( $currentraid["quest_id"] == $this->record['quest_id'] ){
                // 本当に実行できる状態にあるのかチェック。
                $this->replaceStrings['raid_dungeon'] = $currentraid;
                $this->replaceStrings['raid_dungeon']['status'] = Service::create('Raid_Dungeon')->getStatus($this->replaceStrings['raid_dungeon']);

                $this->replaceStrings['raid_dungeon']['prizelist'] = array();
                $this->replaceStrings['raid_dungeon']['total_count'] = 0;
                $this->replaceStrings['raid_dungeon']['defeat_count'] = 0;

                //実行するレイドダンジョンがある場合
                if($this->replaceStrings['raid_dungeon']['status'] >= Raid_DungeonService::START){
                    $this->replaceStrings['raid_dungeon']['prizelist'] = Service::create('Raid_Prize')->getList($this->replaceStrings['raid_dungeon']['id']);

                    //倒すべきモンスターの数
                    $monSvc = new Monster_MasterService();
                    $monsters = $monSvc->getMonsterList("appearance_area", 0, 1000, 0);
                    $this->replaceStrings['raid_dungeon']['total_count'] = count($monsters["resultset"]);

                    $date = date('Y-m-d', strtotime("now"));
                    $this->replaceStrings['raid_dungeon']['defeat_count'] = Service::create('Raid_Monster_User')->getDefeatCount($this->replaceStrings['raid_dungeon']['id'], $date);
                }
            }
        }

        // スフィア再開方法をセット。
        $this->reopenMethod = $_GET['reopen'] ?: 'continue';

        //ボイスはそのルームに必要なものだけを読み込む
        //ボイスの命名規約は v_クエストID_ルームID になる。
        //swfbaseの$this->web_audio_listにボイスを追加すること。
/*
        foreach($this->web_audio_list as $key => $val){
            $voicestr = "v_" . $this->record["quest_id"] . "_" . $this->record["state"]["current_room"];

            if(strpos($key, $voicestr) === 0){
                $this->use_web_audio[] = $key;
            }
        }
*/

        //BGMが設定されていないならすべてbgm_dungeon
        $bgm = "bgm_dungeon";
        if($this->record["state"]["bgm"] != null){
            $bgm = $this->record["state"]["bgm"];
        }

        $this->replaceStrings["bgm"]  = $bgm;

        //$this->replaceStrings["result"] = "ok";
        //return $this->replaceStrings;
    }
}
