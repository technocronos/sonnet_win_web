<?php

/**
 * 仮想通貨換金ログリスト
 */
class VcoinListAction extends SmfBaseAction {

    protected function doExecute($params) {

        $list = Service::create('Vcoin_Payment_Log')->getUserList($this->user_id);

        return array("result" => "ok", "resultset" => $list);
    }
}
