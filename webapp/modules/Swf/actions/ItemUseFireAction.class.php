<?php

/**
 * アイテム使用で、使うアイテム(uitemId)と使用対象(targetId)が決まっている状態で
 * 実行されるアクション。
 */
class ItemUseFireAction extends ApiBaseAction {

    protected function doExecute($params) {

        $array = array();

        $uitemSvc = new User_ItemService();

        // 使おうとしているアイテムの情報を取得。
        $uitem = $uitemSvc->needRecord($_GET['uitemId']);

        // 使用対象が自動的に決まるものは自動取得する。
        switch($uitem['item_type']) {

            // ユーザに対して使うアイテムなら、対象選択は必要ない。
            case Item_MasterService::RECV_AP:
            case Item_MasterService::RECV_MP:
            case Item_MasterService::ATTRACT:
                $targetId = $this->user_id;
                break;

            // キャラクターに対して使うものの場合は、いまのところ必要ない。
            case Item_MasterService::RECV_HP:
            case Item_MasterService::INCR_PARAM:
            case Item_MasterService::DECR_PARAM:
            case Item_MasterService::INCR_EXP:
            case Item_MasterService::DTECH_UPPER:
                $targetId = Service::create('Character_Info')->needAvatarId($this->user_id);
                break;

            // それ以外の場合はGET変数で指定されている必要がある。
            default:
                $targetId = $_GET['targetId'];
        }

        // アイテムを使用できるのかチェック。
        $error = $uitemSvc->checkUsing($this->user_id, $_GET['uitemId'], $targetId);
        // 使用できないならエラー。
        if($error) {
            $array["result"] = "error";
            $array["err_msg"] = $error;

            return $array;
        }

        // 使用処理。
        $array["use"] = $this->processUse($targetId);

        //特殊効果のアイテムを再取得して渡す
        $targetId = Service::create('Character_Info')->needAvatarId($this->user_id);
        $effectExpires = Service::create('Character_Effect')->getEffectExpires($targetId);

        $array["effect"] = $effectExpires;

        $array["result"] = "ok";

        return $array;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * アイテムを使用するときの処理。
     */
    private function processUse($targetId) {

        // 使用。使用時の効果を取得する。
        $result = Service::create('User_Item')->useItem($_GET['uitemId'], $targetId);

        return $result;
    }
}
