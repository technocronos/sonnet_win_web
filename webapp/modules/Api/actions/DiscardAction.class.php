<?php

/**
 * 「アイテムを捨てる」を処理するアクション。
 */
class DiscardAction extends SmfBaseAction {

    protected function doExecute($params) {

        $array = array();

        $uitemSvc = new User_ItemService();

        // 本当に捨てられるのかチェック。
        $error = $uitemSvc->checkDisposable($this->user_id, $_GET['uitemId'], -1);

        // 捨てられないならエラー。
        if($error) {
            $array['result'] = 'error';
            $array['err_code'] = $error;
            return $array;
        }

        // 捨てる。
        $uitemSvc->consumeItem($_GET['uitemId'], 0x7FFFFFFF);

        $array['result'] = 'ok';

        return $array;
    }
}
