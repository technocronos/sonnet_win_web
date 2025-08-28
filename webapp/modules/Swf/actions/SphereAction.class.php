<?php

class SphereAction extends SphereBaseAction {

    protected function onExecute() {

        // 指定されたスフィアの情報をロード。
        $this->record = Service::create('Sphere_Info')->needRecord($_GET['id']);

        // 他人のスフィアならエラー。
        if($this->record['user_id'] != $this->user_id)
            throw new MojaviException('他人のスフィアをロードしようとした');

        // その他の情報をセット。
        $this->replaceStrings['suspUrl'] = Common::genContainerUrl('Swf', 'Main', null, true);
        $this->replaceStrings['reloadUrl'] = Common::genContainerUrl('Swf', 'Sphere', array('id'=>$_GET['id'], 'reopen'=>'resume', '_nocache'=>true), true);
        $this->replaceStrings['transmitUrl'] = TransmitBaseAction::getTransmitUrl(array('action'=>'SphereCommand', 'id'=>$_GET['id'], 'code'=>$this->record['validation_code']));
        $this->replaceStrings['apShortUrl'] = Common::genContainerUrl('User', 'Suggest', array('type'=>'ap', 'backto'=>ViewUtil::serializeBackto(array('reopen'=>null))), true);
        $this->replaceStrings['readonly'] = 0;

        //スマホ用に追加
        if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone"){
            $this->replaceStrings['containerUrl'] = CONTAINER_URL_PC . "?url=";
            $this->replaceStrings['URL_TYPE'] = URL_TYPE;

            $this->replaceStrings['transmitUrl'] = "?id=" . $_GET['id'] . '&code=' . $this->record['validation_code'];

            //スマホ用ナビゲート
            $this->replaceStrings['NAVI_SELECT_COMMAND']  = "コマンドを選ぼう";
            $this->replaceStrings['NAVI_SELECT_DESTINATION'] = "タップで移動先を選んでOK！";
            $this->replaceStrings['NAVI_SELECT_ATTACK'] = "タップで攻撃先を選んでOK！";
            $this->replaceStrings['NAVI_SELECT_ITEMUSE'] = "タップでアイテム使用先を選んでOK！";
            $this->replaceStrings['NAVI_SELECT_ITEMSELECT'] = "タップでアイテムを選んでOK！";
            $this->replaceStrings['NAVI_SELECT_MOVE_AFTER'] = "移動先の行動を選ぼう";
        }

        if($this->record["quest_id"] == 11001) {
            // ユーザが精霊の洞窟の場合はナビを詳しく。
            $this->replaceStrings['NAVI_SELECT_COMMAND']  = "コマンドを選ぼう。まずは「移動」↑";
            $this->replaceStrings['NAVI_SELECT_DESTINATION'] = "タップで移動先を選んでOK↑↑　　　　　";
            $this->replaceStrings['NAVI_SELECT_MOVE_AFTER'] = "「ここに移動」をタップ↑↑↑　　　";
        }else if($this->record["quest_id"] == 11002){
            // ユーザが水汲みの日々の場合はナビを詳しく。
            $this->replaceStrings['NAVI_SELECT_MOVE_AFTER'] = "移動先でもアイテム使えるよ！";
            $this->replaceStrings['NAVI_SELECT_ITEMSELECT'] = "装備を選んだらクエ中でも装備できるよ！";
        }

        $this->html_list["SphereItemList"] = MO_HTDOCS . "/html/SphereItemList.html";
        $this->setAttribute('html_list', $this->html_list);

        // スフィア再開方法をセット。
        $this->reopenMethod = $_GET['reopen'] ?: 'continue';

        //サウンド設定。効果音はweb_audio_apiを使う
        $this->use_web_audio = array(
            "se_btn",
            "se_hit",
            "se_damage",
            "se_explosionshort",
            "se_pallet_rotate",
            "se_explosionlong",
            "se_repair",
            "se_flash",
            "se_thunder",
            "se_zazaza",
            "se_gotoquest",
            "se_pallet_fall",
            "se_scream",
        );

        //ボイスはそのルームに必要なものだけを読み込む
        //ボイスの命名規約は v_クエストID_ルームID になる。
        //swfbaseの$this->web_audio_listにボイスを追加すること。
        foreach($this->web_audio_list as $key => $val){
            $voicestr = "v_" . $this->record["quest_id"] . "_" . $this->record["state"]["current_room"];

            if(strpos($key, $voicestr) === 0){
                $this->use_web_audio[] = $key;
            }
        }

        //BGMが設定されていないならすべてbgm_dungeon
        $bgm = "bgm_dungeon";
        if($this->record["state"]["bgm"] != null){
            $bgm = $this->record["state"]["bgm"];
        }

        //BGMはaudioタグを使う
        $this->use_audio_tag = array(
          $bgm
        );

        $this->setAttribute("bgm", $bgm);
    }
}
