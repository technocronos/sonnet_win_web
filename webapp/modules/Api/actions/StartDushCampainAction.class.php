<?php

class StartDushCampainAction extends ApiDramaBaseAction {


    /*
    9900006の後にスタートダッシュキャンペーン第一段階が終わったことを通知する
    */
    protected function onExecute() {

        // 再生する寸劇のIDを設定。
        $this->dramaId = 9900007;

        if(Service::create('Invitation_Log')->getRecipientCount($this->userInfo["user_id"]) > 0){
            $this->dramaId = 9900008;
        }

        // 戻り先の設定。
        $endTo = Common::genContainerUrl('Api', 'Home', null, true);

        $array['nextscene'] = $endTo;
        $array["dramaId"] = $this->dramaId;
        $array['result'] = "ok";

        // 戻り先の設定。
        return $array;
    }

}
