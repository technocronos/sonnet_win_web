<?php

/**
 * ---------------------------------------------------------------------------------
 * 仲間を探すリストを返す
 * ---------------------------------------------------------------------------------
 */
class MemberSearchApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        // 一門候補を取得。
        $memberSvc = new User_MemberService();
        $list =  $memberSvc->getCandidateList($this->user_id);

        if($list){
            foreach($list as &$row){
                $row["chara"] = Service::create('Character_Info')->needAvatar($row["user_id"], true);
                $row["player_name"] = Text_LogService::get($row["chara"]['name_id']);
                $row["grade"] = Service::create('Grade_Master')->needRecord($row["chara"]['grade_id']);

                //$prof = PlatformApi::queryProfile($row["user_id"], array('thumbnailUrl'));
                //$row["thumbnailUrl"] = $prof["thumbnailUrl"];
                //$row["thumbnailUrlSmall"] = $prof["thumbnailUrlSmall"];
                //$row["thumbnailUrlLarge"] = $prof["thumbnailUrlLarge"];

                // 画像情報を取得。
                $spec = CharaImageUtil::getSpec($row["chara"]);
                $path = sprintf('%s.%s.gif', $spec, 'full');
                $row['imageUrl'] = $path;
            }
        }
        $array['list'] = $list;

        $array['result'] = 'ok';

        return $array;

    }
}
