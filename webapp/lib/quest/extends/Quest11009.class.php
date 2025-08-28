<?php

/**
 * 「西の森へ」のクエスト
 */
class Quest11009 extends FieldQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてのクリアなら...
        if( $success  &&  !$this->isCleared() ) {

            $userSvc = new User_InfoService();

            // チュートリアルステップを引き上げて、移動チュートリアルへ。
            $userSvc->tutorialStepUp($this->userId, User_InfoService::TUTORIAL_END);

            // 西の森へ移動させる。
            $userSvc->movePlace($this->userId, 12);
        }

        parent::endQuest($success, $code);
    }
}
