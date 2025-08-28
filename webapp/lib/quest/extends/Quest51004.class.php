<?php

/**
 * 「夢魔の世界」のクエスト
 */
class Quest51004 extends FieldQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてクリアした場合は「霊子力研究所」に移動させる。
        if( $success  &&  !$this->isCleared() )
            Service::create('User_Info')->movePlace($this->userId, 34);

        parent::endQuest($success, $code);
    }
}
