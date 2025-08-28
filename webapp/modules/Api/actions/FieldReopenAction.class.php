<?php

class FieldReopenAction extends SmfBaseAction {

    protected function doExecute($params) {

        $array = [];

        // フィールドクエストに出ていない場合はこの画面は表示不可。
        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);
        $this->setAttribute('sphereId', $avatar['sally_sphere']);
        if( !$avatar['sally_sphere'] )
            $array["Scene"] = "Home";

        // 出てるけど、それは自分のスフィアではない(援護)なら表示不可。
        $sphere = Service::create('Sphere_Info')->needCoreRecord($avatar['sally_sphere']);
        if($sphere['user_id'] != $this->user_id)
            $array["Scene"] = "Home";

        // 一番最初のクエスト(精霊の洞窟)の場合、この画面はスキップする。
        if($sphere['quest_id'] == 11001){
            $array["Scene"] = "Sphere";
            $array["id"] = $avatar['sally_sphere'];
            $array["reopen"] = $avatar['resume'];
        }

        // ギブアップ確認フォームが送信されているならギブアップ処理。
        if( !empty($_POST['giveup']) ){
            $this->processGiveup($avatar['sally_sphere']);
            $array["Scene"] = "FieldEnd";
            $array["sphereId"] = $sphereId;
        }

        $array["result"] = "ok";

        return $array;
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
