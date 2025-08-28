<?php

class Quest11001 extends FieldQuest {

    //-----------------------------------------------------------------------------------------------------
    /**
     * isExecutableをオーバーライド。
     * tutorial_step だけで判断するようにする。
     */
    public function isExecutable($placeCheck = true) {

        $user = Service::create('User_Info')->needRecord($this->userId);

        return (
               User_InfoService::TUTORIAL_MAINMENU <= $user['tutorial_step']
            && $user['tutorial_step'] <= User_InfoService::TUTORIAL_FIELD
        );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * startFieldをオーバーライド。
     * チュートリアルステップを引き上げるようにする。
     */
    public function startField($charaId, $uitems, $extends = null) {

        Service::create('User_Info')->tutorialStepUp($this->userId, User_InfoService::TUTORIAL_MAINMENU);

        return parent::startField($charaId, $uitems, $extends);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * endQuestをオーバーライド。
     * チュートリアルステップを引き上げるようにする。
     */
    public function endQuest($success, $code) {

        Service::create('User_Info')->tutorialStepUp($this->userId, User_InfoService::TUTORIAL_FIELD);

        parent::endQuest($success, $code);
    }
}
