<?php

/**
 * キャラクター経験値付与。
 * デバックメニュー。
 */
class FixExpAction extends AdminBaseAction {

    public function execute() {

        $charaSvc = new Character_InfoService();

        // フォームが送信されている場合。
        if($_POST) {
            $levelSvc = new Level_MasterService();
            $charaSvc = new Character_InfoService();
            $flagSvc = new Flag_LogService();

            if($_POST["func"] == "fix"){
                $charaStr = $_POST['charaId'];
                $npcflg = $_POST['npcflg'];

                if($charaStr == ""){
                    print_r("charaIdがない");
                    exit;
                }

                $ArrCharaId = split(",", $charaStr);

                foreach($ArrCharaId as $key=> $charaId){

                    Common::varLog("パラメータ修正  FixExpAction start.. charaId = " . $charaId);

                    // 現在のキャラ情報を取得。
                    $chara = $charaSvc->needRecord($charaId);

                    Common::varLog($chara);

                    //現在のレベルでこれまで得たステータスポイントを全部得る
                    $param_seed = $levelSvc->getAllParam((int)$chara['level']);

                    Common::varLog("現在のレベルでこれまで得たステータスポイント:" . $param_seed);

                    //アイテムステータスポイント
                    $item1 = $flagSvc->getValue(Flag_LogService::PARAM_UP, $charaId, 1201);
                    $item2 = $flagSvc->getValue(Flag_LogService::PARAM_UP, $charaId, 1202);
                    $item3 = $flagSvc->getValue(Flag_LogService::PARAM_UP, $charaId, 1203);
                    $item_param = ($item1 + $item2 + $item3) * 3;

                    Common::varLog("アイテムによるステータスポイント:" . $item_param);

                    //足したものが持っているべきステータスポイント
                    $param_seed = $param_seed + $item_param;

                    Common::varLog("持っているべきステータスポイント param_seed=" . $param_seed);

                    //現在の持ちパラメータ
                    $current_param = $chara["param_seed"] + (($chara["hp_max"] - Character_InfoService::INITIAL_HP) / Character_InfoService::HP_SCALE) + ($chara["attack1"] - Character_InfoService::INITIAL_ATTACK) + ($chara["attack2"] - Character_InfoService::INITIAL_ATTACK) + ($chara["attack3"] - Character_InfoService::INITIAL_ATTACK) + ($chara["defence1"] - Character_InfoService::INITIAL_DEFENCE) + ($chara["defence2"] - Character_InfoService::INITIAL_DEFENCE) + ($chara["defence3"] - Character_InfoService::INITIAL_DEFENCE) + ($chara["speed"] - Character_InfoService::INITIAL_SPEED);

                    Common::varLog("現在の持ちパラメータ current_param=" . $current_param);

                    //現在の持ちパラメータが違う場合
                    if($current_param != $param_seed){
                        Common::varLog("現在の持ちパラメータが違うので修正します");

                        // 経験値取得によるレベルアップ情報を取得。
                        $growth = $levelSvc->getGrowth($chara['race'], 0, $chara['exp']);
                        Common::varLog($growth);

                        $param_seed = $growth['param_growth'] + $item_param;
      
                        //NPCの場合は$param_seedは適当に振り分けてしまう
                        if($npcflg == 1){
                            Common::varLog("NPCユーザーとして処置。持ちパラメータを全部自動的に振り分けます");

                            for($i = 0; $i < $param_seed; $i++){
                                switch(mt_rand(1, 8)){
                                    case 1:
                                        $growth['attack1_growth']++;
                                        break;
                                    case 2:
                                        $growth['attack2_growth']++;
                                        break;
                                    case 3:
                                        $growth['attack3_growth']++;
                                        break;
                                    case 4:
                                        $growth['defence1_growth']++;
                                        break;
                                    case 5:
                                        $growth['defence2_growth']++;
                                        break;
                                    case 6:
                                        $growth['defence3_growth']++;
                                        break;
                                    case 7:
                                        $growth['speed_growth']++;
                                        break;
                                    case 8:
                                        $growth['hp_growth']++;
                                        break;
                                }
                            }

                            Common::varLog("振り分けました。" . $param_seed . "pt");

                            $growth['param_growth'] = 0;
                            $param_seed = 0;
                        }

                        // キャラ情報に、経験値取得・能力アップを反映。
                        $charaSvc->saveRecord(array(
                            'character_id' => $charaId,
                            'param_seed' => $param_seed,
                            'attack1' => Character_InfoService::INITIAL_ATTACK + $growth['attack1_growth'],
                            'attack2' => Character_InfoService::INITIAL_ATTACK + $growth['attack2_growth'],
                            'attack3' => Character_InfoService::INITIAL_ATTACK + $growth['attack3_growth'],
                            'defence1' => Character_InfoService::INITIAL_DEFENCE + $growth['defence1_growth'],
                            'defence2' => Character_InfoService::INITIAL_DEFENCE + $growth['defence2_growth'],
                            'defence3' => Character_InfoService::INITIAL_DEFENCE + $growth['defence3_growth'],
                            'speed' => Character_InfoService::INITIAL_SPEED + $growth['speed_growth'],
                            'hp_max' => Character_InfoService::INITIAL_HP + ($growth['hp_growth'] * Character_InfoService::HP_SCALE),
                            'hp' => Character_InfoService::INITIAL_HP + ($growth['hp_growth'] * Character_InfoService::HP_SCALE),
                        ));

                        if($npcflg == 1){
                            //取り直し
                            $chara = $charaSvc->needRecord($charaId);
                            $this->setEquip($chara);
                        }
                        Common::varLog("処理完了 FixExpAction end..");

                    }else{
                        Common::varLog("このユーザーは正常です");
                        if($npcflg == 1){
                            //取り直し
                            $chara = $charaSvc->needRecord($charaId);
                            $this->setEquip($chara);
                        }
                    }
                }

                Common::redirect(array('_self'=>true, 'charaId'=>$_POST['charaId']));

            }else if($_POST["func"] == "show"){
                $userSvc = new User_InfoService();
                $condition['return_matched_rows'] = true;
                $condition['create_at_from'] = RELEASE_DATE;
                $ids = $userSvc->findUsers($condition);

                $count = 0;

                foreach($ids as $id){
                    $chara = $charaSvc->getAvatar($id);
                    $charaId = $chara["character_id"];

                   //現在のレベルでこれまで得たステータスポイントを全部得る
                    $param_seed = $levelSvc->getAllParam((int)$chara['level']);

                    //アイテムステータスポイント
                    $item1 = $flagSvc->getValue(Flag_LogService::PARAM_UP, $charaId, 1201);
                    $item2 = $flagSvc->getValue(Flag_LogService::PARAM_UP, $charaId, 1202);
                    $item3 = $flagSvc->getValue(Flag_LogService::PARAM_UP, $charaId, 1203);
                    $item_param = ($item1 + $item2 + $item3) * 3;

                    //足したものが持っているべきステータスポイント
                    $param_seed = $param_seed + $item_param;

                    //現在の持ちパラメータ
                    $current_param = $chara["param_seed"] + (($chara["hp_max"] - Character_InfoService::INITIAL_HP) / Character_InfoService::HP_SCALE) + ($chara["attack1"] - Character_InfoService::INITIAL_ATTACK) + ($chara["attack2"] - Character_InfoService::INITIAL_ATTACK) + ($chara["attack3"] - Character_InfoService::INITIAL_ATTACK) + ($chara["defence1"] - Character_InfoService::INITIAL_DEFENCE) + ($chara["defence2"] - Character_InfoService::INITIAL_DEFENCE) + ($chara["defence3"] - Character_InfoService::INITIAL_DEFENCE) + ($chara["speed"] - Character_InfoService::INITIAL_SPEED);

                    //現在の持ちパラメータが違う場合
                    if($current_param != $param_seed){
                        $count++;
                        print_r("charaId=" . $charaId);
                        print_r("<br>");
                        print_r("アイテムによるステータスポイント:" . $item_param);
                        print_r("<br>");
                        print_r("持っているべきステータスポイント param_seed=" . $param_seed);
                        print_r("<br>");
                        print_r("現在の持ちパラメータ current_param=" . $current_param);
                        print_r("<br>");
                        print_r("<br>");
                    }
                }
                print_r($count . "件処理が必要です");
            }
        }

        // キャラ情報を取得。
        if( isset($_GET['charaId']) ){
                $charaStr = $_GET['charaId'];

                $ArrCharaId = split(",", $charaStr);

                foreach($ArrCharaId as $key=> $charaId){
                    $arr[] = $charaSvc->getRecord($charaId);
                }

            $this->setAttribute('chara', $arr);
        }

        return View::SUCCESS;
    }

    private function setEquip($chara){

        Common::varLog("レベルにあった装備を自動的に装備します。 level=" . $chara['level']);

        $user_id = $chara["user_id"];
        $level = (int)$chara['level'];

        $rand = mt_rand(1, 3);

        if($level >= 1 && $level <= 5){
            switch($rand){
                case 1:
                    //初心者セット
                    $set_id = 10001;
                    break;
                case 2:
                    //シーフセット
                    $set_id = 10002;
                    break;
                case 3:
                    //ネコセット
                    $set_id = 10004;
                    break;
            }
        }else if($level >= 6 && $level <= 10){
            switch($rand){
                case 1:
                    //シーフセット
                    $set_id = 10002;
                    break;
                case 2:
                    //クリスタルセット
                    $set_id = 10003;
                    break;
                case 3:
                    //水着セット
                    $set_id = 10005;
                    break;
            }
        }else if($level >= 11 && $level <= 20){
            switch($rand){
                case 1:
                    //ネコセット
                    $set_id = 10004;
                    break;
                case 2:
                    //甲冑セット
                    $set_id = 10006;
                    break;
                case 3:
                    //ドレスセット
                    $set_id = 10007;
                    break;
            }
        }else if($level >= 21 && $level <= 30){
            switch($rand){
                case 1:
                    //ドレスセット
                    $set_id = 10007;
                    break;
                case 2:
                    //エルフセット
                    $set_id = 10008;
                    break;
                case 3:
                    //アリスセット
                    $set_id = 10009;
                    break;
            }
        }else if($level >= 31 && $level <= 40){
            switch($rand){
                case 1:
                    //ホームズセット
                    $set_id = 10010;
                    break;
                case 2:
                    //お嬢様セット
                    $set_id = 10011;
                    break;
                case 3:
                    //ダサロボセット
                    $set_id = 10012;
                    break;
            }
        }else if($level >= 41 && $level <= 50){
            switch($rand){
                case 1:
                    //ダサロボセット
                    $set_id = 10012;
                    break;
                case 2:
                    //科学セット
                    $set_id = 10013;
                    break;
                case 3:
                    //踊り子セット
                    $set_id = 10014;
                    break;
            }
        }else if($level >= 51 && $level <= 60){
            switch($rand){
                case 1:
                    //踊り子セット
                    $set_id = 10014;
                    break;
                case 2:
                    //戦士セット
                    $set_id = 10015;
                    break;
                case 3:
                    //ユニコーンセット
                    $set_id = 10016;
                    break;
            }
        }else if($level >= 61 && $level <= 70){
            switch($rand){
                case 1:
                    //ユニコーンセット
                    $set_id = 10016;
                    break;
                case 2:
                    //くの一セット
                    $set_id = 10017;
                    break;
                case 3:
                    //海賊セット
                    $set_id = 10018;
                    break;
            }
        }else if($level >= 71 && $level <= 80){
            switch($rand){
                case 1:
                    //ギャングセット
                    $set_id = 10019;
                    break;
                case 2:
                    //ヒーローセット
                    $set_id = 10020;
                    break;
                case 3:
                    //ドラゴンセット
                    $set_id = 10021;
                    break;
            }
        }else if($level >= 81 && $level <= 90){
            switch($rand){
                case 1:
                    //魔女セット プロト
                    $set_id = 10122;
                    break;
                case 2:
                    //ギャングセット プロト
                    $set_id = 10119;
                    break;
                case 3:
                    //海賊セット プロト
                    $set_id = 10118;
                    break;
            }
        }else if($level >= 91 && $level <= 100){
            switch($rand){
                case 1:
                    //魔女セット プロト
                    $set_id = 10122;
                    break;
                case 2:
                    //ギャングセット プロト
                    $set_id = 10119;
                    break;
                case 3:
                    //魔女セット
                    $set_id = 10022;
                    break;
            }
        }else{
            switch($rand){
                case 1:
                    //魔女セット
                    $set_id = 10022;
                    break;
                case 2:
                    //オリハルコンセット
                    $set_id = 10023;
                    break;
                case 3:
                    //巫女セット
                    $set_id = 10024;
                    break;
            }
        }

        for($i = 1; $i <= 4; $i++){
            $item = Service::create('Item_Master')->getSetItem($set_id , $i);
            // 取得。
            $userItemId = Service::create('User_Item')->gainItem($user_id, $item["item_id"]);

            Service::create('Character_Equipment')->changeEquipment($chara['character_id'], $i, $userItemId);

            Common::varLog($item["item_name"] . "を付与、装備しました");
        }

    }

}
