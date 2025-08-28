<?php

/**
 * エルフの里
 */
class Quest21003 extends FieldQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてのクリアなら...
        if( $success  &&  !$this->isCleared() ) {
            $userSvc = new User_InfoService();

            // マルティーニのポートモールへ移動させる。
            $userSvc->movePlace($this->userId, 31);
        }

        parent::endQuest($success, $code);
    }
}
