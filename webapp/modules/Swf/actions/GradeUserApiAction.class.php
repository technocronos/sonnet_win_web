<?php

class GradeUserApiAction extends ApiBaseAction {

    protected function doExecute($params) {
        $array = array();

        // 省略されているパラメータを補う。
        if($_GET['page'] == '')  $_GET['page'] = 0;

        $gradeSvc = new Grade_MasterService();

        $array['grade'] = $gradeSvc->needRecord($_GET['gradeId']);
        $list = $gradeSvc->getCharacterList($_GET['gradeId'], 10, $_GET['page']);

        // ユーザIDをすべて取得。
        $userIds = ResultsetUtil::colValues($list["resultset"], 'user_id');
        // ユーザ情報を取得して、一覧にユーザ情報を埋め込む。
        $users = Service::create('User_Info')->getRecordsIn($userIds);

        foreach($list['resultset'] as &$row){
            $row['user'] = $users[ $row['user_id'] ];
            $row['player_name'] = Text_LogService::get($row['name_id']);
            $row["grade"] = Service::create('Grade_Master')->needRecord($row['grade_id']);
            $row['member'] = Service::create('User_Member')->getMemberCount( $row['user_id'] );

            // 双方の画像情報を取得。
            $spec = CharaImageUtil::getSpec($row);
            $path = sprintf('%s.%s.gif', $spec, 'full');
            $row['imageUrl'] = $path;
        }

        $array['list'] = $list;

        return $array;
    }
}
