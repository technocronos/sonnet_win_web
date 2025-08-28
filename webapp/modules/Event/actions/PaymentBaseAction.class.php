<?php

/**
 * 課金決済完了通知を受け取るアクションの共通クラス。
 * 派生クラスでは、doExecute() を定義して処理を行う。
 */
abstract class PaymentBaseAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    /**
     * 派生クラスでオーバーライドして、課金決済が完了したときのアイテム付与処理などを記述する。
     *
     * @param array     決済されたpayment_logレコード。
     * @return int      該当のpayment_logレコードの reference_id 列に格納したい値。
     */
    abstract protected function doExecute($paymentData);


    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        $paymentSvc = new Payment_LogService();

        // 途中でエラーが起きたときのために、HTTPレスポンスコードをエラーに設定する。
        header("HTTP/1.0 500 Internal Server Error");

        // プラットフォームから送られてきたデータを解析して正規化した値を取り出す。
        $params = PlatformApi::parsePayment();

        // 結果コードの意味が不明ならエラー。
        if($params['result'] == 'unknown')
            throw new MojaviException('決済結果通知の結果コードの意味が予期しない値だった');

        // 決済データを受信状態にして、購入内容を取得する。
        $paymentData = $paymentSvc->receivePayment(
            $params['paymentId'], $params['result'], $params['data']
        );

        // 処理済みでない場合
        if($paymentData){
            // 決済結果が "完了" ならば、処理する。
            if($params['result'] == 'ok') {

                // 個別の処理を行う。
                $referenceId = $this->doExecute($paymentData);

                // 決済データを完了状態に。
                $paymentData = $paymentSvc->succeedPayment($params['paymentId'], $referenceId);
            }
        }

        // HTTPレスポンスコードを成功値に。
        header("HTTP/1.0 200 OK");

        // 以下、mixiで必要。
        header("Content-Type: text/plain");
        echo 'OK';

        return View::NONE;
    }
}
