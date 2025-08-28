<?php

class MonsterTopAction extends UserBaseAction {

    public function execute() {

        // ビューで使うリストをセット。
        $this->setAttribute('categories', Monster_MasterService::$CATEGORIES);
        $this->setAttribute('rares', Monster_MasterService::$RARE_LEVELS);

        // 地域の一覧を取得して、そこにモンスターダンジョンを追加して出現地の一覧とする。
        $regions = Service::create('Place_Master')->getPlaces(0);
        $regions = ResultsetUtil::colValues($regions, 'place_name', 'place_id');
        $regions[0] = Monster_MasterService::getAppearanceText(0);
        $this->setAttribute('appearances', $regions);

        // 地点番号98のクエストを追加して、イベントの一覧とする。
        $quests = Service::create('Quest_Master')->onPlace(Quest_MasterService::EVENT_QUEST, 'FLD');
        $this->setAttribute('events', ResultsetUtil::colValues($quests, 'quest_name', 'quest_id'));

        // キャプチャ率を取得。
        $this->setAttribute('capture', Service::create('User_Monster')->getCaptureCount($this->user_id));
        $this->setAttribute('monster', Service::create('Monster_Master')->getMonsterCount());

        return View::SUCCESS;
    }
}
