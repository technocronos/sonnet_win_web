<?php

/**
 * ---------------------------------------------------------------------------------
 * Appsflyer情報を更新する
 * ---------------------------------------------------------------------------------
 */
class AppsflyerAction extends SmfBaseAction {
    // ユーザ登録していなくてもアクセス可能にする。
    protected $guestAccess = true;

    protected function doExecute($params) {

        if( isset($_GET['platform_uid']) ){
            $this->saveForm();
            $array["result"] = "ok";
        }else{
            $array["result"] = "error";
            $array["err_code"] = "no_platform_uid";
        }

        return $array;

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * フォームの入力内容を保存する。
     */
    private function saveForm() {

        // キャラクターレコードを作成。
        $appsflyer = new AppsflyerService();
        $appsflyer->insertRecord(array(
            'platform_uid' => $_GET["platform_uid"],
            'adgroup_id' => $_POST['adgroup_id'],
            'adset' => $_POST['adset'],
            'adset_id' => $_POST['adset_id'],
            'af_siteid' => $_POST['af_siteid'],
            'af_status' => $_POST['af_status'],
            'agency' => $_POST['agency'],
            'campaign' => $_POST['campaign'],
            'campaign_id' => $_POST['campaign_id'],
            'click_time' => $_POST['click_time'],
            'http_referrer' => $_POST['http_referrer'],
            'install_time' => $_POST['install_time'],
            'media_source' => $_POST['media_source'],
            'retargeting_conversion_type' => $_POST['retargeting_conversion_type'],
            'af_channel' => $_POST['af_channel'],
        ));

    }

}
