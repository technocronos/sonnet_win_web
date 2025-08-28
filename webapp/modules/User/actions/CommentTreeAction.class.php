<?php

class CommentTreeAction extends UserBaseAction {

    public function execute() {

        $histSvc = new History_LogService();

        // �c���[�̃g�b�v�Ƃ��Ďw�肳��Ă��闚�����擾�B
        // �����؂�ł̕����폜�����肦��̂ŁA�Ȃ��Ă��G���[�ɂȂ�Ȃ��悤�ɂ���B
        $top = $histSvc->getExRecord($_GET['top']);
        if($top) {

            // ���M�������[�U�̖��O���擾���Ă���r���[�ϐ��Ɋ��蓖�Ă�B
            $user = Service::create('User_Info')->needRecord($top['user_id']);
            $top['short_user_name'] = $user['short_name'];
            $this->setAttribute('top', $top);
        }

        // ���X�̈ꗗ���擾�B�T���l�C��URL�ƃ��[�U�����擾���Ă���r���[�ϐ��Ɋ��蓖�Ă�B
        $list = $histSvc->getReplies($_GET['top'], 10, $_GET['page']);
        AppUtil::embedUserFace($list['resultset'], 'user_id');
        $this->setAttribute('list', $list);

        return View::SUCCESS;
    }
}
