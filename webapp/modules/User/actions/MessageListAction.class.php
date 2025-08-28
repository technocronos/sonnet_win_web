<?php

class MessageListAction extends UserBaseAction {

    public function execute() {

        // 指定されていないURL変数を補う。
        if(empty($_GET['userId']))  $_GET['userId'] = $this->user_id;
        if(empty($_GET['type']))    $_GET['type'] = 'receive';

        // ユーザの情報を取得。
        $userSvc = new User_InfoService();
        $this->setAttribute('target', $userSvc->needRecord($_GET['userId']));

        // 自分以外の送信履歴を見るのはNG。
        if($_GET['userId'] != $this->user_id  &&  $_GET['type'] == 'send')
            throw new MojaviException('自分以外の送信履歴を見ようとした。');

        // 自分の受信履歴を見る場合は、「チェック済み」にする。
        if($_GET['userId'] == $this->user_id  &&  $_GET['type'] == 'receive') {
            $mesSvc = new Message_LogService();
            $mesSvc->markReceiverChecked($this->user_id);
        }

        return View::SUCCESS;
    }
}
