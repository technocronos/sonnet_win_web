<?php

/**
 * ---------------------------------------------------------------------------------
 * 装備リストを返す
 * ---------------------------------------------------------------------------------
 */
class EquipListAction extends SmfBaseAction {

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

                $chara['equip'][$mount['mount_id']]['set_id'] = !is_null($set_data["set_id"]) ? $set_data["set_id"] : 0;
                $chara['equip'][$mount['mount_id']]['rear_id'] = !is_null($set_data['rear_id']) ? $set_data['rear_id'] : 0;
                $chara['equip'][$mount['mount_id']]['set_name'] = $set_data['set_name'];
                $chara['equip'][$mount['mount_id']]['set_text'] = $set_data['set_text'];
            }

            $numOnPage = 0;

            // 装備可能なアイテム一覧を取得。
            $condition = array('user_id'=>$this->user_id, 'race'=>$chara['race'], 'mount_id'=>$mount["mount_id"]);
            $list = Service::create('User_Item')->getHoldList($condition, $numOnPage, 0);

            if($numOnPage > 0)
                $list = $list["resultset"];

            //セット情報をマージ
            foreach($list as &$row){
                $set = Service::create('Set_Master')->getRecord($row['set_id']);
                $row['set_name'] = $set["set_name"];
                $row['rear_id'] = (int)$set["rear_id"];
                $row['set_id'] = (int)$set["set_id"];

                //MAXレベル
                $maxLv = Service::create('Item_Level_Master')->getMaxLevel($row['item_id'], $row['evolution']);
                $row['max_level'] = $maxLv;

                $row["is_evol"] = false;

                if($chara['equip'][$mount['mount_id']]["item_id"] == $row['item_id']){

                    //通常レベルMAX値
                    $maxLv = Service::create('Item_Level_Master')->getMaxLevel($chara['equip'][$mount['mount_id']]['item_id']);

                    //現在装備してるのがまだMAXでないなら
                    if($chara['equip'][$mount['mount_id']]["evolution"] == 0 && $maxLv > $chara['equip'][$mount['mount_id']]['level'])
                        continue;

                    //対象装備がまだMAXでないなら
                    if($row["evolution"] == 0 && $maxLv > $row['level'])
                        continue;

                    //進化レベル
                    $evolMaxLv = Service::create('Item_Level_Master')->getMaxLevel($chara['equip'][$mount['mount_id']]['item_id'], 1);

                    //進化レコードが無い
                    if($evolMaxLv == 0)
                        continue;

                    //現在装備してるのがすでに進化MAXなら
                    if($chara['equip'][$mount['mount_id']]["evolution"] == 1 && $evolMaxLv <= $chara['equip'][$mount['mount_id']]['level'])
                        continue;

                    //対象装備がすでに進化MAXなら
                    if($row["evolution"] == 1 && $evolMaxLv <= $row['level'])
                        continue;

                    //ここまで来ればOK
                    $row["is_evol"] = true;

                }
            }

            //自分が持っている装備一覧
            $array['equip'][$mount["mount_id"]] = $list;
            //$array['equip'][$mount["mount_id"]]["Num"] = count($list);

        }

        foreach($chara['equip'] as &$row){
            $row["set_id"] = (int)$row["set_id"];

            //MAXレベル
            $maxLv = Service::create('Item_Level_Master')->getMaxLevel($row['item_id'], $row['evolution']);
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

            $row["level"] = (int)$row["level"];
            $row["attack1"] = (int)$row["attack1"];
            $row["attack2"] = (int)$row["attack2"];
            $row["attack3"] = (int)$row["attack3"];
            $row["defence1"] = (int)$row["defence1"];
            $row["defence2"] = (int)$row["defence2"];
            $row["defence3"] = (int)$row["defence3"];
            $row["speed"] = (int)$row["speed"];
            $row["defenceX"] = (int)$row["defenceX"];
            $row["set_id"] = (int)$row["set_id"];
            $row["rear_level"] = (int)$row["rear_level"];
            $row["evolution"] = (int)$row["evolution"];

        }

        //自分が持っている消費アイテム一覧
        //システマチックに扱えるようにアイテムはequip5として扱う。
        $array['equip']["5"] = $list;
        //$array['equip']["5"]['Num'] = count($list);

        $array['result'] = 'ok';

        return $array;

    }
}
