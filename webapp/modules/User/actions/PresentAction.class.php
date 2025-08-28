<?php

class PresentAction extends UserBaseAction {

    public function execute() {

        $uitemSvc = new User_ItemService();

        // 指定されていないURL変数を補う。
        if( empty($_GET['cat']) )    $_GET['cat'] = 'WPN';

        // この相手にプレゼントできるのかどうかをチェック。出来ないのならここまで。
        $error = Service::create('User_Member')->canPresent($this->user_id, $_GET['companionId']);
        $this->setAttribute('error', $error);
        if($error)
            return View::SUCCESS;

        // プレゼントアイテムが選択されている場合...
        if($_GET['uitemId']) {

            // 本当にプレゼントできるのかチェック。
            $error = $uitemSvc->checkDisposable($this->user_id, $_GET['uitemId']);
            $this->setAttribute('error', $error);

            // プレゼント可能で、確認が取れているならプレゼント処理。
            if(!$error  &&  $_POST)
                $this->processPresent();

            // それ以外は確認画面を表示。

            // 相手の情報を取得。
            $userSvc = new User_InfoService();
            $this->setAttribute('companion', $userSvc->needRecord($_GET['companionId']));

            // プレゼントアイテムを取得。
            $this->setAttribute('uitem', $uitemSvc->needRecord($_GET['uitemId']));

            return 'Confirm';
        }

        // 以降、アイテム選択画面の処理。

        // プレゼント可能なアイテム一覧を取得。
        $condition = array();
        $condition['user_id'] = $this->user_id;
        $condition['category'] = $_GET['cat'];
        $list = $uitemSvc->getHoldList($condition, 6, $_GET['page']);
        $this->setAttribute('list', $list);

        // アイテム選択時のURLをビュー変数にセット。
        $this->setAttribute('itemLink', Common::genContainerURL(array(
            '_self' => true,
            'uitemId' => '--id--',
            'result' => null,
        )));

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * プレゼント確定を処理する。
     */
    private function processPresent() {

        $uitemSvc = new User_ItemService();

        // プレゼントアイテムを取得。
        $uitem = $uitemSvc->needRecord($_GET['uitemId']);

        // アイテム移動。
        if(Item_MasterService::isDurable($uitem['category'])) {
            $uitemSvc->moveDurable($uitem['user_item_id'], $_GET['companionId']);
        }else {
            $uitemSvc->gainItem($_GET['companionId'], $uitem['item_id']);
            $uitemSvc->consumeItem($uitem['user_item_id']);
        }

        // 履歴に記録。
        $historySvc = new History_LogService();
        $historySvc->insertRecord(array(
            'user_id' => $_GET['companionId'],
            'type' => History_LogService::TYPE_PRESENTED,
            'ref1_value' => $this->user_id,
            'ref2_value' => $uitem['item_id'],
        ));

        // 受け取りユーザにプラットフォームメッセージを送る。
        $title = sprintf('[%s]ｱｲﾃﾑﾌﾟﾚｾﾞﾝﾄ', SITE_SHORT_NAME);
        PlatformApi::sendMessage($_GET['companionId'], 'ｱｲﾃﾑﾌﾟﾚｾﾞﾝﾄを受けました｡いますぐ確認してみよう', $title);

        // 結果画面へ。
        Common::redirect(array(
            '_self' => true,
            'uitemId' => '',
            'result' => $uitem['item_id'],
        ));
    }
}
