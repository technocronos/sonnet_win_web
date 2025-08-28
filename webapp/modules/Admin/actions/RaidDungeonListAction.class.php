<?php

class RaidDungeonListAction extends AdminBaseAction {

    public function execute() {

        $raid_dungeon = Service::create('Raid_Dungeon')->getList(100, 0);

        foreach($raid_dungeon["resultset"] as &$d){
            $d["status"] = Service::create('Raid_Dungeon')->getStatus($d);

            //倒すべきモンスターの数
            $monSvc = new Monster_MasterService();
            $monsters = $monSvc->getMonsterList("appearance_area", 0, 1000, 0);
            $d['total_count'] = count($monsters["resultset"]);

            if($d["status"] == Raid_DungeonService::START){
                $date = date('Y-m-d', strtotime("now"));
                $d['defeat_count'] = Service::create('Raid_Monster_User')->getDefeatCount($d['id'], $date);
            }else{
                $d['defeat_count'] = Service::create('Raid_Monster_User')->getDefeatCountAll($d['id']);
            }

        }

        // ビューに割り当てる。
        $this->setAttribute('raid_dungeon', $raid_dungeon["resultset"]);

        return View::SUCCESS;
    }
}
