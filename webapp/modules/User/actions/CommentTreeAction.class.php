<?php

class CommentTreeAction extends UserBaseAction {

    public function execute() {

        $histSvc = new History_LogService();

        // ツリーのトップとして指定されている履歴を取得。
        // 期限切れでの物理削除もありえるので、なくてもエラーにならないようにする。
        $top = $histSvc->getExRecord($_GET['top']);
        if($top) {

            // 発信したユーザの名前を取得してからビュー変数に割り当てる。
            $user = Service::create('User_Info')->needRecord($top['user_id']);
            $top['short_user_name'] = $user['short_name'];
            $this->setAttribute('top', $top);
        }

        // レスの一覧を取得。サムネイルURLとユーザ名を取得してからビュー変数に割り当てる。
        $list = $histSvc->getReplies($_GET['top'], 10, $_GET['page']);
        AppUtil::embedUserFace($list['resultset'], 'user_id');
        $this->setAttribute('list', $list);

        return View::SUCCESS;
    }
}
