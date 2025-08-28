<?php

class AdmirersAction extends UserBaseAction {

    public function execute() {

        // 指定されていないURL変数を補う。
        if( empty($_GET['page']) )    $_GET['page'] = '0';

        // 称賛したユーザの一覧を取得。
        $list = Service::create('History_Admiration')->getAdmirerList($_GET['id'], 10, $_GET['page']);

        // ユーザ名、サムネイルURLを取得。
        AppUtil::embedUserFace($list['resultset'], 'admirer_id');

        $this->setAttribute('list', $list);

        return View::SUCCESS;
    }
}
