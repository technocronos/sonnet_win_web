<?php

/**
 * �R�~�������������󂯎��A�N�V�����@���̏��������Ȃ��B
 */
class LeaveGroupAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        // �r���ŃG���[���N�����Ƃ��̂��߂ɁAHTTP���X�|���X�R�[�h���G���[�ɐݒ肷��B
        header("HTTP/1.0 500 Internal Server Error");

        // HTTP���X�|���X�R�[�h�𐬌��l�ɁB
        header("HTTP/1.0 200 OK");

        return View::NONE;
    }

}
