<?php

/**
 * 購入でリクエストされるアクション。
 * @param buy      アイテムID
 * @param cat      カテゴリ　'ITM'
 * @param currency 通貨　'gold' or 'coin'
 * @param num      個数
 */
class ShopAction extends SmfBaseAction {

    protected function doExecute($params) {

        $shopSvc = new Shop_ContentService();

        // 指定されていないGETパラメータにデフォルト値をセット。
        if(empty($_GET['cat'])) $_GET['cat'] = 'ITM';
        if(empty($_GET['currency'])) $_GET['currency'] = 'gold';

        // ショップ情報を取得。課金で買う場合は課金ショップ、それ以外は地点マスタから取る。
        // …だったけど、通常ショップは場所に関係なく統一された。
        if($_GET['currency'] == 'coin')
            $shop = Place_MasterService::$SPECIAL_SHOP;
        else
            $shop = Place_MasterService::$NORMAL_SHOP;

        // ちゃんと買えるのかチェック。
        $response = $this->canPurchase($shop['shop_id'], $_GET['buy'], $_GET['num']);

        // 買えるのなら...
        if($response["result"] == "ok") {
            $response = $this->processPurchase($shop['shop_id']);
        }

        // Flashに返す。
        return $response;
    }
    //-----------------------------------------------------------------------------------------------------
    /**
     * ちゃんと買えるのかどうかをチェックする。
     * 買える場合は Shop_ContentService から取得したレコードを返す。買えない場合はNULL。
     * 買えない場合のエラー処理も行っている。
     */
    public function canPurchase($shopId, $itemId, $num = 1) {

        // 個数の妥当性チェック。
        if($num <= 0)
            return array("result" => "no_num");

        // レコード取得。needRecord を使って取得できない、つまり売っていない場合は例外を投げる。
        $record = Service::create('Shop_Content')->needRecord($shopId, $itemId);

        // ユーザのアバターキャラを取得。
        $chara = Service::create('Character_Info')->needAvatar($this->user_id);

        // 開放レベルに到達していないのはエラー。
        if($chara['level'] < $record['unlock_level'])
            return array("result" => "no_level");

        // ゲーム内通貨で買おうとしている場合に、お金が足りない場合はビューにエラーを設定。
        if($shopId != Shop_ContentService::COIN_SHOP  &&  $this->userInfo['gold'] < $record['price'] * $num)
            return array("result" => "no_gold");

        $coin = Service::create('User_Item')->getRecordByItemId($this->user_id,COIN_ITEM_ID);

        // コインで買おうとしている場合に、お金が足りない場合はビューにエラーを設定。
        if(PLATFORM_TYPE == "nati" && $shopId == Shop_ContentService::COIN_SHOP  &&  (int)$coin['num'] < $record['price'] * $num)
            return array("result" => "no_coin");
        // ここまでくればOK
        return array("result" => "ok");
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * アイテムの購入を処理する。
     */
    public function processPurchase($shopId) {

        // レコード取得。needRecord を使って取得できない、つまり売っていない場合は例外を投げる。
        $sale = Service::create('Shop_Content')->needRecord($shopId, $_GET['buy']);

        // 課金で購入の場合
        if($shopId == Shop_ContentService::COIN_SHOP) {

            //ログ作成
            Service::create('Coin_Log')->insertRecord(array(
                'user_id' => $this->user_id,
                'item_type' => "IT",
                'item_id' => $sale['item_id'],
                'amount' => $_GET['num'],
                'unit_price' => $sale['price'],
            ));

            $svc = new User_ItemService();

            // coinアイテムのuser_itemレコードを取得。
            $coin_info = $svc->getRecordByItemId($this->user_id, COIN_ITEM_ID);

            // コイン減算
            $svc->consumeItem($coin_info['user_item_id'], $sale['price'] * $_GET['num']);

            // アイテム付与
            for($i = 0; $i < $_GET['num']; $i++)
                $uitemId = $svc->gainItem($this->user_id, $sale['item_id']);

            //購入後情報
            $array["mount_id"] = $mountId;
            $array["buy_user_item_id"] = $uitemId;
            $array["num"] = $_GET['num'];
            $array["coin"] = (int)$coin_info['num'] + (-1 * $sale['price'] * $_GET['num']);
            $array["price"] = $sale['price'];

            $array["result"] = "ok";

            return $array;

        // ゲーム内通貨の場合
        }else {

            // ショップチュートリアル中なら、ココでステップアップ
            Service::create('User_Info')->tutorialStepUp($this->user_id, User_InfoService::TUTORIAL_SHOPPING);

            // ゲーム内通貨減算
            Service::create('User_Info')->plusValue($this->user_id, array(
                'gold' => -1 * $sale['price'] * $_GET['num'],
            ));

            if($_GET['cat'] == "WPN")
                $mountId = 1;
            else if($_GET['cat'] == "BOD")
                $mountId = 2;
            else if($_GET['cat'] == "HED")
                $mountId = 3;
            else if($_GET['cat'] == "ACS")
                $mountId = 4;
            else
                $mountId = 5;

            $svc = new User_ItemService();

            for($i = 0; $i < $_GET['num']; $i++){
                // アイテム付与
                $uitemId = $svc->gainItem($this->user_id, $sale['item_id']);
                $uItem = $svc->needRecord($uitemId);
                $item = Service::create('Item_Master')->needRecord($uItem['item_id']);
            }

            //購入後マグナ等追加情報
            $array["mount_id"] = $mountId;
            $array["buy_user_item_id"] = $uitemId;
            $array["num"] = $_GET['num'];
            $array["gold"] = ($this->userInfo['gold'] + (-1 * $sale['price'] * $_GET['num']));
            $array["price"] = $sale['price'];

            $array["result"] = "ok";

            return $array;
        }
    }
}
