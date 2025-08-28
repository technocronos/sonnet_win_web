<?php

/**
 * フィールド結果を構成するアクション。
 * 
 */
class GachaResultAction extends SwfBaseAction {

    protected function doExecute() {

        // GET変数 dataId が指定されている場合は、そこからパラメータを取り出す。
        if( !empty($_GET['dataId']) ) {
            $data = Service::create('Mini_Session')->getData($_GET['dataId']);
            $_GET['backto'] =  isset($data['backto']) ? $data['backto'] : null;
            $_GET['uitemId'] = isset($data['uitemId']) ? $data['uitemId'] : null;
            $_GET['nextUrl'] = isset($data['nextUrl']) ? $data['nextUrl'] : null;
            $_GET['coin'] =    isset($data['coin']) ? $data['coin'] : null;
        }

        // 課金で購入している場合に決済完了しているかどうかのチェック。
        if( !PlatformApi::validatePayment() )
            Controller::getInstance()->redirect($_GET['backto'] );

        // 購入したアイテムの user_item レコードを取得。
        $svc = new User_ItemService();

        foreach($data["uitemId"] as $key => $uitemId){
            $itm = $svc->getRecord($uitemId);

            // 取得できない場合はエラーページに飛ばす。
            if(!$itm)
                Common::redirect('User', 'Static', array('id'=>'Timeout'));

            // 他人のアイテムだったらエラー。
            if($itm['user_id'] != $this->user_id)
                throw new MojaviException('他人のアイテムで取得ページを表示しようとした');

      			$set =  Service::create('Set_Master')->getRecord($itm["set_id"]);

            $itm["set_name"] = $set["set_name"];
            $itm["set_text"] = $set["set_text"];
            $itm["rear_id"] = $set["rear_id"];

            $itm["effect"] = AppUtil::itemEffectStr($itm);

            $uitem[] = $itm;
        }

        $this->arrayToFlasm('getitem_', $uitem);
        $this->replaceStrings['gacha_count'] = $data["count"];

        // チュートリアル中の場合。
        if($this->userInfo['tutorial_step'] < User_InfoService::TUTORIAL_END) {
            $this->replaceStrings['urlOnMain'] = Common::genContainerUrl('Swf', 'Main', array(), true);
        }else{
            $this->replaceStrings['urlOnMain'] = Common::genContainerUrl('Swf', 'Main', array("firstscene" => "gacha"), true);
        }


        $this->img_list["circle_bg"] = "img/parts/sp/preload/circle_bg.png";
        $this->setAttribute('img_list', $this->img_list);

        //サウンド設定。
        $this->use_web_audio = array(
            "se_btn",
            "se_consolidation",
            "se_congrats",
            "se_coin",
        );
        $this->use_audio_tag = array(
            "bgm_mute",
        );

    }
}
