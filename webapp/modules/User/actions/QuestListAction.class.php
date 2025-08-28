<?php

class QuestListAction extends UserBaseAction {

    public function execute() {

        // すでにフィールドクエストに出ている場合はこの画面は表示不可。
        $avatar = Service::create('Character_Info')->needAvatar($this->user_id);
        if($avatar['sally_sphere'])
            Common::redirect('User', 'FieldReopen');

        // 今いる場所の情報を取得。
        $place = Service::create('Place_Master')->needRecord($this->userInfo['place_id']);
        $this->setAttribute('place', $place);

        // 実行できるクエストの一覧を取得
        $this->setAttribute('list', QuestCommon::getExecutableList($this->user_id));

        //曜日クエ内容
        $reword = FieldBattle99999Util::getRewordDay();
        $this->setAttribute('week_quest_str', $reword['str']);

        return View::SUCCESS;
    }

}
