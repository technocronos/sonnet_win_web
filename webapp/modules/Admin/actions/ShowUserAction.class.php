<?php

/**
 * プラットフォームにユーザデータを問い合わせてみる。
 * デバックメニュー。
 */
class ShowUserAction extends AdminBaseAction {

    public function execute() {

        if(isset($_GET['id'])) {

            $this->setAttribute('response', PlatformApi::queryProfile($_GET['id']));
        }

        return View::SUCCESS;
    }
}
