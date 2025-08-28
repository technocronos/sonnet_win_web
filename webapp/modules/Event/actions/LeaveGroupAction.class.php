<?php

/**
 * コミュ抜けた時を受け取るアクション　今の所何もしない。
 */
class LeaveGroupAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        // 途中でエラーが起きたときのために、HTTPレスポンスコードをエラーに設定する。
        header("HTTP/1.0 500 Internal Server Error");

        // HTTPレスポンスコードを成功値に。
        header("HTTP/1.0 200 OK");

        return View::NONE;
    }

}
