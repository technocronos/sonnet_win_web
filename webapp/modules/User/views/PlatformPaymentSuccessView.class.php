<?php

class PlatformPaymentSuccessView extends UserBaseView {



    //-----------------------------------------------------------------------------------------------------
    /**
     * execute���I�[�o�[���C�h�B
     */
    public function execute () {

        BaseView::setTemplate('PlatformPaymentSuccess');

        // ���Ƃ͐e�ɔC����B
        return parent::execute();
    }
}
