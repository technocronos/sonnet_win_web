<?php

/**
 * GET変数 "id" で指定されたテンプレートを表示するアクション
 */
class StaticAction extends UserBaseAction {

    // ユーザ登録していなくてもアクセス可能にする。
    protected $guestAccess = true;


    public function execute() {

        // 非対応端末の表示を出す場合はナビゲーションメニューを出さない。
        $this->setAttribute('hideNavigator', $_GET['id']=='non-compliant');

        return 'Success';
    }
}
