<?php

class RaidDungeonDetailAction extends AdminBaseAction {

    public function execute() {

        $raid_dungeon_id = $_GET['raid_dungeon_id'];

        $raid_dungeon = Service::create('Raid_Dungeon')->getRecord($raid_dungeon_id);

        $raid_dungeon_title = Service::create('Text_Master')->getSymbol("raid_dungeon_title_" . $raid_dungeon_id);
        $raid_dungeon_description = Service::create('Text_Master')->getSymbol("raid_dungeon_description_" . $raid_dungeon_id);

        $raid_dungeon["title"] = $raid_dungeon_title["ja"];
        $raid_dungeon["title_en"] = $raid_dungeon_title["en"];

        $raid_dungeon["description"] = $raid_dungeon_description["ja"];
        $raid_dungeon["description_en"] = $raid_dungeon_description["en"];

        $week = array( "日", "月", "火", "水", "木", "金", "土" );
        $week_en = array( "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat" );

        $raid_dungeon["start_at_w"] = $week[date("w", strtotime($raid_dungeon["start_at"]))];
        $raid_dungeon["end_at_w"] = $week[date("w", strtotime($raid_dungeon["end_at"]))];

        $raid_dungeon["start_at_w_en"] = $week_en[date("w", strtotime($raid_dungeon["start_at"]))];
        $raid_dungeon["end_at_w_en"] = $week_en[date("w", strtotime($raid_dungeon["end_at"]))];

        $raid_dungeon["status"] = Service::create('Raid_Dungeon')->getStatus($raid_dungeon);

        //倒すべきモンスターの数
        $monsters = Service::create('Monster_Master')->getMonsterList("appearance_area", 0, 1000, 0);
        $raid_dungeon['total_count'] = count($monsters["resultset"]);

        if($d["status"] == Raid_DungeonService::START){
            $date = date('Y-m-d', strtotime("now"));
            $raid_dungeon['defeat_count'] = Service::create('Raid_Monster_User')->getDefeatCount($raid_dungeon['id'], $date);
        }else{
            $raid_dungeon['defeat_count'] = Service::create('Raid_Monster_User')->getDefeatCountAll($raid_dungeon['id']);
        }

        $raid_dungeon['defeat_date'] = Service::create('Raid_Monster_User')->getLatestDefeatDate($raid_dungeon_id);

        $rank = Service::create('Raid_Monster_User')->getUserRank($raid_dungeon_id);
        $raid_prize = Service::create('Raid_Prize')->getList($raid_dungeon_id);

        foreach($raid_prize as $p){
            $total_prize = $total_prize + $p["prize"];
        }

        $ranking = 1;
        $i = 1;
        $before_pt = 0;

        foreach($rank as &$row){

            $row['avatar'] = Service::create('Character_Info')->getAvatar($row['user_id'], true);
            $row['avatar']['player_name'] = Text_LogService::get($row['avatar']['name_id']);
            $row['avatar']["grade"] = Service::create('Grade_Master')->needRecord($row['avatar']['grade_id']);

            if($before_pt != $row['total_point']){
                $ranking = $i;
            }

            $row["rank"] = $ranking;

            $before_pt = $row['total_point'];

            $i++;
        }

        foreach($raid_prize as &$row){
            if($row["join_prize_kind"] == Raid_PrizeService::PRIZE_KIND_ITEM){
                $item = Service::create('Item_Master')->getRecord($row["prize"]);
                $row["item_name"] = $item["item_name"];
            }
        }

        // ビューに割り当てる。
        $this->setAttribute('raid_dungeon', $raid_dungeon);
        $this->setAttribute('raid_rank', $rank);
        $this->setAttribute('raid_prize', $raid_prize);
        $this->setAttribute('total_prize', $total_prize);

        return View::SUCCESS;
    }
}
