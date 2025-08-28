<?php

/**
 * 「マルティーニの抜け道」のクエスト
 */
class Quest53002 extends FieldQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてクリアした場合は「マルティーニ城下町」に移動させる。
        if( $success  &&  !$this->isCleared() )
            Service::create('User_Info')->movePlace($this->userId, 33);

        parent::endQuest($success, $code);
    }
}
