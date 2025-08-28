<?php

/**
 * アイテム使用で、使うアイテム(uitemId)と使用対象(targetId)が決まっている状態で
 * 実行されるアクション。
 * 必要なら確認画面(兼エラー画面)を表示する。
 */
class ItemUseFireAction extends UserBaseAction {

    public function execute() {

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
        $this->setAttribute('error', $error);

        // 使用できないなら確認(エラー)画面へ。
        if($error) {
            $this->processConfirm($targetId);
            return 'Confirm';
        }

        // 確認不要な場合は使用処理。
        if($uitem['item_type'] == Item_MasterService::REPAIRE) {
            $this->processUse($targetId);
        }

        // すでに確認画面を経由しているなら使用処理。
        if($_GET['go']) {
            $this->processUse($targetId);
        }

        // ここまできたら確認画面を表示。
        $this->processConfirm($targetId);
        return 'Confirm';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * アイテムを選択して、確認画面を表示するときの処理
     */
    private function processConfirm($targetId) {

        // 使おうとしているアイテムの情報を取得。
        $uitem = Service::create('User_Item')->needRecord($_GET['uitemId']);
        $this->setAttribute('uitem', $uitem);

        // ステータスアップアイテムを使おうとしている場合はあと何回使えるのかを表示する。
        if($uitem['item_type'] == Item_MasterService::INCR_PARAM) {
            $useCount = Service::create('Flag_Log')->getValue(Flag_LogService::PARAM_UP, $targetId, $uitem['item_id']);
            $this->setAttribute('useCount', $useCount);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * アイテムを使用するときの処理。
     */
    private function processUse($targetId) {

        // GETメソッドで使用できるので、簡単ながらCSRF対策をする。
        if( !Common::validateSign() )
            Common::redirect('User', 'ItemList');

        // 使用。使用時の効果を取得する。
        $result = Service::create('User_Item')->useItem($_GET['uitemId'], $targetId);

        // 結果画面へ。
        Common::redirect('User', 'Status', array('result'=>'item'));
    }
}
