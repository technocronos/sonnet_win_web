<?php

class TestPaySuccessView extends UserBaseView {

    //-----------------------------------------------------------------------------------------------------
    /**
     * execute���I�[�o�[���C�h�B
     */
    public function execute () {

        BaseView::setTemplate('TestPaySuccess');

        // ���Ƃ͐e�ɔC����B
        return parent::execute();
    }
}
