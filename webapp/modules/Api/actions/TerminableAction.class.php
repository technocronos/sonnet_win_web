<?php

class TerminableAction extends ApiDramaBaseAction {

    protected function onExecute() {

        $quest_id = $_GET["questId"];
        $sphere_id = $_GET["sphereId"];

        // 再生する寸劇のIDを設定。
        $this->dramaId = $quest_id . '00';

        $array['result'] = "ok";
        $array['skip'] = false;

        if($sphere_id != null){
            //すでに出発している場合は再生してから再開
            $endTo = Common::genURL('Api', 'Sphere', array('id'=>$sphere_id, 'reopen' => 'resume'));
        }else{
            // 戻り先URLを取得。
            $endTo = Common::genURL('Api', 'Ready', array('questId'=>$quest_id));

            // 寸劇レコードがない、あるいはすでにクエストにトライしたことがあるならスキップする。
            if(!Service::create('Drama_Master')->getRecord($this->dramaId)
                ||  Service::create('Flag_Log')->getValue(Flag_LogService::TRY_COUNT, $this->user_id, $quest_id)
            ) {
                $array['skip'] = true;
            }
        }

        $array['nextscene'] = $endTo;
        $array["dramaId"] = $this->dramaId;

        // 戻り先の設定。
        return $array;
    }
}
