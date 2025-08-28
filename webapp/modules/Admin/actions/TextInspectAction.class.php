<?php

/**
 * プラットフォームに送信した監査対象テキストを参照／削除する。
 * デバックメニュー。
 */
class TextInspectAction extends AdminBaseAction {

    public function execute() {

        if(isset($_GET['id']))
            $this->setAttribute('response', PlatformApi::getText($_GET['id']));

        if(isset($_POST['id']))
            $this->setAttribute('response', PlatformApi::deleteText($_POST['id']));

        return View::SUCCESS;
    }
}
