<?php

/**
 * 「ギブアップをする」を処理するアクション。
 */
class NoticeApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        // 指定されていないURL変数を補う。
        if( empty($_GET['page']) )  $_GET['page'] = '0';

        // パラメータのデフォルト値を設定。
        $count = 30;

        // お知らせのリストを取得。
        $svc = new Oshirase_LogService();
        $notice = $svc->getList(array('type'=>'notice'), $count, $_GET['page']);

        return $notice;

    }
}
