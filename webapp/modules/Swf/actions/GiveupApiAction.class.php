<?php

/**
 * 「ギブアップをする」を処理するアクション。
 */
class GiveupApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);
        $this->processGiveup($avatar['sally_sphere']);

        return array('result'=>'ok');

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * ギブアップを処理する。
     */
    private function processGiveup($sphereId) {

        // スフィアを閉じる。
        Service::create('Sphere_Info')->closeSphere($sphereId, Sphere_InfoService::GIVEUP);

    }

}
