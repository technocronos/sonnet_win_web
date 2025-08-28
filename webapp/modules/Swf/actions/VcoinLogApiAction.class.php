<?php

/**
 * 他人のページを作成する
 */
class VcoinLogApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        // 検索。
        $res = Service::create('Vcoin_Flag_Log')->getBtcLog(array("id" => $this->user_id));
        $table = array();
        $i = 0;
        $total = 0;

        foreach($res as $row){
            if($row["flag_group"] == Vcoin_Flag_LogService::MONSTER){
                $table[$i]["reason"] = "モンスター討伐";
                $table[$i]["owner_id"] = $row["owner_id"];

                $chara = Service::create('Character_Info')->getRecord($row["flag_id"]);
                $text_log = Service::create('Text_Log')->getRecord($chara["name_id"]);
                $table[$i]["name"] = $text_log["body"];
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];
            }else if($row["flag_group"] == Vcoin_Flag_LogService::BATTLE_RANKING){
                $table[$i]["reason"] = "バトルランキング";
                $table[$i]["owner_id"] = $row["owner_id"];
                $table[$i]["name"] = (int)substr($row["flag_id"], -4) . "位";
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];
            }else if($row["flag_group"] == Vcoin_Flag_LogService::FIELD){
                $table[$i]["reason"] = "フィールド";
                $table[$i]["owner_id"] = $row["owner_id"];
                $quest = Service::create('Quest_Master')->getRecord((int)substr($row["flag_id"], 0, 5));
                $table[$i]["name"] = $quest["quest_name"];
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];
                
            }
            $total += $row["flag_value"];
            $i++;
        }

        return $table;

    }
}
