<?php

/**
 * 出航
 */
class Quest14005 extends DramaQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてのクリアなら...
        if( $success  &&  !$this->isCleared() ) {
            $userSvc = new User_InfoService();

            // エルフの森へ移動させる。
            $userSvc->movePlace($this->userId, 21);
        }

        parent::endQuest($success, $code);
    }
}
