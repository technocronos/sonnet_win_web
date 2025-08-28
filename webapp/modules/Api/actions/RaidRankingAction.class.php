<?php

/**
 * ステータス画面の情報を取得する
 */
class RaidRankingAction extends SmfBaseAction {

    protected function doExecute($params) {

        $raid_dungeon_id = $_GET['raid_dungeon_id'];

        $array = array();

        // ステータス画面、装備の情報をセット
        $raidmonSvc = new Raid_Monster_UserService();
        $charaSvc = new Character_InfoService();

        $raid_dungeon = Service::create('Raid_Dungeon')->needRecord($raid_dungeon_id);
        $raid_dungeon['status'] = Service::create('Raid_Dungeon')->getStatus($raid_dungeon);

        $rank = $raidmonSvc->getUserRank($raid_dungeon_id);

        $ranking = 1;
        $i = 1;
        $before_pt = 0;

        foreach($rank as &$row){

            $row['avatar'] = $charaSvc->needAvatar($row['user_id'], true);
            $row['avatar']['player_name'] = Text_LogService::get($row['avatar']['name_id']);

            // 画像情報を取得。
            $spec1 = $this->getFormation($row['avatar']);
            $row['avatar']['equip_info'] = $spec1;

            $row['avatar']["grade"] = Service::create('Grade_Master')->needRecord($row['avatar']['grade_id']);


            if($before_pt != $row['total_point']){
                $ranking = $i;
            }

            $row["rank"] = $ranking;

            $before_pt = $row['total_point'];

            $i++;
        }


        $array['raid_dungeon'] = $raid_dungeon;
        $array['rank_list'] = $rank;
        $array['result'] = 'ok';

        return $array;

    }
}
