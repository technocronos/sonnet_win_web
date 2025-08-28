<?php

/**
 * 海岸洞窟後劇
 */
class Quest14004 extends DramaQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてのクリアなら...
        if( $success  &&  !$this->isCleared() ) {
            $userSvc = new User_InfoService();

            // 故郷の村へ移動させる。
            $userSvc->movePlace($this->userId, 15);
        }

        parent::endQuest($success, $code);
    }
}
