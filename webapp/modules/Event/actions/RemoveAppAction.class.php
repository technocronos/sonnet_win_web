<?php

/**
 * �A���C���X�g�[���ʒm���󂯎��A�N�V����
 */
class RemoveAppAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        // �r���ŃG���[���N�����Ƃ��̂��߂ɁAHTTP���X�|���X�R�[�h���G���[�ɐݒ肷��B
        header("HTTP/1.0 500 Internal Server Error");

        // �v���b�g�t�H�[�����瑗���Ă����f�[�^����͂��ă��[�UID�����o���B
        $ids = PlatformApi::parseLifeCycleIds();

        // �A���C���X�g�[���������Z�b�g����B
        Service::create('User_Info')->setRetire($ids);

        // HTTP���X�|���X�R�[�h�𐬌��l�ɁB
        header("HTTP/1.0 200 OK");

        return View::NONE;
    }
}
