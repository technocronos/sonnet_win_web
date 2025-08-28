<?php

/**
 * ---------------------------------------------------------------------------------
 * 友達申請を送信する
 * @param companionId　送信対象ID
 *        type receive/send
 * ---------------------------------------------------------------------------------
 */
class ApproachAction extends SmfBaseAction {

    protected function doExecute($params) {

        // 申請確定されているなら、それ用の処理へ。
        if( isset($_POST['approach']) )
            $array = $this->processApproach();

        // 解除確定されているなら、それ用の処理へ。
        if( isset($_POST['dissolve']) )
            $array = $this->processDissolve();

        return $array;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 申請確定を処理する。
     */
    private function processApproach() {

        $apprSvc = new Approach_LogService();

        // 本当に仲間申請を出せるのかチェック。出せないのなら確認画面に戻す。
        $res = $apprSvc->checkApproachable($this->user_id, $_GET['companionId']);
        if( 'ok' != $res )
            return array("result" => "error", "err_code" => $res);

        // 申請レコードを作成。
        $apprSvc->makeApproach($this->user_id, $_GET['companionId']);

        // プラットフォームメッセージを飛ばす。
        $title = sprintf('[%s]仲間申請', SITE_SHORT_NAME);
        PlatformApi::sendMessage($_GET['companionId'], SITE_NAME.'から仲間申請を受けました', $title, Common::genUrl('User', 'ApproachList'));

        // 結果画面へ。
        return array("result" => "ok");
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 解除確定を処理する。
     */
    private function processDissolve() {

        Service::create('User_Member')->dissolveFriend($this->user_id, $_GET['companionId']);

        // 結果画面へ。
        return array("result" => "ok");
    }
}
