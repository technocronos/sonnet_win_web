<?php

/**
 * ---------------------------------------------------------------------------------
 * 装備リストを返す
 * ---------------------------------------------------------------------------------
 */
class EquipListApiAction extends ApiBaseAction {

    protected function doExecute($params) {

        // ステータス画面、装備の情報をセット
        $charaSvc = new Character_InfoService();
        $avatar = $charaSvc->needAvatar($this->user_id, true);

        // 指定されているキャラクタを取得。
        $chara = Service::create('Character_Info')->needExRecord($avatar['character_id']);

        // 装備箇所の一覧を取得。
        $mounts = Service::create('Mount_Master')->getMounts($chara['race']);

        foreach($mounts as $mount){
            if($chara['equip'][$mount['mount_id']]['set_id']){
                //セット情報を追加する
                $set_data = Service::create('Set_Master')->getRecord($chara['equip'][$mount['mount_id']]['set_id']);

                $chara['equip'][$mount['mount_id']]['mount_id'] = $mount["mount_id"];
                $chara['equip'][$mount['mount_id']]['mount_name'] = $mount["mount_name"];

                $chara['equip'][$mount['mount_id']]['set_id'] = $set_data["set_id"];
                $chara['equip'][$mount['mount_id']]['rear_id'] = $set_data['rear_id'];
                $chara['equip'][$mount['mount_id']]['set_name'] = $set_data['set_name'];
                $chara['equip'][$mount['mount_id']]['set_text'] = $set_data['set_text'];
            }

            // 装備可能なアイテム一覧を取得。
            $condition = array('user_id'=>$this->user_id, 'race'=>$chara['race'], 'mount_id'=>$mount["mount_id"]);
            $list = Service::create('User_Item')->getHoldList($condition, 0);

            //セット情報をマージ
            foreach($list as &$row){
                $set = Service::create('Set_Master')->getRecord($row['set_id']);
                $row['set_name'] = $set["set_name"];
                $row['rear_id'] = $set["rear_id"];

                //MAXレベル
                $maxLv = Service::create('Item_Level_Master')->getMaxLevel($row['item_id']);
                $row['max_level'] = $maxLv;
            }

            //自分が持っている装備一覧
            $array['equip'][$mount["mount_id"]] = $list;
            $array['equip'][$mount["mount_id"]]["Num"] = count($list);

        }

        foreach($chara['equip'] as &$row){
            //MAXレベル
            $maxLv = Service::create('Item_Level_Master')->getMaxLevel($row['item_id']);
            $row['max_level'] = $maxLv;
        }

        //自分の現在の装備一覧
        $array['PLAEQP'] = $chara['equip'];

        // 消費アイテム一覧を取得。仕様を軽くするためSYSアイテムに関しては現在の所ガチャチケットだけなので削る。
        $condition = array('user_id'=>$this->user_id, 'category'=>'ITM');
        $list = Service::create('User_Item')->getHoldList($condition, 0);
        $targetId = Service::create('Character_Info')->needAvatarId($this->user_id);

        foreach($list as &$row){
            $str = AppUtil::itemEffectStr($row);
            $row['effect'] = $str;

            $row['useable'] = 0;

            // 使用可能なアイテムならアイテム名にリンクを施す。
            if($row['category'] == 'ITM'  &&  in_array($row['item_type'], Item_MasterService::$ON_CONFIG)  &&  $row['free_count'] > 0) {
                $row['useable'] = 1;
            }

            $row['useCount'] = 0;
            // ステータスアップアイテムを使おうとしている場合はあと何回使えるのかを表示する。
            if($row['item_type'] == Item_MasterService::INCR_PARAM) {
                $useCount = Service::create('Flag_Log')->getValue(Flag_LogService::PARAM_UP, $targetId, $row['item_id']);
                $row['useCount'] = $useCount;
            }
        }

        //自分が持っている消費アイテム一覧
        //システマチックに扱えるようにアイテムはequip5として扱う。
        $array['equip']["5"] = $list;
        $array['equip']["5"]['Num'] = count($list);

        $array['result'] = 'ok';

        return $array;

    }
}
