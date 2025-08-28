<?php

class TestPaySuccessView extends UserBaseView {

    //-----------------------------------------------------------------------------------------------------
    /**
     * executeをオーバーライド。
     */
    public function execute () {

        BaseView::setTemplate('TestPaySuccess');

        // あとは親に任せる。
        return parent::execute();
    }
}
