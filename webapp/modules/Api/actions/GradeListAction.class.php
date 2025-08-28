<?php

class GradeListAction extends SmfBaseAction {

    protected function doExecute($params) {
        $array = array();

        $gradeSvc = new Grade_MasterService();

        // ユーザが存在する最も高い番付を取得。
        $highest = $gradeSvc->getHighestGrade();

        // 番付の一覧を取得。
        $array['list'] = $gradeSvc->getList('DESC', $highest);

        // 各番付の人数を取得。
        $array['distribute'] = $gradeSvc->getDistribution();

        // ユーザのキャラを取得。
        $character = Service::create('Character_Info')->needAvatar($this->user_id);
        $array['chara'] = $character;

        return $array;
    }
}
