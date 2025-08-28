<?php

/**
 * ---------------------------------------------------------------------------------
 * 仲間一覧リストを返す
 * @param userId
 * ---------------------------------------------------------------------------------
 */
class MemberListApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        // 指定されていないURL変数を補う。
        if( empty($_GET['userId']) )  $_GET['userId'] = $this->user_id;
        if( empty($_GET['page']) )    $_GET['page'] = '0';

        // ユーザの情報を取得。
        $userSvc = new User_InfoService();
        $this->setAttribute('target', $userSvc->needRecord($_GET['userId']));

        // ユーザの仲間を取得。
        $memberSvc = new User_MemberService();
        $list = $memberSvc->getMemberList($_GET['userId'], 1000, $_GET['page']);

        foreach($list['resultset'] as &$row){
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

        $array['list'] = $list;

        $array['result'] = 'ok';

        return $array;

    }
}
