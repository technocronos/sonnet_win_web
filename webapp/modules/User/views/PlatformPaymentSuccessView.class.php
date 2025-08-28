<?php

class PlatformPaymentSuccessView extends UserBaseView {



    //-----------------------------------------------------------------------------------------------------
    /**
     * executeをオーバーライド。
     */
    public function execute () {

        BaseView::setTemplate('PlatformPaymentSuccess');

        // あとは親に任せる。
        return parent::execute();
    }
}
