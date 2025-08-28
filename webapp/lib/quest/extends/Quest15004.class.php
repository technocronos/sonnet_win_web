<?php

/**
 * 愛思い出して
 */
class Quest15004 extends FieldQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてのクリアなら...
        if( $success  &&  !$this->isCleared() ) {
            $userSvc = new User_InfoService();

            // 師匠の家へ移動させる。
            $userSvc->movePlace($this->userId, 11);
        }

        parent::endQuest($success, $code);
    }
}
