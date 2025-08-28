<?php

/**
 * cronによって定期的に実行されるアクション。ランキング情報の更新を行う。
 *
 */
class VCoinCheckAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        Common::varLog("仮想通貨申請チェック");

        $list = Service::create('Vcoin_Payment_Log')->getStatus(Vcoin_Payment_LogService::STATUS_INITIAL);

        $count = count($list);

        if($count > 0){
            $to = "sonnet.userhelp@gmail.com";
            $subject = SITE_NAME . "申請チェック";
            $message = "仮想通貨の申請未処理が" . $count . "件あります";
            $headers = "From: yamauchi@t-cronos.co.jp";
            mb_send_mail($to, $subject, $message, $headers); 
        }

        Common::varLog("仮想通貨申請チェック終了 count=" . $count);

        return View::NONE;
    }
}
