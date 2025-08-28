<?php

/**
 * コミュニティ参加通知を受け取るアクション
 */
class JoinCommunityAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        // 途中でエラーが起きたときのために、HTTPレスポンスコードをエラーに設定する。
        header("HTTP/1.0 500 Internal Server Error");

        $flagSvc = new Flag_LogService();
        $uitemSvc = new User_ItemService();

        // 配布定義のインポート
        require_once(MO_WEBAPP_DIR.'/config/values.php');
        $distribute = $DISTRIBUTIONS['tuxZmJ0Y5yix0lO5'];

        // プラットフォームから送られてきたデータを解析してユーザIDを取り出す。
        $userIds = PlatformApi::parseLifeCycleIds();

        // 参加したユーザにマルティーニの槌をプレゼント。
        foreach($userIds as $userId) {

            // すでに配布していないかチェック。
            $presented = $flagSvc->getValue(Flag_LogService::DISTRIBUTION, $userId, $distribute['flag_id']);

            // すでにしているならマークしてスキップ
            if($presented)
                continue;

            // プレゼント。
            foreach((array)$distribute['item_id'] as $itemId)
                $uitemSvc->gainItem($userId, $itemId);

            // プレゼントを受け取ったフラグをONに。
            $flagSvc->flagOn(Flag_LogService::DISTRIBUTION, $userId, $distribute['flag_id']);
        }

        // HTTPレスポンスコードを成功値に。
        header("HTTP/1.0 200 OK");

        return View::NONE;
    }
}
