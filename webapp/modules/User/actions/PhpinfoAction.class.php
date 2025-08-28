<?php

class PhpinfoAction extends UserBaseAction {

    public function execute() {

        if(ENVIRONMENT_TYPE == 'test')
            phpinfo();

        return View::NONE;
    }
}
