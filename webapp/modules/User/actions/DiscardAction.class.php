<?php

/**
 * 「アイテムを捨てる」を処理するアクション。
 */
class DiscardAction extends UserBaseAction {

    public function execute() {

        $uitemSvc = new User_ItemService();

        // 結果メッセージを表示しようとしているなら、特に処理しない。
        if($_GET['result'])
            return View::SUCCESS;

        // 本当に捨てられるのかチェック。
        $error = $uitemSvc->checkDisposable($this->user_id, $_GET['uitemId'], -1);
        $this->setAttribute('error', $error);

        // 捨てるフォームが送信されているなら...
        if(!$error  &&  $_POST) {

            // 捨てる。
            $uitemSvc->consumeItem($_GET['uitemId'], 0x7FFFFFFF);

            // 結果画面へリダイレクト。
            Common::redirect( array('_self'=>true, 'result'=>1) );
        }

        // 捨てようとしているアイテムの情報を取得。
        $uitem = $uitemSvc->needRecord($_GET['uitemId']);
        $this->setAttribute('uitem', $uitem);

        return View::SUCCESS;
    }
}
