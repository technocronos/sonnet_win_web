<?php

/**
 * ---------------------------------------------------------------------------------
 * 仲間を探すリストを返す
 * ---------------------------------------------------------------------------------
 */
class MemberSearchAction extends SmfBaseAction {

    protected function doExecute($params) {

        // 一門候補を取得。
        $memberSvc = new User_MemberService();
        $list =  $memberSvc->getCandidateList($this->user_id);

        if($list){
            foreach($list as &$row){
                $row["chara"] = Service::create('Character_Info')->needAvatar($row["user_id"], true);
                $row["player_name"] = Text_LogService::get($row["chara"]['name_id']);
                $row["grade"] = Service::create('Grade_Master')->needRecord($row["chara"]['grade_id']);

                // 画像情報を取得。
                $spec1 = $this->getFormation($row["chara"]);
                $row['equip_info'] = $spec1;

            }
        }
        $array['list'] = $list;

        $array['result'] = 'ok';

        return $array;

    }
}
