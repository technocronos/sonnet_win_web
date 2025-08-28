<?php

/**
 */
class SessionExpiredAction extends UserBaseAction {

    // ユーザ登録していなくてもアクセス可能にする。
    protected $guestAccess = true;

    public function execute() {

        $this->setAttribute('CONTAINER_URL', CONTAINER_URL_PC);

        return 'Success';
    }
}
