<?php
/**
 * 「そして、恋の結末は・・」のクエスト
 */
class Quest41007 extends DramaQuest {


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
