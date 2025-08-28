<?php

/**
 * アンインストール通知を受け取るアクション
 */
class RemoveAppAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        // 途中でエラーが起きたときのために、HTTPレスポンスコードをエラーに設定する。
        header("HTTP/1.0 500 Internal Server Error");

        // プラットフォームから送られてきたデータを解析してユーザIDを取り出す。
        $ids = PlatformApi::parseLifeCycleIds();

        // アンインストール日時をセットする。
        Service::create('User_Info')->setRetire($ids);

        // HTTPレスポンスコードを成功値に。
        header("HTTP/1.0 200 OK");

        return View::NONE;
    }
}
