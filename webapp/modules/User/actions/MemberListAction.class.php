<?php

class MemberListAction extends UserBaseAction {

    public function execute() {

        // 指定されていないURL変数を補う。
        if( empty($_GET['userId']) )  $_GET['userId'] = $this->user_id;
        if( empty($_GET['page']) )    $_GET['page'] = '0';

        // ユーザの情報を取得。
        $userSvc = new User_InfoService();
        $this->setAttribute('target', $userSvc->needRecord($_GET['userId']));

        // ユーザの仲間を取得。
        $memberSvc = new User_MemberService();
        $this->setAttribute('list', $memberSvc->getMemberList($_GET['userId'], 10, $_GET['page']));

        return View::SUCCESS;
    }
}
