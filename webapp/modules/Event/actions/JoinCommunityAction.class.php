<?php

/**
 * �R�~���j�e�B�Q���ʒm���󂯎��A�N�V����
 */
class JoinCommunityAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        // �r���ŃG���[���N�����Ƃ��̂��߂ɁAHTTP���X�|���X�R�[�h���G���[�ɐݒ肷��B
        header("HTTP/1.0 500 Internal Server Error");

        $flagSvc = new Flag_LogService();
        $uitemSvc = new User_ItemService();

        // �z�z��`�̃C���|�[�g
        require_once(MO_WEBAPP_DIR.'/config/values.php');
        $distribute = $DISTRIBUTIONS['tuxZmJ0Y5yix0lO5'];

        // �v���b�g�t�H�[�����瑗���Ă����f�[�^����͂��ă��[�UID�����o���B
        $userIds = PlatformApi::parseLifeCycleIds();

        // �Q���������[�U�Ƀ}���e�B�[�j�̒Ƃ��v���[���g�B
        foreach($userIds as $userId) {

            // ���łɔz�z���Ă��Ȃ����`�F�b�N�B
            $presented = $flagSvc->getValue(Flag_LogService::DISTRIBUTION, $userId, $distribute['flag_id']);

            // ���łɂ��Ă���Ȃ�}�[�N���ăX�L�b�v
            if($presented)
                continue;

            // �v���[���g�B
            foreach((array)$distribute['item_id'] as $itemId)
                $uitemSvc->gainItem($userId, $itemId);

            // �v���[���g���󂯎�����t���O��ON�ɁB
            $flagSvc->flagOn(Flag_LogService::DISTRIBUTION, $userId, $distribute['flag_id']);
        }

        // HTTP���X�|���X�R�[�h�𐬌��l�ɁB
        header("HTTP/1.0 200 OK");

        return View::NONE;
    }
}
