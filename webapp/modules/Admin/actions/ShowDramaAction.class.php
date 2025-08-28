<?php

/**
 * 寸劇チェック用。
 * デバックメニュー。
 */
class ShowDramaAction extends AdminBaseAction {

    public function execute() {

        $lang = array(0=>"jp", 1=>"en");

        $this->setAttribute('lang', $lang);
        return View::SUCCESS;
    }
}
