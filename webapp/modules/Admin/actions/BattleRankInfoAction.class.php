<?php

class BattleRankInfoAction extends AdminBaseAction {

    public function execute() {

        $battle_rank_info = Service::create('Ranking_Log')->getRankingStatus();

        $battle_rank_info["ready_start_date"] = $battle_rank_info["start_date"] - (60 * 60 * 24 * 3);
        $battle_rank_info["ranking_end_date"] = $battle_rank_info["result_date"] - 1;
//print_r($battle_rank_info["ranking_end_date"]);
//exit;
        //1:開催中 2:結果発表中 3:非開催 4:準備中
        if($battle_rank_info["status"] == 1){
            $battle_rank_info["status_str"] = "開催中";
        }else if($battle_rank_info["status"] == 2){
            $battle_rank_info["status_str"] = "結果発表中";
        }else if($battle_rank_info["status"] == 3){
            $battle_rank_info["status_str"] = "非開催";
        }else if($battle_rank_info["status"] == 4){
            $battle_rank_info["status_str"] = "準備中";
        }

        // ランキング種別に応じた褒賞アイテムの設定を取得。設定されていない場合は何もしない。
        $weekly_prizes = Ranking_LogService::$PRIZES[Ranking_LogService::GRADEPT_WEEKLY];

        $weekly_list = array();

        foreach($weekly_prizes as $key=>$value){

            $weekly_list[$key]["order"] = $key;
            $weekly_list[$key]["item_id"] = $value["id"];
            $item = Service::create('Item_Master')->getRecord($value["id"]);
            $weekly_list[$key]["set_name"] = "";

            $text = Service::create('Text_Master')->getSymbol("item_master_item_name_" . $value["id"]);

            $weekly_list[$key]["item_name"] = $text["ja"];
            $weekly_list[$key]["item_name_en"] = $text["en"];

            $weekly_list[$key]["category"] = $item["category"];

            if($item["category"] != "ITM"){
                $text = Service::create('Text_Master')->getSymbol("set_master_set_name_" . $item["set_id"]);

                $weekly_list[$key]["set_name"]  = $text["ja"];
                $weekly_list[$key]["set_name_en"]  = $text["en"];
            }
            $weekly_list[$key]["count"] = $value["count"];
            $weekly_list[$key]["btc"] = $value["btc"];
        }

        // ビューに割り当てる。
        $this->setAttribute('weekly_list', $weekly_list);
        $this->setAttribute('battle_rank_info', $battle_rank_info);

        return View::SUCCESS;
    }
}
