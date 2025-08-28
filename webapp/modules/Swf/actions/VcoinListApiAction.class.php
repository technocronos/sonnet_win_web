<?php

/**
 * 他人のページを作成する
 */
class VcoinListApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        $list = Service::create('Vcoin_Payment_Log')->getUserList($this->user_id);

        return $list;

    }
}
