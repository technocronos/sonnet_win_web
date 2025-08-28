<?php

/**
 * ユーザーアイテムを処理するアクション。
 */
class UserItemAction extends SmfBaseAction {

    protected function doExecute($params) {
        $svc = new User_ItemService();
        $array['user_item'] = $svc->getRecord($_GET['user_item_id']);

        $array['user_item']['effect'] = AppUtil::itemEffectStr($uitem);

        $array['result'] = 'ok';

        return $array;

    }
}
