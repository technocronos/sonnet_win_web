<?php

/**
 * �C���X�g�[���ʒm���󂯎��A�N�V����
 */
class AddAppAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        // �r���ŃG���[���N�����Ƃ��̂��߂ɁAHTTP���X�|���X�R�[�h���G���[�ɐݒ肷��B
        header("HTTP/1.0 500 Internal Server Error");

/*
        // �v���b�g�t�H�[�����瑗���Ă����f�[�^����͂��ă��[�UID�����o���B
        $ids = PlatformApi::parseLifeCycleIds();

        // ���Ҏ҂�����ꍇ�͏��ҏ������s���B
        if($_GET['wakuwaku_invite_from']) {
            foreach($ids as $id)
                $this->processInvitation($id, PlatformApi::getInternalUid($_GET['wakuwaku_invite_from']));
        }
*/
        // HTTP���X�|���X�R�[�h�𐬌��l�ɁB
        header("HTTP/1.0 200 OK");

        return View::NONE;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * �����Ɏw�肳�ꂽ���[�U�̏��ҏ������s���B
     *
     * @param int   ���Ҏ�̎�
     * @param int   ���Ҏ��s��
     */
    private function processInvitation($acceptor, $inviter) {

        $svc = new InvitationLog();

        // ���҃e�[�u���Ƀ��R�[�h�쐬�B
        $svc->makeInvitation($inviter, $acceptor);

        // ���[�U��Ӑ��ɖ�肪�Ȃ��Ȃ�A�F�������҉����̏������s���B
        if( (new UserProfile())->getProfile($acceptor, 'unique') )
            $svc->congraturateInvitation($acceptor);
    }
}
