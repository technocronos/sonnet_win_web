<?php

/**
 * RaidDungeon画面の情報を取得する
 */
class RaidDungeonAction extends SmfBaseAction {

    protected function doExecute($params) {

        $raid_dungeon_id = $_GET['raid_dungeon_id'];

        $array = array();

        $currentraid = Service::create('Raid_Dungeon')->getCurrent();

        if($currentraid != null){
            // レイドダンジョンをロード。
            $questObj = QuestCommon::factory($currentraid["quest_id"], $this->user_id);

            // 本当に実行できる状態にあるのかチェック。
            if( $questObj->isExecutable() ){
                $array['raid_dungeon'] = $currentraid;
                $array['raid_dungeon']['status'] = Service::create('Raid_Dungeon')->getStatus($array['raid_dungeon']);
                $array['raid_dungeon']['prizelist'] = array();
                $array['raid_dungeon']['total_count'] = 0;
                $array['raid_dungeon']['defeat_count'] = 0;

                //実行するレイドダンジョンがある場合
                if($array['raid_dungeon']['status'] > Raid_DungeonService::NONE){
                    $array['raid_dungeon']['prizelist'] = Service::create('Raid_Prize')->getList($array['raid_dungeon']['id']);

                    //倒すべきモンスターの数
                    $monSvc = new Monster_MasterService();
                    $monsters = $monSvc->getMonsterList("appearance_area", 0, 1000, 0);
                    $array['raid_dungeon']['total_count'] = count($monsters["resultset"]);

                    $date = date('Y-m-d', strtotime("now"));
                    $array['raid_dungeon']['defeat_count'] = Service::create('Raid_Monster_User')->getDefeatCount($array['raid_dungeon']['id'], $date);

                    $array['raid_dungeon']['description'] = str_replace("{NORMAL_PT}", RAID_AMOUNT_RARE1, $array['raid_dungeon']['description']);
                    $array['raid_dungeon']['description'] = str_replace("{REAR_PT}", RAID_AMOUNT_RARE2, $array['raid_dungeon']['description']);
                    $array['raid_dungeon']['description'] = str_replace("{SREAR_PT}", RAID_AMOUNT_RARE3, $array['raid_dungeon']['description']);
                    $array['raid_dungeon']['description'] = str_replace("{JOIN_PT}", (float) $array['raid_dungeon']['join_prize'], $array['raid_dungeon']['description']);

                    $itemSvc = new Item_MasterService();
                    $prizelist = "";
                    foreach($array['raid_dungeon']['prizelist'] as $prize){
                        if($prize["join_prize_kind"] == Raid_PrizeService::PRIZE_KIND_BTC){
                            $prizelist .= "\n" . $prize["rank_id"] . "位:";
                            $prizelist .= (float) $prize["prize"] . "BTC";
                        }else if($prize["join_prize_kind"] == Raid_PrizeService::PRIZE_KIND_ITEM){
                            $item = $itemSvc->needRecord((int)$prize["prize"]);
                            $prizelist .= " " . $item["item_name"];
                        }
                    }

                    $array['raid_dungeon']['description'] = str_replace("{PRIZELIST}", $prizelist, $array['raid_dungeon']['description']);
                }

                if($array['raid_dungeon']['status'] == Raid_DungeonService::NONE){
                    $array['raid_dungeon']['navi_title'] = AppUtil::getText("TEXT_RAIDDUNGEON");
                    $array['raid_dungeon']['navi_serifu'] = AppUtil::getText("TEXT_RAIDDUNGEON_STATUS_NONE"); 
                }else if($array['raid_dungeon']['status'] == Raid_DungeonService::READY){
                    $array['raid_dungeon']['navi_title'] = $array['raid_dungeon']['title'];
                    $array['raid_dungeon']['navi_serifu'] = str_replace("{0}", $array['raid_dungeon']['title'], AppUtil::getText("TEXT_RAIDDUNGEON_STATUS_READY"));
                }else if($array['raid_dungeon']['status'] == Raid_DungeonService::START){
                    $array['raid_dungeon']['navi_title'] = str_replace("{0}", $array['raid_dungeon']['title'], AppUtil::getText("TEXT_OPEN"));
                    $array['raid_dungeon']['navi_serifu'] = str_replace("{0}", $array['raid_dungeon']['title'], AppUtil::getText("TEXT_RAIDDUNGEON_STATUS_START")); 
                }else if($array['raid_dungeon']['status'] == Raid_DungeonService::SUCCESS){
                    $array['raid_dungeon']['navi_title'] = str_replace("{0}", $array['raid_dungeon']['title'], AppUtil::getText("TEXT_END"));
                    $array['raid_dungeon']['navi_serifu'] = str_replace("{0}", $array['raid_dungeon']['title'], AppUtil::getText("TEXT_RAIDDUNGEON_STATUS_SUCCESS"));
                }else if($array['raid_dungeon']['status'] == Raid_DungeonService::FAILURE){
                    $array['raid_dungeon']['navi_title'] = str_replace("{0}", $array['raid_dungeon']['title'], AppUtil::getText("TEXT_FAILURE"));
                    $array['raid_dungeon']['navi_serifu'] = str_replace("{0}", $array['raid_dungeon']['title'], AppUtil::getText("TEXT_RAIDDUNGEON_STATUS_FAILURE")); 
                }

            }
        }

        $array['result'] = 'ok';

        return $array['raid_dungeon'];

    }
}
