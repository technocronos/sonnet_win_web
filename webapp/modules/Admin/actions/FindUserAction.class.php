<?php

class FindUserAction extends AdminBaseAction {

    public function execute() {

        if(isset($_POST['submit_chara_id'])) {
            //Array ( [submit_chara_id] => 3072114 [part] => Array ( [0] => 11027 [1] => 12024 [2] => 13024 [3] => 14024 ) [user_name] => W H [grade] => 60 [level] => 269 )

            //npcはuser_id=charaidの前提
            $id = $_POST["submit_chara_id"];

            $result1 = $this->update_name($id, $_POST["user_name"]);
            $result2 = $this->update_level($id, $_POST["level"]);
            $result3 = $this->update_grade($id, $_POST["grade"]);
            $result4 = $this->update_equip($id, $_POST["part"]);

            if($result1 || $result2 || $result3 || $result4)
              $result = true;
            else
              $result = false;
              
            Common::redirect(array('_self'=>true, 'update'=>$result));
        }

        // イメージが要求されている場合。
        if(isset($_GET['img_id'])) {
            $this->responseImage($_GET['img_id']);
            return View::NONE;
        }

        if(isset($_GET['update'])) {
            $this->setAttribute('update', $_GET['update']);
        }

        if(!isset($_GET["create_at_from"]))
            $_GET["create_at_from"] = RELEASE_DATE;

        $grades = Service::create('Grade_Master')->getList();
        array_unshift($grades, array("grade_id"=>0,"grade_name"=>""));
        $this->setAttribute('grades', ResultsetUtil::colValues($grades, 'grade_name', 'grade_id'));

        // 検査ルールの作成。
        $validator = new MyValidator(array(
            'go' => 'required',
            'id' => 'numonly',
            'character_id' => 'numonly',
            'access_date_from' => 'datetime',
            'access_date_to' => 'dateend',
            'create_at_from' => 'datetime',
            'create_at_to' => 'dateend',
            '_form' => array(
                array('lowerupper' => array('access_date_from', 'access_date_to')),
                array('lowerupper' => array('create_at_from', 'create_at_to')),
            ),
        ));
        $this->setAttribute('validator', $validator);

        // 入力値の検査。
        $validator->validate($_GET);

        // エラーがあるならココまで。
        if($validator->isError())
            return View::SUCCESS;

        // 指定された条件のユーザを取得する。
        $this->find($validator->values);

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された条件のユーザを取得する。
     *
     * @param object    フォームの入力値を正規化したバリデータ。
     */
    private function find($condition) {

        $userSvc = new User_InfoService();

        // 先に検索条件をシリアル化しておく。
        $this->setAttribute( 'target', urlencode(json_encode($condition)) );

        // ユーザ検索。
        $limit = 100;
        $condition['return_matched_rows'] = true;
        $ids = $userSvc->findUsers($condition, $limit);
        $matchedRows = $limit;

        // 抽出したユーザを取得。
        $table = $userSvc->getRecordsIn($ids);

        // アンインストールユーザを除いたカウントを得る。
        $condition['except_retire'] = true;
        $limit = 1;
        $userSvc->findUsers($condition, $limit);
        $liveRows = $limit;

        foreach($table as &$row){
            //不要なカラム削除
            unset($row["name"],$row["short_name"],$row["name_sync_date"],$row["retire_date"]);

            $chara = Service::create('Character_Info')->getAvatar($row["user_id"]);
            $charaEx = Service::create('Character_Info')->needExRecord($chara["character_id"]);

            $row["character_id"] = $chara["character_id"];

            $row["targetimage"] = $chara["character_id"];

            if($charaEx['equip'][1] != null){
                $set_data = Service::create('Set_Master')->getRecord($charaEx['equip'][1]['set_id']);
                $row["WEP"]["item_name"] =  $charaEx['equip'][1]["item_name"] . "(" . $set_data["set_name"] . ")";
                $row["WEP"]["item_id"] =  $charaEx['equip'][1]["item_id"];
            }else{
                $row["WEP"]["item_name"] = "無し";
                $row["WEP"]["item_id"] =  11001;
            }

            if($charaEx['equip'][2] != null){
                $set_data = Service::create('Set_Master')->getRecord($charaEx['equip'][2]['set_id']);
                $row["BOD"]["item_name"] =  $charaEx['equip'][2]["item_name"] . "(" . $set_data["set_name"] . ")";
                $row["BOD"]["item_id"] =  $charaEx['equip'][2]["item_id"];
            }else{
                $row["BOD"]["item_name"] = "無し";
                $row["BOD"]["item_id"] =  12001;
            }

            if($charaEx['equip'][3] != null){
                $set_data = Service::create('Set_Master')->getRecord($charaEx['equip'][3]['set_id']);
                $row["HED"]["item_name"] =  $charaEx['equip'][3]["item_name"] . "(" . $set_data["set_name"] . ")";
                $row["HED"]["item_id"] =  $charaEx['equip'][3]["item_id"];
            }else{
                $row["HED"]["item_name"] = "無し";
                $row["HED"]["item_id"] =  13001;
            }

            if($charaEx['equip'][4] != null){
                $set_data = Service::create('Set_Master')->getRecord($charaEx['equip'][4]['set_id']);
                $row["ACS"]["item_name"] =  $charaEx['equip'][4]["item_name"] . "(" . $set_data["set_name"] . ")";
                $row["ACS"]["item_id"] =  $charaEx['equip'][4]["item_id"];
            }else{
                $row["ACS"]["item_name"] = "無し";
                $row["ACS"]["item_id"] =  14001;
            }

            $text_log = Service::create('Text_Log')->getWriter($row["user_id"]);
            $row["name"] = $text_log[0]["body"];

            $row["grade"] = Service::create('Grade_Master')->name($chara["grade_id"]);
            $level = Service::create('Level_Master')->getLevelByExp("PLA", $chara["exp"]);
            $row["level"] = $level["level"];
            $row["exp"] = $chara["exp"];
            $row["paramseed"] = $chara["param_seed"];
            $row["最大HP"] = $chara["hp_max"];
            $row["攻撃(火)"] = $chara["attack1"] . "/" . $charaEx["total_attack1"];
            $row["攻撃(水)"] = $chara["attack2"] . "/" . $charaEx["total_attack2"];
            $row["攻撃(雷)"] = $chara["attack3"] . "/" . $charaEx["total_attack3"];
            $row["防御(火)"] = $chara["defence1"] . "/" . $charaEx["total_defence1"];
            $row["防御(水)"] = $chara["defence2"] . "/" . $charaEx["total_defence2"];
            $row["防御(雷)"] = $chara["defence3"] . "/" . $charaEx["total_defence3"];
            $row["speed"] = $chara["speed"] . "/" . $charaEx["total_speed"];

            $payment = Service::create('Payment_Log')->sumupUserPayment($row["user_id"]);
            $row["total_payment"] = $payment[0]["sales"];

            // coinアイテムのuser_itemレコードを取得。
            $coin_info = Service::create('User_Item')->getRecordByItemId($row["user_id"], COIN_ITEM_ID);

            $row["coin"] = $coin_info['num'];
            $row["virtual_coin"] = sprintf('%f', $row["virtual_coin"]);

            $appsflyer = new AppsflyerService();
            $appsflyer_info = $appsflyer->getRecord($row["platform_uid"]);

            $row["af_status"] = $appsflyer_info["af_status"];
            $row["campaign"] = $appsflyer_info["campaign"];
            $row["media_source"] = $appsflyer_info["media_source"];

        }

        $svc = new Equippable_MasterService();
        $this->setAttribute('pla_weapon', $svc->getEquipList('PLA', Mount_MasterService::PLAYER_WEAPON));
        $this->setAttribute('pla_body', $svc->getEquipList('PLA', Mount_MasterService::PLAYER_BODY));
        $this->setAttribute('pla_head', $svc->getEquipList('PLA', Mount_MasterService::PLAYER_HEAD));
        $this->setAttribute('pla_shield', $svc->getEquipList('PLA', Mount_MasterService::PLAYER_SHIELD));

        $level_master = Service::create('Level_Master')->getAllRecord("PLA");
        $this->setAttribute('level_master', $level_master);

        $gradeSvc = new Grade_MasterService();

        // ユーザが存在する最も高い番付を取得。
        $highest = $gradeSvc->getHighestGrade();

        // 番付の一覧を取得。
        $this->setAttribute('grade_list', $gradeSvc->getList('DESC', $highest));

        // ビューに割り当て。
        $this->setAttribute('table', $table);
        $this->setAttribute('hit', $matchedRows);
        $this->setAttribute('live', $liveRows);
    }

    //-----------------------------------------------------------------------------------------------------
    private function responseImage($character_id) {

            $charaEx = Service::create('Character_Info')->needExRecord($character_id);

            $charaEqp = array(
                'race' => 'PLA',
                'graphic_id' => Character_InfoService::INITIAL_FACE,
                'admin_check' => array(
                    Mount_MasterService::PLAYER_WEAPON => ($charaEx['equip'][1]["item_id"] != null) ? $charaEx['equip'][1]["item_id"] : 11001,
                    Mount_MasterService::PLAYER_BODY =>   ($charaEx['equip'][2]["item_id"] != null) ? $charaEx['equip'][2]["item_id"] : 12001,
                    Mount_MasterService::PLAYER_HEAD =>   ($charaEx['equip'][3]["item_id"] != null) ? $charaEx['equip'][3]["item_id"] : 13001,
                    Mount_MasterService::PLAYER_SHIELD => ($charaEx['equip'][4]["item_id"] != null) ? $charaEx['equip'][4]["item_id"] : 14001,
                ),
            );

            // イメージ作成＆パス取得。
            $img = CharaImageUtil::getImageFromChara($charaEqp, 'swf');

            // 出力。
            header('Content-Type: image/png');
            readfile($img);
    }


    //-----------------------------------------------------------------------------------------------------
    private function update_name($character_id, $name) {

        $chara = Service::create('Character_Info')->getRecord($character_id);

        $text_log = Service::create('Text_Log')->getWriter($chara["user_id"]);

        if($name != $text_log[0]["body"]){
            Service::create('Text_Log')->updateText($text_log[0]["text_id"],$name);
            return true;
        }

        return false;
    }

    //-----------------------------------------------------------------------------------------------------
    private function update_level($charaId, $level) {

        $flg = false;

        $charaEx = Service::create('Character_Info')->needExRecord($charaId);

        if($charaEx["level"] != $level){
            //経験値更新
            $level_master = Service::create('Level_Master')->getRecord("PLA", $level);

            Service::create('Character_Info')->updateRecord($charaId, array(
              'exp' => $level_master['exp'],
            ));

            // 現在のキャラ情報を取得。
            $chara = Service::create('Character_Info')->needRecord($charaId);

            // 経験値取得によるレベルアップ情報を取得。
            $growth = Service::create('Level_Master')->getGrowth($chara['race'], 0, $chara['exp']);

            //アイテムステータスポイント
            $flagSvc = new Flag_LogService();
            $item1 = $flagSvc->getValue(Flag_LogService::PARAM_UP, $charaId, 1201);
            $item2 = $flagSvc->getValue(Flag_LogService::PARAM_UP, $charaId, 1202);
            $item3 = $flagSvc->getValue(Flag_LogService::PARAM_UP, $charaId, 1203);
            $item_param = ($item1 + $item2 + $item3) * 3;

            $param_seed = $growth['param_growth'] + $item_param;

            //param_seedは適当に振り分けてしまう
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

            $growth['param_growth'] = 0;
            $param_seed = 0;

            // キャラ情報に、経験値取得・能力アップを反映。
            Service::create('Character_Info')->saveRecord(array(
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

            $flg = true;
            
        }

        return $flg;
    }

    //-----------------------------------------------------------------------------------------------------
    private function update_grade($character_id, $grade_id) {

        $chara = Service::create('Character_Info')->getRecord($character_id);
        $grade = Service::create('Grade_Master')->getRecord($chara["grade_id"]);

        if($grade_id != $grade["grade_id"]){
            Service::create('Character_Info')->updateRecord($character_id, array(
              'grade_id' => $grade_id,
            ));
            return true;
        }

        return false;

    }

    //-----------------------------------------------------------------------------------------------------
    private function update_equip($character_id, $equip) {

        $equipSvc = new Character_EquipmentService();

        $chara = Service::create('Character_Info')->getRecord($character_id);

        $charaEx = Service::create('Character_Info')->needExRecord($chara["character_id"]);

        $flg = false;

        foreach($equip as $key=>$value){
            $mountId = $key + 1;
            if($value != $charaEx['equip']["item_id"]){
                // 装備変更。
                $change = ($value == '11001' || $value == '12001'  || $value == '13001'  || $value == '14001' ) ? null : $value;
                $uitemId = null;

                if($change){
                    $useritem = Service::create('User_Item')->getRecordByItemId($chara["user_id"], $change);
                    if($useritem == null){
                        $uitemId =  Service::create('User_Item')->gainItem($chara["user_id"], $change);
                    }else{
                        $uitemId =  $useritem["user_item_id"];
                    }
                }

                $equipSvc->changeEquipment($character_id, $mountId, $uitemId);

                $flg = true;
            }
        }

        return $flg;
    }

}
