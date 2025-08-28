<?php

/**
 * 仮想通貨取得ログリスト
 */
class VcoinLogAction extends SmfBaseAction {

    protected function doExecute($params) {

        // 検索。
        $res = Service::create('Vcoin_Flag_Log')->getBtcLog(array("id" => $this->user_id));
        $table = array();
        $i = 0;
        $total = 0;

        foreach($res as $row){
            if($row["flag_group"] == Vcoin_Flag_LogService::MONSTER){
                $table[$i]["reason"] = AppUtil::getText("TEXT_VCOIN_REASON_MONSTER");
                $table[$i]["owner_id"] = $row["owner_id"];

                $chara = Service::create('Character_Info')->getRecord($row["flag_id"]);
                $text_log = Service::create('Text_Log')->getRecord($chara["name_id"]);
                $table[$i]["name"] = $text_log["body"];
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];
            }else if($row["flag_group"] == Vcoin_Flag_LogService::BATTLE_RANKING){
                $table[$i]["reason"] = AppUtil::getText("TEXT_VCOIN_REASON_BATTLE_RANKING");
                $table[$i]["owner_id"] = $row["owner_id"];
                $table[$i]["name"] = str_replace(array("{0}"), array((int)substr($row["flag_id"], -4)), AppUtil::getText("TEXT_RANK"));
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];
            }else if($row["flag_group"] == Vcoin_Flag_LogService::FIELD){
                $table[$i]["reason"] = AppUtil::getText("TEXT_VCOIN_REASON_FIELD");
                $table[$i]["owner_id"] = $row["owner_id"];
                $quest = Service::create('Quest_Master')->getRecord((int)substr($row["flag_id"], 0, 5));
                $table[$i]["name"] = AppUtil::getText("quest_master_quest_name_" . $quest["quest_id"]);
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];
                
            }else if($row["flag_group"] == Vcoin_Flag_LogService::RAID){
                $table[$i]["reason"] = AppUtil::getText("TEXT_VCOIN_REASON_RAID");
                $table[$i]["owner_id"] = $row["owner_id"];
                $quest = Service::create('Raid_Dungeon')->getRecord($row["flag_id"]);
                $table[$i]["name"] = AppUtil::getText("raid_dungeon_title_" . $quest["id"]);
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];

            }else if($row["flag_group"] == Vcoin_Flag_LogService::RAID_0){
                $table[$i]["reason"] = AppUtil::getText("TEXT_VCOIN_REASON_RAID_0");
                $table[$i]["owner_id"] = $row["owner_id"];
                $quest = Service::create('Raid_Dungeon')->getRecord($row["flag_id"]);
                $table[$i]["name"] = AppUtil::getText("raid_dungeon_title_" . $quest["id"]);
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];

            }else if($row["flag_group"] == Vcoin_Flag_LogService::RAID_1){
                $table[$i]["reason"] = AppUtil::getText("TEXT_VCOIN_REASON_RAID_1");
                $table[$i]["owner_id"] = $row["owner_id"];
                $quest = Service::create('Raid_Dungeon')->getRecord($row["flag_id"]);
                $table[$i]["name"] = AppUtil::getText("raid_dungeon_title_" . $quest["id"]);
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];

            }else if($row["flag_group"] == Vcoin_Flag_LogService::RAID_2){
                $table[$i]["reason"] = AppUtil::getText("TEXT_VCOIN_REASON_RAID_1");
                $table[$i]["owner_id"] = $row["owner_id"];
                $quest = Service::create('Raid_Dungeon')->getRecord($row["flag_id"]);
                $table[$i]["name"] = AppUtil::getText("raid_dungeon_title_" . $quest["id"]);
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];

            }else if($row["flag_group"] == Vcoin_Flag_LogService::RAID_3){
                $table[$i]["reason"] = AppUtil::getText("TEXT_VCOIN_REASON_RAID_3");
                $table[$i]["owner_id"] = $row["owner_id"];
                $quest = Service::create('Raid_Dungeon')->getRecord($row["flag_id"]);
                $table[$i]["name"] = AppUtil::getText("raid_dungeon_title_" . $quest["id"]);
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];

            }else if($row["flag_group"] == Vcoin_Flag_LogService::RAID_4){
                $table[$i]["reason"] = AppUtil::getText("TEXT_VCOIN_REASON_RAID_4");
                $table[$i]["owner_id"] = $row["owner_id"];
                $quest = Service::create('Raid_Dungeon')->getRecord($row["flag_id"]);
                $table[$i]["name"] = AppUtil::getText("raid_dungeon_title_" . $quest["id"]);
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];

            }else if($row["flag_group"] == Vcoin_Flag_LogService::RAID_5){
                $table[$i]["reason"] = AppUtil::getText("TEXT_VCOIN_REASON_RAID_5");
                $table[$i]["owner_id"] = $row["owner_id"];
                $quest = Service::create('Raid_Dungeon')->getRecord($row["flag_id"]);
                $table[$i]["name"] = AppUtil::getText("raid_dungeon_title_" . $quest["id"]);
                $table[$i]["amount"] = sprintf('%f', $row["flag_value"]);
                $table[$i]["update_at"] = $row["update_at"];

            }else if($row["flag_group"] == Vcoin_Flag_LogService::INVITE){
                $table[$i]["reason"] = AppUtil::getText("TEXT_VCOIN_REASON_INVITE");
                $table[$i]["owner_id"] = $row["owner_id"];

                $avatar = Service::create('Character_Info')->needAvatar($row["flag_id"], true);
                $player_name = Text_LogService::get($avatar['name_id']);

                $table[$i]["name"] = $player_name;
                $table[$i]["amount"] = Invitation_LogService::INVITE_BTC;
                $table[$i]["update_at"] = $row["update_at"];

            }else if($row["flag_group"] == Vcoin_Flag_LogService::INVITED){
                $table[$i]["reason"] = AppUtil::getText("TEXT_VCOIN_REASON_INVITED");
                $table[$i]["owner_id"] = $row["owner_id"];

                $avatar = Service::create('Character_Info')->needAvatar($row["flag_id"], true);
                $player_name = Text_LogService::get($avatar['name_id']);

                $table[$i]["name"] = $player_name;
                $table[$i]["amount"] = Invitation_LogService::INVITED_BTC;
                $table[$i]["update_at"] = $row["update_at"];

            }
            $total += $row["flag_value"];
            $i++;
        }

        

        return array("result" => "ok", "resultset" => $table);

    }
}
