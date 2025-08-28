<?php

/**
 * ガチャ購入の決済通知を受け取るアクション。
 */
class DrawGachaAction extends PaymentBaseAction {

    protected function doExecute($paymentData) {
        //ミニセッションからレコードを得る
        $sessSvc = new Mini_SessionService();
        $data = $sessSvc->getData($_GET['dataId']);

        // ガチャからアイテムを引く。
        $items = Service::create('Gacha_Master')->drawItem($paymentData['item_id'], $paymentData['user_id'], $data["count"]);

        // 引いたアイテムをユーザに付与。
        $svc = new User_ItemService();

        $uitemIds = array();
        foreach($items as $item) {
            $uitemIds[] = $svc->gainItem($paymentData['user_id'], $item["item_id"]);
        }

        // dataId で指定されているmini_sessionに結果を格納。        
        $sessSvc->mergeData(array('uitemId'=>$uitemIds), $_GET['dataId']);

        // payment_log.reference_id に、引いたアイテムのIDを格納させる。
        return $items[0]["item_id"];
    }
}
