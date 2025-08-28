<?php

/**
 * 課金アイテム購入の決済通知を受け取るアクション。
 */
class BuyItemAction extends PaymentBaseAction {

    protected function doExecute($paymentData) {

        // アイテム付与。
        $svc = new User_ItemService();
        $uitemId = $svc->gainItem($paymentData['user_id'], $paymentData['item_id'], $paymentData['amount']);

        // dataId で指定されているmini_sessionに結果を格納。
        $sessSvc = new Mini_SessionService();
        $sessSvc->mergeData(array('uitemId'=>$uitemId), $_GET['dataId']);
    }
}
