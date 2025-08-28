<?php

/**
 * インストール通知を受け取るアクション
 */
class AddAppAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        // 途中でエラーが起きたときのために、HTTPレスポンスコードをエラーに設定する。
        header("HTTP/1.0 500 Internal Server Error");

/*
        // プラットフォームから送られてきたデータを解析してユーザIDを取り出す。
        $ids = PlatformApi::parseLifeCycleIds();

        // 招待者がいる場合は招待処理を行う。
        if($_GET['wakuwaku_invite_from']) {
            foreach($ids as $id)
                $this->processInvitation($id, PlatformApi::getInternalUid($_GET['wakuwaku_invite_from']));
        }
*/
        // HTTPレスポンスコードを成功値に。
        header("HTTP/1.0 200 OK");

        return View::NONE;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定されたユーザの招待処理を行う。
     *
     * @param int   招待受領者
     * @param int   招待実行者
     */
    private function processInvitation($acceptor, $inviter) {

        $svc = new InvitationLog();

        // 招待テーブルにレコード作成。
        $svc->makeInvitation($inviter, $acceptor);

        // ユーザ一意性に問題がないなら、友だち招待応諾の処理を行う。
        if( (new UserProfile())->getProfile($acceptor, 'unique') )
            $svc->congraturateInvitation($acceptor);
    }
}
