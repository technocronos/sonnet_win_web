<?php

class OshiraseDetailAction extends UserBaseAction {

    // ユーザ登録していなくてもアクセス可能にする。
    protected $guestAccess = true;


    public function execute() {

        $entry = Service::create('Oshirase_Log')->needRecord($_GET['id']);
        $this->setAttribute('item', $entry);

        $this->setAttribute('title', ($entry['importance'] == 0) ? '日誌' : 'お知らせ');

        return View::SUCCESS;
    }
}
