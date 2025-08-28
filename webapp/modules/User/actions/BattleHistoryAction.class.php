<?php

class BattleHistoryAction extends UserBaseAction {

    public function execute() {

        $charaSvc = new Character_InfoService();

        // 指定されていないURL変数を補う。
        if( empty($_GET['charaId']) )   $_GET['charaId'] = $charaSvc->needAvatarId($this->user_id);
        if( empty($_GET['tourId']) )    $_GET['tourId'] = Tournament_MasterService::TOUR_MAIN;
        if( empty($_GET['side']) )      $_GET['side'] = 'defend';
        if( empty($_GET['page']) )      $_GET['page'] = '0';

        // システムキャラの戦歴を出そうとしているならエラー。
        if($_GET['charaId'] < 0)
            throw new MojaviException('システムキャラの戦歴を出そうとした');

        // クエスト戦闘の戦歴を出そうとしているならエラー。
        if($_GET['tourId'] == Tournament_MasterService::TOUR_QUEST)
            throw new MojaviException('クエスト戦闘の戦歴を出そうとした。');

        // 指定されているキャラを取得。
        $character = $charaSvc->needRecord($_GET['charaId']);
        $this->setAttribute('character', $character);
        $this->setAttribute('charaName', Text_LogService::get($character['name_id']));

        // 現在のキャラの、指定の戦闘種別の戦績を取得。
        $ctour = Service::create('Character_Tournament')->needRecord($_GET['charaId'], $_GET['tourId']);
        $this->setAttribute('win',  $ctour[$_GET['side'].'_win']);
        $this->setAttribute('lose', $ctour[$_GET['side'].'_lose']);
        $this->setAttribute('draw', $ctour[$_GET['side'].'_draw']);
        $this->setAttribute('fights', $ctour[$_GET['side'].'_win'] + $ctour[$_GET['side'].'_lose'] + $ctour[$_GET['side'].'_timeup'] + $ctour[$_GET['side'].'_draw']);

        $battleSvc = new Battle_LogService();

        // 戦歴を取得。
        $condition = array(
            'characterId' => $_GET['charaId'],
            'tourId' => $_GET['tourId'],
            'side' => $_GET['side']
        );
        $list = $battleSvc->getBattleList($condition, 8, $_GET['page']);

        // 戦歴の持ち主の主観で、擬似列を加える
        $battleSvc->addBiasColumn($list['resultset'], $_GET['charaId'], true);

        // 対戦相手のアバターURLを加える。
        Common::embedThumbnailColumn($list['resultset'], 'rival_user_id');

        $this->setAttribute('list', $list);

        return View::SUCCESS;
    }
}
