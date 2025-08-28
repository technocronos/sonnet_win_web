<?php

/**
 * ステータス画面の情報を取得する
 */
class RaidMonsterListAction extends SmfBaseAction {

    protected function doExecute($params) {

        if($_GET['date'] != "")
            $date_int = $_GET['date'];
        else
            $date_int = 0;

        $array = array();
        $array["monsterlist"] = array();

        // ステータス画面、装備の情報をセット
        $monSvc = new Monster_MasterService();
        $raidmonSvc = new Raid_Monster_UserService();
        $charaSvc = new Character_InfoService();

        $array['raid_dungeon'] = Service::create('Raid_Dungeon')->getCurrent();

        $timestamp = strtotime("now") - ($date_int * 60 * 60 * 24);

        //日付を確定する
        if(strtotime($array['raid_dungeon']["start_at"]) <= $timestamp && strtotime($array['raid_dungeon']["end_at"]) > $timestamp){
            $date = date('Y-m-d', $timestamp);
        }else{
            $date = date('Y-m-d', strtotime($array['raid_dungeon']["end_at"]) - ($date_int * 60 * 60 * 24));
        }

        $array['raid_dungeon']['status'] = Service::create('Raid_Dungeon')->getStatus($array['raid_dungeon']);

        //開始から何日経過してるか
        $today = date("Y-m-d");// 現在の日付け取得
        $today = strtotime($today);// タイムスタンプへ変換

        if($array['raid_dungeon']['status'] == Raid_DungeonService::START){
            $array['raid_dungeon']["past"] = ($today - strtotime($array['raid_dungeon']["start_at"])) / (60 * 60 * 24);
        }else{
            $array['raid_dungeon']["past"] = floor((strtotime($array['raid_dungeon']["end_at"]) - strtotime($array['raid_dungeon']["start_at"])) / (60 * 60 * 24));
        }

        $array['raid_dungeon']['prizelist'] = Service::create('Raid_Prize')->getList($array['raid_dungeon']["id"]);

        $monsters = $monSvc->getMonsterList("appearance_area", 0, 1000, 0);

        $i = 0;
        foreach($monsters["resultset"] as $monster){
            $array["monsterlist"][$i]["monster"] = $monster;

            $array["monsterlist"][$i]["monster"]['monster_name'] = Text_LogService::get($array["monsterlist"][$i]["monster"]['name_id']);
            $array["monsterlist"][$i]["monster"]["equip"] = array();
            $array["monsterlist"][$i]["monster"]["image_url"] = null;

            $raidmon = null;

            //倒したユーザーがいる場合
            if($date != ""){
                $raidmon = $raidmonSvc->getMonsterIdByDate($array['raid_dungeon']["id"], $array["monsterlist"][$i]["monster"]["character_id"], $date);
            }else{
                $tmp = $raidmonSvc->getMonsterId($array['raid_dungeon']["id"], $array["monsterlist"][$i]["monster"]["character_id"]);
                if($tmp != null)
                    $raidmon = $tmp[0];
            }

            if($raidmon != null){
                $array["monsterlist"][$i]["defeat_user"]['avatar'] = $charaSvc->needAvatar($raidmon['user_id'], true);

                $array["monsterlist"][$i]["defeat_user"]['avatar']['player_name'] = Text_LogService::get($array["monsterlist"][$i]["defeat_user"]['avatar']['name_id']);

                // 画像情報を取得。
                $spec1 = $this->getFormation($array["monsterlist"][$i]["defeat_user"]['avatar']);
                $array["monsterlist"][$i]["defeat_user"]['avatar']['equip_info'] = $spec1;

                $array["monsterlist"][$i]["defeat_user"]['avatar']["grade"] = Service::create('Grade_Master')->needRecord($array["monsterlist"][$i]["defeat_user"]['avatar']['grade_id']);

                $array["monsterlist"][$i]["defeat_user"]["point"] = $raidmon["point"];
                $array["monsterlist"][$i]["defeat_user"]["create_at"] = $raidmon["create_at"];

                $array['raid_dungeon']['defeat_count'] = Service::create('Raid_Monster_User')->getDefeatCount($array['raid_dungeon']["id"], $date);

            }

            //倒すべきモンスターの数
            $monSvc = new Monster_MasterService();
            $monsters = $monSvc->getMonsterList("appearance_area", 0, 1000, 0);
            $array['raid_dungeon']['total_count'] = count($monsters["resultset"]);

            $i++;
        }

        $array['result'] = 'ok';

        return $array;

    }
}
