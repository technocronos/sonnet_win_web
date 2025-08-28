<?php

/**
 * プラットフォームに代わって表示する、決済確認画面。
 * GET変数は以下の通り。
 *
 *      オーダーID
 *      ?order=4b1ecccc-fc22-4d12-b4e0-b37bb6bad0d0
 *
 *      プラットフォームからのレスポンスコード
 *      &code=01
 *
 *      ゲームサーバ側決済確定処理URL。module=Event は省略されている。
 *      &callback=dataId%3DtnxvMp0UTqniLHymMBQNoNV0yssxog4c%26action%3DBuyItem
 *          ⇒  dataId=tnxvMp0UTqniLHymMBQNoNV0yssxog4c&action=BuyItem
 *
 *      決済後遷移先の画面。
 *      &finish=dataId%3DtnxvMp0UTqniLHymMBQNoNV0yssxog4c%26module%3DUser%26action%3DItemGet
 *          ⇒  dataId=tnxvMp0UTqniLHymMBQNoNV0yssxog4c&module=User&action=ItemGet
 *
 *      アイテム名
 *      &name=%E3%83%8B%E3%83%AF%E3%83%88%E3%83%AA%E3%81%AE%E6%99%82%E8%A8%882%E5%80%8B
 *
 *      アイテムイメージURL。/img/ からの相対パス。
 *      &img=item%2F01902.gif
 *
 *      価格
 *      &price=200
 */
class PlatformPaymentAction extends UserBaseAction {

    public function execute() {

        //戻るURLを取得
        $data = Service::create('Mini_Session')->getData($_GET["dataId"]);
        $this->setAttribute('backto', $data['backto']);

        // 購入確定されていないならまずは確認画面を表示。
        if(!$_POST['go'])  return View::SUCCESS;

        // 以降、購入確定されている場合の処理。

        // ゲソてんの決済確定APIをコール。
        $response = PlatformApi::$apiObject->settlePayment($_GET['order'], $_GET['callback']);

        // レスポンスはこんな感じ。
        # array(1) {
        #   ["entry"]=> array(7) {
        #     ["itemId"]=> string(54) "dataId-EW9IQp7vtQvLLsZQGfnrMWuEIKqYI9um_action-BuyItem"
        #     ["appResponseCode"]=> string(2) "ok"
        #     ["orderId"]=> string(36) "08ee7448-1d14-438c-ac79-493f406c21d9"
        #     ["message"]=> string(7) "SUCCESS"
        #     ["appMessage"]=> string(12) "no problem!!"
        #     ["coin"]=> int(86700)
        #     ["responseCode"]=> string(2) "00"
        #   }
        # }

        // エラーになったら確認画面を再表示。
        if($response['entry']['responseCode'] != '00') {
            $_GET['code'] = $response['entry']['responseCode'];
            return View::SUCCESS;
        }

        // ユーザを決済後画面にリダイレクト。
        parse_str($_GET['finish'], $finish);
        $finish['payment_id'] = $response['entry']['orderId'];
        Common::redirect($finish);
    }
}
