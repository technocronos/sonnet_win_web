<?php

/**
 * 「コバイヤで蟲退治」のクエスト
 */
class Quest21009 extends FieldQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてクリアした場合は「トロルの里」に移動させる。
        if( $success  &&  !$this->isCleared() )
            Service::create('User_Info')->movePlace($this->userId, 24);

        parent::endQuest($success, $code);
    }
}
