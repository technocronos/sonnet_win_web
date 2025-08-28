<?php

/**
 * 「トロルの霊廟」のクエスト
 */
class Quest21011 extends FieldQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてクリアした場合は「エルフの森」に移動させる。
        if( $success  &&  !$this->isCleared() )
            Service::create('User_Info')->movePlace($this->userId, 21);

        parent::endQuest($success, $code);
    }
}
