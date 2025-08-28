<?php

/**
 * アイテム使用で、使うアイテム(uitemId)と使用対象(targetId)が決まっている状態で
 * 実行されるアクション。
 */
class ItemExchangeAction extends SmfBaseAction {

   protected function doExecute($params) {

        $array = array();

        $uitemSvc = new User_ItemService();
        $setSvc = new Set_MasterService();

        // 削除して新たに付与する
        Service::create('User_Item')->deleteRecord($_GET['uitemId']);
        $newid = $uitemSvc->gainItem($this->user_id, $_GET['targetId']);

        // 差し替えアイテムの情報を取得。
        $array["exchange"] = $uitemSvc->needRecord($newid);
        $array["exchange"]['set'] = $setSvc->getRecord($array["exchange"]['set_id']);
        $array["exchange"]['guaranteed_flg'] = 1;

        $array["result"] = "ok";

        return $array;
    }
}
