<?php

/**
 * 海岸
 */
class Quest14001 extends FieldQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {
        $flagSvc = new Flag_LogService();

        //師匠クエを倒した直後の場合
        $clear_flag = $flagSvc->getValue(Flag_LogService::CLEAR, $this->userId, 11007);
        $flag = $flagSvc->getValue(Flag_LogService::FLAG, $this->userId, 1400100002);

        if( $success  && $clear_flag && !$flag ) {
            $userSvc = new User_InfoService();

            // エルフの森へ移動させる。
            $userSvc->movePlace($this->userId, 21);
        }

        parent::endQuest($success, $code);
    }
}
