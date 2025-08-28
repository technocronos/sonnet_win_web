<?php

class SphereSwfAction extends SphereBaseAction {

    // 管理ページからのチェック用なので、ユーザ登録ナシでアクセスできるようにする。
    protected $guestAccess = true;


    protected function onExecute() {

        // 指定されたスフィアの情報をロード。
        $this->record = Service::create('Sphere_Info')->needRecord($_GET['id']);

        // その他の情報をセット。
        $this->replaceStrings['suspUrl'] = Common::genUrl(array('_self'=>true), null, null, true);
        $this->replaceStrings['reloadUrl'] = Common::genUrl(array('_self'=>true), null, null, true);
        $this->replaceStrings['transmitUrl'] = '';
        $this->replaceStrings['apShortUrl'] = '';
        $this->replaceStrings['readonly'] = 1;
    }
}
