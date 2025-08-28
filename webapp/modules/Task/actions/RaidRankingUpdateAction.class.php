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
class RaidRankingUpdateAction extends BaseAction {

    //-----------------------------------------------------------------------------------------------------
    public function execute() {
Common::varLog("RaidRankingUpdateAction start..");

        $raid = Service::create('Raid_Dungeon')->getCurrent();
        $raid_status = Service::create('Raid_Dungeon')->getStatus($raid);

        if($raid_status == Raid_DungeonService::SUCCESS){
            $logcount = Service::create('Raid_Ranking_Log')->getCount($raid["id"]);

            if($logcount == 0){
                $userranklist = Service::create('Raid_Monster_User')->getUserRank($raid["id"]);
                $prizelist = Service::create('Raid_Prize')->getList($raid["id"]);

                $ranking = 1;
                $i = 1;
                $before_pt = 0;

                foreach($userranklist as $row){

                    if($before_pt != $row['total_point']){
                        $ranking = $i;
                    }

                    $userrank = $ranking;

                    $before_pt = $row['total_point'];

                    $vres = false;
                    foreach($prizelist as $prize){
                        if($prize["rank_id"] == $userrank){
                            if($prize["join_prize_kind"] == Raid_PrizeService::PRIZE_KIND_BTC){
                                $vres = Service::create('User_Info')->setVirtualCoin($row['user_id'], Vcoin_Flag_LogService::RAID, $prize["prize"], $raid["id"]);
                                if($vres)
                                    Common::varLog("レイドダンジョン BTC付与 " . $userrank . "位 user_id=" . $row['user_id'] . " flag_id=" . $raid["id"] . " prize=" . $prize["prize"]);
                            }else if($prize["join_prize_kind"] == Raid_PrizeService::PRIZE_KIND_ITEM){
                                Service::create('User_Item')->gainItem($row['user_id'], $prize["prize"]);
                                Common::varLog("レイドダンジョン ITEM付与 " . $userrank . "位 user_id=" . $row['user_id'] . " item_id=" . $prize["prize"]);
                            }
                        }
                    }
/*
                    if(!$vres){
                        //ランクがリスト外だった場合
                        $vres = Service::create('User_Info')->setVirtualCoin($row['user_id'], Vcoin_Flag_LogService::RAID, $raid["join_prize"], $raid["id"]);
                        if($vres)
                            Common::varLog("レイドダンジョン BTC付与 " . $userrank . "位 user_id=" . $row['user_id'] . " flag_id=" . $raid["id"] . " prize=" . $raid["join_prize"]);
                    }
*/
                    //ログ
                    Service::create('Raid_Ranking_Log')->setValue($raid["id"], $row['user_id'], $row['total_point'], $i);

                    $i++;
                }
            }else{
                Common::varLog("RaidRankingUpdateAction already given.. logcount = " . $logcount);
            }
        }else{
            Common::varLog("RaidRankingUpdateAction status not success = " . $raid_status);
        }

        Common::varLog("RaidRankingUpdateAction end..");

        return View::NONE;
    }
}
