<?php
/**
 * 「霊子力研究所後劇」のクエスト
 */
class Quest31032 extends DramaQuest {


    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてのクリアなら...
        if( $success  &&  !$this->isCleared() ) {

            $userSvc = new User_InfoService();

            // 廃棄物処理場へ移動させる。
            $userSvc->movePlace($this->userId, 53);
        }

        parent::endQuest($success, $code);
    }
}

