<?php

/**
 * 「月影の洞窟」のクエスト
 */
class Quest21013 extends FieldQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     */
    public function endQuest($success, $code) {

        // 初めてクリアした場合は「レジスタンスアジト」に移動させる。
        if( $success  &&  !$this->isCleared() )
            Service::create('User_Info')->movePlace($this->userId, 46);

        parent::endQuest($success, $code);
    }
}
