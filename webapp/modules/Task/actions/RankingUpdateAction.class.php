<?php

/**
 * cronによって定期的に実行されるアクション。ランキング情報の更新を行う。
 *
 * GETパラメータ)
 *     type     種別。"daily", "weekly" のいずれか
 *     period   期間。ranking_info.period と同じ。
 *              省略した場合は当日or当週になる。特殊な値 "prev" を指定した場合は前日or前週になる。
 *     prize    褒賞付与を行うかどうか。
 */
class RankingUpdateAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {

        $rankSvc = new Ranking_LogService();

Common::varLog("ランキング集計 " . $_GET['type']);

        //集計するかしないか調べる
        if($rankSvc->isAggregate()){
Common::varLog("ランキング集計開始");
            // ランキング種別を取得。
            $type = ($_GET['type'] == 'daily') ? Ranking_LogService::GRADEPT_DAILY : Ranking_LogService::GRADEPT_WEEKLY;

            // 集計期間を取得。
            $term = $rankSvc->getRankingTerm($type, $_GET['period']);
            $period = date('Ymd', $term['begin']);


Common::varLog("ランキング期間=" . date('Ymd', $term['begin']) . "-" . date('Ymd', $term['end']));

            // 該当期間のポイントを集計しなおす。
            $rankSvc->sumupPoint($type, $period);

Common::varLog("sumupPoint終了");

            // 順位付けを行う。
            $rankSvc->updateRank($type, $period);

Common::varLog("updateRank終了");

            // 褒賞アリなら、その付与と、過去最高位の更新を行う。
            if($_GET['prize']) {
Common::varLog("褒賞アリなら、その付与と、過去最高位の更新を行う");
                $rankSvc->awardPrize($type, $period);
                $rankSvc->updateHighestRank($type, $period);
            }

Common::varLog("RankingUpdate終了");
        }else{
Common::varLog("ランキング集計スキップ・・");
        }

        return View::NONE;
    }
}
