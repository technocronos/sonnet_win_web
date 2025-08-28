<?php

class Quest31007 extends DramaQuest {


    //-----------------------------------------------------------------------------------------------------
    /**
     * 夢から覚めて・・の特殊処理。endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてのクリアなら...
        if( $success  &&  !$this->isCleared() ) {

            $userSvc = new User_InfoService();

            // ランカスタへ移動させる。
            $userSvc->movePlace($this->userId, 32);
        }

        parent::endQuest($success, $code);
    }
}
