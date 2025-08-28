<?php

/**
 * 「シショーに報告その２」のドラマクエスト
 */
class Quest11005 extends DramaQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてのクリアなら...
        if( $success  &&  !$this->isCleared() ) {

            $userSvc = new User_InfoService();

            // ツクール海岸へ移動させる。
            $userSvc->movePlace($this->userId, 14);
        }

        parent::endQuest($success, $code);
    }
}
