<?php

class MonsterListApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        $umonsSvc = new User_MonsterService();
        $array = [];
        $tab_list = [];
        $page_const = 1000;

        if($_GET["category"] == 1){
            // 種族別
            $tab_list = Monster_MasterService::$CATEGORIES;
            $field = "category";
            $array['title'] = "種族別";
        }else if($_GET["category"] == 2){
            // レア度別
            $tab_list = Monster_MasterService::$RARE_LEVELS;
            $field = "rare_level";
            $array['title'] = "レア度別";
        }else if($_GET["category"] == 3){
            // 地域の一覧を取得して、そこにモンスターダンジョンを追加して出現地の一覧とする。
            $regions = Service::create('Place_Master')->getPlaces(0);
            $regions = ResultsetUtil::colValues($regions, 'place_name', 'place_id');
            $regions[0] = Monster_MasterService::getAppearanceText(0);
            $tab_list = $regions;
            $field = "appearance";
            $array['title'] = "地域別";
        }else if($_GET["category"] == 4){
            // イベントの一覧とする。
            $quests = Service::create('Quest_Master')->onEvent('FLD');
            $tab_list = ResultsetUtil::colValues($quests, 'quest_name', 'quest_id');
            $field = "appearance";
            $array['title'] = "イベント別";
        }else if($_GET["category"] == 5){
            $array['title'] = "倒したモンスター";
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
            // 双方の画像情報を取得。
            $spec = CharaImageUtil::getSpec($row);
            $path = sprintf('%s.%s.gif', $spec, 'full');
            $row['image_url'] = $path;
        }

        // 飾りテキストを決定
        $array['flavor'] = $this->decideFlavorText($field);

        $array["list"] = $list;
        $array["tab_list"] = $tab_list;

        $array["field"] = $field;

        $array['category_text'] = Monster_MasterService::$CATEGORIES;

        return $array;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 飾りテキストを返す。
     */
    private function decideFlavorText($field) {

        switch($field) {

            case 'category':
                $texts = array(
                     1 => '古い植物に精霊が憑依して生命を持ったモンスター｡',
                     2 => '水の中や水辺に棲むモンスター｡水属性を持つものが多い｡',
                     3 => '生物の死体がよみがえったモンスター｡強力なものが多いが火に弱い｡',
                     4 => '巨大化した虫がモンスター化したもの｡',
                     5 => '自然界に棲む猛獣｡強力な爪と牙を持つ｡',
                     6 => '修行によりモンスターと同等の力を持った人間｡',
                     7 => '強力な力を持つ亜人｡100年戦争で絶滅しかかっているが個々の力は人間より強力｡',
                     8 => 'ﾏﾙﾃｨｰﾆが作り出した感情を持たないマシーン｡',
                     9 => '意思はあるけど実体のないエネルギー体｡',
                    10 => '誰もが知っている伝説の生物｡地域によっては神と崇められている者もいる｡',
                );
                break;

            case 'rare_level':
                $texts = array(
                     1 => 'どこにでも出現するモンスター｡見つけるのは簡単だが入手できるかは実力次第｡',
                     2 => 'ストーリー内ではボスキャラ、モンスターの洞窟内ではたまに現れるモンスター｡強力なモンスターが多く､見つけるのも困難なので十分な準備が必要｡',
                     3 => '見つけるのも倒すのも非常に困難なモンスター｡特殊能力を持つものが多く､通常はまず発見できない｡',
                );
                break;

            case 'appearance':
                $texts = array(
                     0 => '誰も一番奥を見たことがないという果てしない洞窟｡モンスターはここから生まれてくるとか…',
                     1 => '主人公の生まれ育った故郷の島｡牧歌的であまり強力な敵はいない平和なところ｡',
                     2 => '亜人の生き残りがひっそりと住んでいる大陸｡',
                     3 => '機械で世界を支配する王国｡その軍事力は強力だ。',
                     4 => 'キャラバンたちが商業を営む砂漠｡レジスタンスたちのアジトがある。',
                     5 => 'マルティーニの罪人の流刑地｡',
                );
                break;

            case 'terminate':
                return 'いままで倒してきたモンスターたち｡コンプしたら立派なモンスターハンターだ｡';
        }

        return is_null($texts) ? '' : $texts;
    }
}
