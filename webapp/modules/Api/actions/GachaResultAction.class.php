<?php

/**
 * フィールド結果を構成するアクション。
 * 
 */
class GachaResultAction extends SmfBaseAction {

    protected function doExecute($params) {

        $array = array();


        // GET変数 dataId が指定されている場合は、そこからパラメータを取り出す。
        if( !empty($_GET['dataId']) ) {
            $data = Service::create('Mini_Session')->getData($_GET['dataId']);
            $_GET['backto'] =  isset($data['backto']) ? $data['backto'] : null;
            $_GET['uitemId'] = isset($data['uitemId']) ? $data['uitemId'] : null;
            $_GET['nextUrl'] = isset($data['nextUrl']) ? $data['nextUrl'] : null;
            $_GET['coin'] =    isset($data['coin']) ? $data['coin'] : null;
        }

        // 課金で購入している場合に決済完了しているかどうかのチェック。
        if( !PlatformApi::validatePayment() ){
                $array['err_code'] = "InvalidePayment";
                $array['result'] = "error";
                return $array;
        }

        // 購入したアイテムの user_item レコードを取得。
        $svc = new User_ItemService();

        foreach($data["uitemId"] as $key => $uitemId){
            $itm = $svc->getRecord($uitemId);

            // 取得できない場合はエラーページに飛ばす。
            if(!$itm){
                $array['err_code'] = "Timeout";
                $array['result'] = "error";
                return $array;
            }

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

        $array["atari_item"] = null;

        if($data["guaranteed_item_id"] > 0){
            $contents = Service::create('Gacha_Master')->getContentData($data["gacha_id"]);
            foreach($contents as $content) {
                if($content["guaranteed_flg"] == 1){
                    $itm = Service::create('Item_Master')->getRecord($content["item_id"]);
              			$set =  Service::create('Set_Master')->getRecord($itm["set_id"]);

                    $itm["set"]["set_name"] = $set["set_name"];
                    $itm["set"]["set_text"] = $set["set_text"];
                    $itm["set"]["rear_id"] = $set["rear_id"];

                    $array["atari_item"][] = $itm;
                }
            }
        }

        $array["getitem"] = $uitem;
        $array["gacha_count"] = $data["count"];
        $array["guaranteed_item_id"] = $data["guaranteed_item_id"];

        // チュートリアル中の場合。
        if($this->userInfo['tutorial_step'] < User_InfoService::TUTORIAL_END) {
            $array['urlOnMain'] = Common::genContainerUrl('Api', 'Home', array(), true);
        }else{
            $array['urlOnMain'] = Common::genContainerUrl('Api', 'Gacha', array(), true);
        }

        $array["result"] = "ok";

        return $array;

    }
}
