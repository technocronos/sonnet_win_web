<?php

/**
 * JSONチェック用。
 * デバックメニュー。
 */
class CheckJsonAction extends AdminBaseAction {

    public function execute() {

        // フォームが送信されている場合。
        if($_POST) {

            $result = json_decode($_POST['json'], true);

            $this->setAttribute('error', json_last_error() != JSON_ERROR_NONE);
            $this->setAttribute('result', $result);
        }

        return View::SUCCESS;
    }
}
