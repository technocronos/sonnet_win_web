<?php
/*
 * 「ふたたびレジスタンスへ」特殊処理
*/
class Quest41008 extends FieldQuest {


    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてのクリアなら...
        if( $success  &&  !$this->isCleared() ) {

            $userSvc = new User_InfoService();

            // 「流刑地の波止場」へ移動させる。
            $userSvc->movePlace($this->userId, 51);
        }

        parent::endQuest($success, $code);
    }
}
