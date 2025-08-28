<?php

class DiaryListAction extends UserBaseAction {

    // ユーザ登録していなくてもアクセス可能にする。
    protected $guestAccess = true;


    public function execute() {

        // 指定されていないURL変数を補う。
        if( empty($_GET['page']) )  $_GET['page'] = '0';

        // お知らせのリストを取得。
        $svc = new Oshirase_LogService();
        $this->setAttribute('list', $svc->getList(array('type'=>'diary'), 10, $_GET['page']));

        return View::SUCCESS;
    }
}
