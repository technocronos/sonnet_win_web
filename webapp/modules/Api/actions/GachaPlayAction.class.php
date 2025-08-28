<?php

/**
 * 「ガチャのラインナップ」を処理するアクション。
 * 
 * @param gachaId ガチャID
 *        go ガチャ種別　課金：charge マグナ：gold チケット：ticket
 *        count 回数　何連ガチャか 。1か11のいずれか。
 */
class GachaPlayAction extends SmfBaseAction {

    const FREE_GACHA_ID = 9997;

    protected function doExecute($params) {

        $array = array();

        $gachaSvc = new Gacha_MasterService();

        if($_GET['go'] == "free"){
            $array = $this->tryFreeGacha();
            $array["result"] = "ok";
            return $array;
        }

        // 指定されているガチャを本当に購入できるのかチェックしておく。
        if( !$gachaSvc->canPurchase($this->user_id, $_GET['gachaId']) ){
            $array["err_code"] = "cannot_purchase";
            $array["result"] = "ok";
            return $array;
        }

       //マグナ
        if($_GET['go'] == "gold"){
            $array = $this->useGold();
            $array["result"] = "ok";
            return $array;
        //課金
        }else if($_GET['go'] == "charge"){
            $array = $this->processConfirm();
            $array["result"] = "ok";
            return $array;
        //チケット
        }else if($_GET['go'] == "ticket"){
            $array = $this->useFreeticket();
            $array["result"] = "ok";
            return $array;
        }

        // 以下、ガチャの内容を表示する場合。

        // ガチャの詳細と中身のリストを取得。
        $gacha = $gachaSvc->needRecord($_GET['gachaId']);

        $list = $gachaSvc->getContents($_GET['gachaId']);
        if($gacha['gacha_id'] != 9997 && $gacha['gacha_id'] != 9998){
            foreach($list as $row){
                if($set_id != $row['item']['set']['set_id'])
                    $list_set[]['item']['set'] = $row['item']['set'];

                $set_id = $row['item']['set']['set_id'];
            }
            $list = $list_set;
        }

        // フリーチケットの数を取得。
        if($gacha['freeticket_item_id']) {
            $userItemSvc = new User_ItemService();
            $array["freeticketCount"] = $userItemSvc->getHoldCount($this->user_id, $gacha['freeticket_item_id']);
        }

        $array["gacha"] = $gacha;
        $array["list"] = $list;

        return $array;

    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 課金orﾌﾘｰﾁｹｯﾄでまわす を押しているときの処理
     * 課金の場合は決済画面にリダイレクトするので制御は戻らない。
     */
    private function processConfirm() {

        // ガチャのデータを取得。
        $gacha = Service::create('Gacha_Master')->needRecord($_GET['gachaId']);

        // 課金でまわす場合
        if($_GET['go'] == 'charge') {
            //値段設定
            if($_GET['count'] == 1){
                $gachaname = $gacha['gacha_name'];
                $price = $gacha['price'] * $_GET['count'];
            }else if($_GET['count'] == 11){
                $gachaname = $gacha['gacha_name'] . " " .$_GET['count'] . "連";
                $price = $gacha['price_bulk'];
            }


            // coinアイテムのuser_itemレコードを取得。
            $coin_info = Service::create('User_Item')->getRecordByItemId($this->user_id, COIN_ITEM_ID);

            //足りない場合はエラー
            if($coin_info['num'] < $price){
                return array("err_code" => "not_sufficient_coin"); 
            }

            // ガチャからアイテムを一つ引く。
            $items = Service::create('Gacha_Master')->drawItem($_GET['gachaId'], $this->user_id, $_GET["count"]);

            $guaranteed_item_id = 0;

            // foreachで1つずつ値を取り出す
            foreach ($items as $key => $value) {
              $weight[$key] = $value['weight'];
            }

            // array_multisortで'weight'の列を昇順に並び替える
            array_multisort($weight, SORT_DESC, $items);

            // 引いたアイテムをユーザに付与。
            $uitemIds = array();
            foreach($items as $item) {
                if($item["is_guaranteed"] == 1)
                    $guaranteed_item_id = $item["item_id"];

                $uitemIds[] = Service::create('User_Item')->gainItem($this->user_id, $item["item_id"]);
            }

            // コイン減算
            Service::create('User_Item')->consumeItem($coin_info['user_item_id'], $price);

            //ログ作成
            //ガチャの場合は11連でも1個としてログに記録する。
            Service::create('Coin_Log')->insertRecord(array(
                'user_id' => $this->user_id,
                'item_type' => "GC",
                'item_id' => $gacha['gacha_id'],
                'amount' => 1,
                'unit_price' => $price,
            ));

            $backto = ViewUtil::serializeBackto(array('go'=>null));
            $sessId = Service::create('Mini_Session')->setData(array("uitemId" => $uitemIds, 'count'=>$_GET["count"], 'backto'=>$backto, 'guaranteed_item_id' => $guaranteed_item_id, 'gacha_id'=>$gacha['gacha_id']));

            // 結果画面へ。
            return array("nextUrl" => Common::genUrl('Api', 'GachaResult', array('dataId'=>$sessId))); 


        //マグナでまわす場合
        }else if($_GET['go'] == 'gold'){
            // リターンして、確認画面に行く。

        // フリーチケットでまわす場合。
        }else{
            // リターンして、確認画面に行く。
        }
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * マグナ使用でガチャをまわす処理。
     */
    private function useGold() {

        $gachaSvc = new Gacha_MasterService();
        $userItemSvc = new User_ItemService();

        // ガチャの情報を取得。
        $gacha = $gachaSvc->needRecord($_GET['gachaId']);

        if($_GET["count"] > 1)
            $price = $gacha['price_bulk'];
        else
            $price = $gacha['price'];

        //足りない場合はエラー
        if($this->userInfo['gold'] < $price){
            return array("err_code" => "not_sufficient_gold"); 
        }

        // ガチャからアイテムを一つ引く。
        $items = $gachaSvc->drawItem($_GET['gachaId'], $this->user_id, $_GET["count"]);

        // 引いたアイテムをユーザに付与。
        $uitemIds = array();
        foreach($items as $item) {
            $uitemIds[] = $userItemSvc->gainItem($this->user_id, $item["item_id"]);
        }

        // ゲーム内通貨減算
        Service::create('User_Info')->plusValue($this->user_id, array(
            'gold' => -1 * $price,
        ));

        $backto = ViewUtil::serializeBackto(array('go'=>null));
        $sessId = Service::create('Mini_Session')->setData(array("uitemId" => $uitemIds, 'count'=>$_GET["count"], 'backto'=>$backto));

        // 結果画面へ。
        return array("nextUrl" => Common::genUrl('Api', 'GachaResult', array('dataId'=>$sessId))); 
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * フリーチケット使用でガチャをまわす処理。
     */
    private function useFreeticket() {

        $gachaSvc = new Gacha_MasterService();
        $userItemSvc = new User_ItemService();

        // ガチャの情報を取得。
        $gacha = $gachaSvc->needRecord($_GET['gachaId']);

        //枚数チェック
        $freeticketCount = $userItemSvc->getHoldCount($this->user_id, $gacha['freeticket_item_id']);
        // 規定数持ってない場合はとりあえず、ガチャ詳細に飛ばす。
        if($freeticketCount < $gacha['freeticket_count']){
            return array("err_code" => "not_sufficient_ticket"); 
        }

        // フリーチケットアイテムのuser_itemレコードを取得。
        $ticketItem = $userItemSvc->getRecordByItemId($this->user_id, $gacha['freeticket_item_id']);
        // 持ってない場合はとりあえず、ガチャ詳細に飛ばす。
        if(!$ticketItem){
            return array("err_code" => "not_sufficient_ticket"); 
        }
        // ガチャからアイテムを一つ引く。
        $items = $gachaSvc->drawItem($_GET['gachaId'], $this->user_id, $_GET["count"]);

        // 引いたアイテムをユーザに付与。
        $uitemIds = array();
        foreach($items as $item) {
            $uitemIds[] = $userItemSvc->gainItem($this->user_id, $item["item_id"]);
        }

        // フリーチケットを消費。
        $userItemSvc->consumeItem($ticketItem['user_item_id'], $gacha['freeticket_count']);

        $sessId = Service::create('Mini_Session')->setData(array("uitemId" => $uitemIds, 'count'=>$_GET["count"], 'backto'=>$backto));

        // 結果画面へ。
        return array("nextUrl" => Common::genUrl('Api', 'GachaResult', array('dataId'=>$sessId))); 
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * フリーガチャをまわす処理。
     */
    private function tryFreeGacha() {

        // 今日、もう回しているかどうかを取得。ただし、チュートリアル中なら無条件で回せるようにする。
        if($this->userInfo['tutorial_step'] == User_InfoService::TUTORIAL_GACHA) {
            $tryable = true;
        }else{
            $lastTry = Service::create('User_Property')->getProperty($this->user_id, 'free_gacha_date');
            $tryable = ( $lastTry < (int)date('Ymd') );
        }

        // 持ってない場合はとりあえず、ガチャ詳細に飛ばす。
        if(!$tryable){
            return array("err_code" => "not_tryable"); 
        }

        $gachaSvc = new Gacha_MasterService();
        $userItemSvc = new User_ItemService();

        // ガチャチュートリアル中ならステップアップ
        Service::create('User_Info')->tutorialStepUp($this->user_id, User_InfoService::TUTORIAL_GACHA);

        // ガチャからアイテムを一つ引く。ただしチュートリアル中は無条件で時計を出す。
        if($this->userInfo['tutorial_step'] == User_InfoService::TUTORIAL_GACHA){
            //$itemId = 1902;
            // ガチャからアイテムを一つ引く。
            $itemIds = $gachaSvc->drawItem($_GET['gachaId'], $this->user_id, $_GET["count"]);
            $itemId = $itemIds[0]["item_id"];
        }else{
            $itemIds = $gachaSvc->drawItem(self::FREE_GACHA_ID, $this->user_id);
            $itemId = $itemIds[0]["item_id"];
        }

        // 引いたアイテムをユーザに付与。
        $uitemIds = array();
        $uitemIds[] = $userItemSvc->gainItem($this->user_id, $itemId);

        // チュートリアル中でなければ、無料ガチャを引いた記録をつける。
        if($this->userInfo['tutorial_step'] != User_InfoService::TUTORIAL_GACHA){
            Service::create('User_Property')->updateProperty($this->user_id, 'free_gacha_date', date('Ymd'));

            $medal_result = "";
            //ゲソてんの場合
            if(PLATFORM_TYPE == "geso"){
                $response = GesoApi::postMedalEvent(1, GesoApi::MEDAL_EVENT_TYPE);
                Common::varLog($response);
                if($response["entry"])
                    $medal_result = $response["entry"]["responseCode"];
            }
        }

        $sessId = Service::create('Mini_Session')->setData(array("uitemId" => $uitemIds, 'count'=>$_GET["count"], 'medal_result'=>$medal_result, 'backto'=>$backto));

        // 結果画面へ。
        return array("nextUrl" => Common::genUrl('Api', 'GachaResult', array('dataId'=>$sessId))); 
    }
}
