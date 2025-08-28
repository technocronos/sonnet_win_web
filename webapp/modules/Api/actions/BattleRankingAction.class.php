<?php

/**
 * ---------------------------------------------------------------------------------
 * バトルランキング一覧を表示する
 * @param id　type
 * ---------------------------------------------------------------------------------
 */
class BattleRankingAction extends SmfBaseAction {

    protected function doExecute($params) {
        $array = array();

        $charaSvc = new Character_InfoService();
        $rankSvc = new Ranking_LogService();

        // 省略されているGET変数を補う。
        if(!$_GET['type'])  $_GET['type'] = Ranking_LogService::GRADEPT_WEEKLY;

        if(!$_GET['count'])  $_GET['count'] = 10;
        if(!$_GET['page'])  $_GET['page'] = 0;

        // 指定のランキング種別の最新期間を取得。
        $period = $rankSvc->getNewestPeriod($_GET['type']);

        // 指定のランキング種別の現在の期間を取得。
        $array['term'] = $rankSvc->getRankingTerm($_GET['type']);

        // 現在のランキングを取得。
        $rank = $rankSvc->getRecord($_GET['type'], $period, $this->user_id);
        if($rank)
            $array['yourRank'] = $rank['rank'];

        $list = $rankSvc->getRankingList($_GET['type'], $period, $_GET['count'], $_GET['page']);

        // サムネイルURLを一覧の列に追加する。
        //Common::embedThumbnailColumn($list['resultset'], 'user_id');

        // ユーザ情報をすべて取得。
        $userIds = ResultsetUtil::colValues($list['resultset'], 'user_id');
        $users = Service::create('User_Info')->getRecordsIn( array_unique($userIds) );

        // 擬似列 "user_name", "avatar", "highest_rank" を追加する。
        foreach($list['resultset'] as &$record) {

            $record['user_name'] = $users[ $record['user_id'] ]['short_name'];

            $record['avatar'] = $charaSvc->needAvatar($record['user_id'], true);

            // 画像情報を取得。
            $spec1 = $this->getFormation($record['avatar']);
            $record['avatar']['equip_info'] = $spec1;

            $record['avatar']["player_name"] = Text_LogService::get($record['avatar']['name_id']);

            $record['avatar']["grade"] = Service::create('Grade_Master')->needRecord($record['avatar']['grade_id']);

            $record['highest'] = $rankSvc->getHighestRank($record['user_id']);

        }unset($record);

        $array["list"] = $list;

        // ウィークリーランキングのサイクルを取得。
        $array['cycle'] = $rankSvc->getRankingCycle();

        $array['period']["weekly"] = $rankSvc->getNewestPeriod(Ranking_LogService::GRADEPT_WEEKLY);
        $array['period']["daily"] = $rankSvc->getNewestPeriod(Ranking_LogService::GRADEPT_DAILY);

        $array['rankinfo'] = $rankSvc->getRankingStatus();

        return $array;

    }
}
