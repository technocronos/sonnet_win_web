<?php

/**
 * Swf���W���[���̋��ʃr���[�B�X�}�z�p
 */
class SwfBaseView extends BaseView {

    public function execute()
    {

        $request = $this->getContext()->getRequest();

        // �e���v���[�g�͂�����g���B
        $this->setTemplate('BaseContainer');

        // Request�I�u�W�F�N�g�ɃZ�b�g����Ă��� Attribute �����ׂăe���v���[�g�ɓ`�d����B
        foreach($request->getAttributeNames() as $attrName) {
            $this->setAttribute($attrName, $request->getAttribute($attrName));
        }

    }

}
