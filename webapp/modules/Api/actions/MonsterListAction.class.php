<?php

class MonsterListAction extends SmfBaseAction {

    protected function doExecute($params) {

        $umonsSvc = new User_MonsterService();
        $array = [];
        $tab_list = [];
        $page_const = 1000;

        if($_GET["category"] == 1){
            // 種族別
            $tab_list = Monster_MasterService::getCategorys();
            $field = "category";
            $array['title'] = AppUtil::getText("monsterlist_text_kind1");
        }else if($_GET["category"] == 2){
            // レア度別
            $tab_list = Monster_MasterService::getRareLevels();
            $field = "rare_level";
            $array['title'] = AppUtil::getText("monsterlist_text_kind2");
        }else if($_GET["category"] == 3){
            // 地域の一覧を取得して、そこにモンスターダンジョンを追加して出現地の一覧とする。
            $regions = Service::create('Place_Master')->getPlaces(0);
            $regions = ResultsetUtil::colValues($regions, 'place_name', 'place_id');
            $regions[0] = Monster_MasterService::getAppearanceText(0);
            $tab_list = $regions;
            $field = "appearance";
            $array['title'] = AppUtil::getText("monsterlist_text_kind3");
        }else if($_GET["category"] == 4){
            // イベントの一覧とする。
            $quests = Service::create('Quest_Master')->onEvent('FLD', "quest_id");
            $tab_list = ResultsetUtil::colValues($quests, 'quest_name', 'quest_id');
            $field = "appearance";
            $array['title'] = AppUtil::getText("monsterlist_text_kind4");
        }else if($_GET["category"] == 5){
            $array['title'] = AppUtil::getText("monsterlist_text_kind5");
            $field = "terminate";
        }

        foreach($tab_list as $key=>$val)
            $value[] = $key;

        // リストを取得。
        if($_GET["category"] == 5)
            $list  = $umonsSvc->getTerminateList($this->user_id, $page_const, 0);
        else
            $list  = $umonsSvc->getCollectionList(array('user_id'=>$this->user_id, 'field' => $field, 'value' => $value), $page_const, 0);

        foreach($list["resultset"] as &$row){
            $row['monster_name'] = Text_LogService::get($row['name_id']);

            $row["equip"] = array();

            // 画像情報を取得。
            $spec1 = $this->getFormation($row);
            $row['equip_info'] = $spec1;
        }

        // 飾りテキストを決定
        $array['flavor'] = $this->decideFlavorText($_GET["category"]);

        $array["list"] = $list;
        $array["tab_list"] = $tab_list;

        $array["field"] = $field;

        $array['category_text'] = Monster_MasterService::getCategorys();

        return $array;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 飾りテキストを返す。
     */
    private function decideFlavorText($category) {

        switch($category) {

            case 1:
                $texts = array(
                     1 => AppUtil::getText("monsterlist_text_flavor1"),
                     2 => AppUtil::getText("monsterlist_text_flavor2"),
                     3 => AppUtil::getText("monsterlist_text_flavor3"),
                     4 => AppUtil::getText("monsterlist_text_flavor4"),
                     5 => AppUtil::getText("monsterlist_text_flavor5"),
                     6 => AppUtil::getText("monsterlist_text_flavor6"),
                     7 => AppUtil::getText("monsterlist_text_flavor7"),
                     8 => AppUtil::getText("monsterlist_text_flavor8"),
                     9 => AppUtil::getText("monsterlist_text_flavor9"),
                    10 => AppUtil::getText("monsterlist_text_flavor10"),
                );
                break;

            case 2:
                $texts = array(
                     1 => AppUtil::getText("monsterlist_text_flavor_rear1"),
                     2 => AppUtil::getText("monsterlist_text_flavor_rear2"),
                     3 => AppUtil::getText("monsterlist_text_flavor_rear3"),
                );
                break;

            case 3:
                $texts = array(
                     0 => AppUtil::getText("monsterlist_text_flavor_area0"),
                     1 => AppUtil::getText("monsterlist_text_flavor_area1"),
                     2 => AppUtil::getText("monsterlist_text_flavor_area2"),
                     3 => AppUtil::getText("monsterlist_text_flavor_area3"),
                     4 => AppUtil::getText("monsterlist_text_flavor_area4"),
                     5 => AppUtil::getText("monsterlist_text_flavor_area5"),
                );
                break;
            case 4:
                $texts = array(
                     0 => AppUtil::getText("monsterlist_text_flavor_event"),
                );
                break;
            case 5:
                $texts = array(
                     0 => AppUtil::getText("monsterlist_text_flavor_get"),
                );
                break;
        }

        return is_null($texts) ? '' : $texts;
    }
}
