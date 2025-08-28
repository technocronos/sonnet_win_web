<?php

class HistoryListAction extends UserBaseAction {

    public function execute() {

        // パラメータが省略されていたら補う。
        if(empty($_GET['userId']))  $_GET['userId'] = $this->user_id;

        // タイトルを初期化。
        $title = '';

        // 他人の履歴なら「○○の」を付ける。
        if($_GET['userId'] != $this->user_id) {
            $targetUser = Service::create('User_Info')->needRecord($_GET['userId']);
            $title .= $targetUser['short_name'] . 'の';
        }

        // タイトルをセット。
        $this->setAttribute('title', $title.'履歴');

        return View::SUCCESS;
    }
}
