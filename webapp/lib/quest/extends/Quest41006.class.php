<?php

/**
 * 「レジスタンスお手伝い」のクエスト
 */
class Quest41006 extends FieldQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてのクリアなら...
        if( $success  &&  !$this->isCleared() ) {

            $userSvc = new User_InfoService();

            // レジスタンスのアジトへ移動させる。
            $userSvc->movePlace($this->userId, 46);
        }

        parent::endQuest($success, $code);
    }
}
