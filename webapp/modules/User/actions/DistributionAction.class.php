<?php

class DistributionAction extends UserBaseAction {

    public function execute() {

        // 配布定義のインポート
        require_once(MO_WEBAPP_DIR.'/config/values.php');

        // 存在する配布IDを指定しているのかチェック
        if( !array_key_exists($_GET['id'], $DISTRIBUTIONS) )
            throw new MojaviException('存在しない配布ID');

        // 配布情報を取得。
        $distribute = $DISTRIBUTIONS[ $_GET['id'] ];
        $this->setAttribute('distribute', $distribute);

        // 結果画面を表示する場合は以降の処理は不要。
        if($_GET['result'])
            return View::SUCCESS;

        // すでにプレゼントしていないかチェック。
        $presented = Service::create('Flag_Log')->getValue(
            Flag_LogService::DISTRIBUTION, $this->user_id, $distribute['flag_id']
        );

        // すでにしているならマークしてビューへ。
        if($presented) {
            $this->setAttribute('error', 1);
            return View::SUCCESS;
        }

        // 「受け取る」ボタンが押されているならそれ用の処理。制御は戻ってこない。
        if($_POST)
            $this->processPost($distribute);

        // プレゼントアイテムの一覧を取得。
        $itemSvc = new Item_MasterService();
        $items = array();
        foreach((array)$distribute['item_id'] as $itemId)
            $items[] = $itemSvc->needExRecord($itemId);

        $this->setAttribute('items', $items);

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * プレゼント処理を行う。
     */
    private function processPost($distribute) {

        // プレゼント。
        $uitemSvc = new User_ItemService();
        foreach((array)$distribute['item_id'] as $itemId)
            $uitemSvc->gainItem($this->user_id, $itemId);

        // プレゼントを受け取ったフラグをONに。
        Service::create('Flag_Log')->flagOn(
            Flag_LogService::DISTRIBUTION, $this->user_id, $distribute['flag_id']
        );

        // 結果画面へリダイレクト。
        Common::redirect(array('_self'=>true, 'result'=>1));
    }
}
