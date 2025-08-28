<?php

class Quest31010 extends DramaQuest {


    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてのクリアなら...
        if( $success  &&  !$this->isCleared() ) {

            $userSvc = new User_InfoService();

            // 砂漠へ移動させる。
            $userSvc->movePlace($this->userId, 44);
        }

        parent::endQuest($success, $code);
    }
}
