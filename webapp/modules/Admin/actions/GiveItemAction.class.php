<?php

/**
 * 指定のユーザにアイテムを付与する。
 */
class GiveItemAction extends AdminBaseAction {

    public function execute() {

        // フォームが送信されているならそれを処理する。制御は戻らない。
        if($_POST)
            $this->processPost();

        // 結果を表示するように指定されている場合は、誰に、何を付与したのか取得する。
        if($_GET['result']) {
            list($userId, $itemId, $amount) = explode('-', $_GET['result']);
            $result = array();
            $result['user'] = Service::create('User_Info')->getRecord($userId);
            $result['item'] = Service::create('Item_Master')->getRecord($itemId);
            $result['amount'] = $amount;

            $this->setAttribute('result', $result);
        }

        // アイテムの一覧を取得。
        $items = Service::create('Item_Master')->getAllRecords();
        $this->setAttribute('items', ResultsetUtil::colValues($items, 'item_name', 'item_id'));
        $this->setAttribute('amount', 1);

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    private function processPost() {

        // ユーザIDが数値じゃなかったら、プラットフォームユーザIDと判断して変換する。
        $userId = ctype_digit($_POST['userId']) ? $_POST['userId'] : PlatformApi::getInternalUid($_POST['userId']);

        // 指定のユーザが本当にいるのかどうかチェックしてから、アイテム付与。
        if( Service::create('User_Info')->getRecord($userId) ){
            if($_POST['amount'] > 0)
                Service::create('User_Item')->gainItem($_POST['userId'], $_POST['itemId'], $_POST['amount']);
        }

        // 結果画面へ。
        Common::redirect(array(
            '_self' => true,
            'result' => $userId . '-' . $_POST['itemId'] . '-' . $_POST['amount'],
        ));
    }
}
