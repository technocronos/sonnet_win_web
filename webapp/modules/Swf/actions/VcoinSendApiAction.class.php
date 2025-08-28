<?php

/**
 * 他人のページを作成する
 */
class VcoinSendApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        $amount = (float)$_GET['amount'];
        $curr_vcoin = (float)$this->userInfo["virtual_coin"];

        //出金停止
        if(BTC_CAMPAIGN_PAYMENT_STOP)
            return array("result" => "payment_stop");

        //キャンペーンしていない
        if(!VCOIN_RELEASE_FLG)
            return array("result" => "canpain_stop");

        // 出金アドレスが無い
        if($_GET['address'] == "")
            return array("result" => "no_address");

        // 出金アドレスがbitcoinのものではない
        if(!AddressValidator::isValid($_GET['address']))
            return array("result" => "invalid_address");

        // ユーザーインフォが無い
        if(!$this->userInfo) 
            return array("result" => "no_user");

        // 最低出金料以下
        if($curr_vcoin <= VCOIN_MINIMAM){
            return array("result" => "short_amount");            
        }

        // 全額出金なので同じでないと数が合わない
        if($curr_vcoin != $amount){
            return array("result" => "invalid_amount");
        }

        //これまでの課金額を得る
        $payment = Service::create('Payment_Log')->sumupUserPayment($this->user_id);

        // 仮想通貨出金可能課金額
        if($payment[0]["sales"] < VCOIN_MINIMAM_PAYMENT){
            return array("result" => "short_payment");
        }

        // 出金ログを作成する
        Service::create('Vcoin_Payment_Log')->insertRecord(array(
            'user_id' => $this->user_id,
            'address' => $_GET['address'],
            'amount' => $_GET['amount'],
            'fee' => VCOIN_FEE,
            'status_update_at' => array('sql'=>'NOW()'),
        ));

        // 仮想通貨減算
        Service::create('User_Info')->plusValue($this->user_id, array(
            'virtual_coin' => -1 * $amount,
        ));

        return array("result" => "ok");

    }
}
