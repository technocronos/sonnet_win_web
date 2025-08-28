<?php

class RankingAction extends UserBaseAction {

    public function execute() {

        $rankSvc = new Ranking_LogService();

        // 省略されているGET変数を補う。
        if(!$_GET['type'])  $_GET['type'] = Ranking_LogService::GRADEPT_WEEKLY;

        // 指定のランキング種別の最新期間を取得。
        $period = $rankSvc->getNewestPeriod($_GET['type']);
        $this->setAttribute('period', $period);

        // 指定のランキング種別の現在の期間を取得。
        $this->setAttribute('term', $rankSvc->getRankingTerm($_GET['type']));

        // 現在のランキングを取得。
        $rank = $rankSvc->getRecord($_GET['type'], $period, $this->user_id);
        if($rank)
            $this->setAttribute('yourRank', $rank['rank']);

        // ウィークリーランキングのサイクルを取得。
        $this->setAttribute('cycle', $rankSvc->getRankingCycle());

        // smartyはクラス定数の参照ができないため、変数にセットしとく…
        $this->setAttribute('type1', Ranking_LogService::GRADEPT_WEEKLY);
        $this->setAttribute('type2', Ranking_LogService::GRADEPT_DAILY);

        return View::SUCCESS;
    }
}
