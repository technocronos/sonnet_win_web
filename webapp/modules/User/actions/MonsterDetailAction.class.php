<?php

class MonsterDetailAction extends UserBaseAction {

    public function execute() {

        // 指定されたモンスターの情報を取得。
        $monster = Service::create('Monster_Master')->needRecord($_GET['id']);

        // 本当に倒したモンスターなのかチェック。
        $open = Service::create('User_Monster')->needRecord($this->user_id, $_GET['id']);
        if(!$open)
            throw new MojaviException('倒していないモンスターの情報を表示しようとした');

        // character_info の拡張情報も加える。
        Service::create('Character_Info')->addExColumn($monster);

        // category_text, rare_level_text も加える
        $monster['category_text'] = Monster_MasterService::$CATEGORIES[ $monster['category'] ];
        $monster['rare_level_text'] = Monster_MasterService::$RARE_LEVELS[ $monster['rare_level'] ];

        // rare_level_indicator も加える
        $monster['rare_level_indicator'] = '';
        for($i = 0 ; $i < $monster['rare_level'] ; $i++)
            $monster['rare_level_indicator'] .= '★';

        // 必殺技がある場合は必殺技に関するデータも取得。
        if($monster['dtech1_id'])
            $this->setAttribute('dtech', Service::create('Dtech_Master')->needRecord($monster['dtech1_id']));

        // ビューにセット。
        $this->setAttribute('monster', $monster);

        return View::SUCCESS;
    }
}
