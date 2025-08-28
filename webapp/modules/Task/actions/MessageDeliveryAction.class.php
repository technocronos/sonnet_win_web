<?php

/**
 * cronによって定期的に実行されるアクション。
 * 一定量のメッセージ配信を行う。
 */
class MessageDeliveryAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        $delivSvc = new Delivery_LogService();

        // 送信単位数と回数を取得。
        $unit = $_GET['unit'] ?: 20;
        $send = $_GET['send'] ?: 5;

        // 設定された送信回数だけ繰り返す。
        for( ; $send > 0 ; $send--) {

            // 次に配信する内容を取得。
            $delivery = $delivSvc->nextDeliveryInfo($unit);

            // 待機中の配信がなかったら終了。
            if(!$delivery) {
                echo "終了<br />";
                break;
            }

            // メッセージを送信。
            $url = Common::genUrl('User', 'Index', array('_touch'=>'deliv-'.$delivery['open_key']));
            PlatformApi::sendMessage($delivery['user_ids'], $delivery['body'], $delivery['title'], $url);

            // 終了した分を反映。
            $delivSvc->endDelivery($delivery['delivery_id'], $delivery['user_ids']);

            // 送信した内容を出力。
            echo "<pre>\n";
            var_dump($delivery);
            echo "</pre>\n";
        }

        return View::NONE;
    }
}
