<?php

/**
 * 「西の森へ」のクエスト
 */
class Quest12002 extends FieldQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてクリアした場合は「牧場」に移動させる。
        if( $success  &&  !$this->isCleared() )
            Service::create('User_Info')->movePlace($this->userId, 13);

        parent::endQuest($success, $code);
    }
}
