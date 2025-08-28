<?php

class PhpInfoAction extends AdminBaseAction {

    public function execute() {

        phpinfo();

        return View::NONE;
    }
}
