<?php

class GradeListAction extends UserBaseAction {

    public function execute() {

        $gradeSvc = new Grade_MasterService();

        // ユーザが存在する最も高い番付を取得。
        $highest = $gradeSvc->getHighestGrade();

        // 番付の一覧を取得。
        $this->setAttribute('list', $gradeSvc->getList('DESC', $highest));

        // 各番付の人数を取得。
        $this->setAttribute('distribute', $gradeSvc->getDistribution());

        // ユーザのキャラを取得。
        $character = Service::create('Character_Info')->needAvatar($this->user_id);
        $this->setAttribute('chara', $character);

        return View::SUCCESS;
    }
}
