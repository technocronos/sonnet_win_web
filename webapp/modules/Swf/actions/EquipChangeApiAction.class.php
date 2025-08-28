<?php

/**
 * バトルフラッシュで、開始時の確認のためにリクエストされるアクション。
 */
class EquipChangeApiAction extends ApiBaseAction {

    protected function doExecute($params) {

//Common::varLog("ok");
        //値段を得るだけの場合はチェックはしない。
        if($_GET['base_id'] && $_GET["source_id"]){
            $base = Service::create('User_Item')->getRecord($_GET['base_id']);
            $source = Service::create('User_Item')->getRecord($_GET['source_id']);

            $response['price'] = $this->getPrice($base, $source);
            return $response;
        }

        // 指定されているキャラクタを取得。
        $chara = Service::create('Character_Info')->needExRecord($_GET['charaId']);

        // 他人のキャラだったらエラー。
        if($chara['user_id'] != $this->user_id)
            return array('result'=>"not_me");

        // フィールドクエスト中の場合は装備変更不可
        if($chara['sally_sphere'])
            return array('result'=>"in_quest");

        if($_GET['func'] == 'auto')
            $response = $this->processAutoEquipment($chara);
        else if($_GET['func'] == 'release')
            $response = $this->processAllRelease($chara);
        else if($_GET['change'])
            $response = $this->processChange($chara);
        else if($_GET['synth'])
            $response = $this->processSynth($chara);

        // Flashに結果コードを返す。
        return $response;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 装備変更のリクエストを処理する。
     */
    private function processChange(&$chara) {
        // GETリクエストだけで装備変更が行えてしまうので、簡単ながらCSRFに備える。
        if( !Common::validateSign() )
            $this->redirect(array('_self'=>true, 'change'=>''));

        // 装備変更。
        $equipSvc = new Character_EquipmentService();
        $change = ($_GET['change'] == 'NONE') ? null : $_GET['change'];
        $equipSvc->changeEquipment($_GET['charaId'], $_GET['mountId'], $change);

        return array("result" => 'ok');
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 自動装備を処理する。
     */
    public function processAutoEquipment(&$chara) {

        $uitemSvc = new User_ItemService();
        $equipSvc = new Character_EquipmentService();

        // とりあえず今の装備を外す
        $this->processAllRelease($chara);

        // 装備箇所の一覧を取得。
        $mounts = Service::create('Mount_Master')->getMounts($chara['race']);

        // ユーザ所持アイテムの基本検索条件を作成。
        $condition = array();
        $condition['user_id'] = $this->user_id;
        $condition['race'] = $chara['race'];

        // 装備可能箇所を一つずつ見ていく。
        foreach($mounts as $mount) {

            // 装備可能な所持アイテムを取得。
            $condition['mount_id'] = $mount['mount_id'];
            $uitems = $uitemSvc->getHoldList($condition, 0);

            // 取得した所持アイテムを一つずつ見て、最も強いものを変数 $decision に格納する。
            $decision = null;
            $currentScore = 0;
            foreach($uitems as $uitem) {

                // 他のキャラが装備しているものはスキップ
                if(!$uitem['free_count'])
                    continue;

                // 装備できない場合はスキップ
                if( !$equipSvc->isEquippable($chara['character_id'], $mount['mount_id'], $uitem['user_item_id'], false) )
                    continue;

                // スコアを計算。
                $score = 0;
                $score += $uitem['attack1'] + $uitem['attack2'] + $uitem['attack3'] + $uitem['speed'];
                $score += $uitem['defence1'] + $uitem['defence2'] + $uitem['defence3'] + $uitem['defenceX'];

                // 現在保持している装備より良いスコアなら変更する。
                if($currentScore < $score) {
                    $currentScore = $score;
                    $decision = $uitem;
                }
            }

            // 良い装備があったなら変更。
            if($decision){
                $equipSvc->changeEquipment($chara['character_id'], $mount['mount_id'], $decision['user_item_id']);
            }
        }

        // 装備チュートリアル中ならステップアップ
        Service::create('User_Info')->tutorialStepUp($this->user_id, User_InfoService::TUTORIAL_EQUIP);

        return array("result" => 'ok');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 装備全解除を処理する。
     */
    public function processAllRelease(&$chara) {

        // 装備箇所の一覧を取得。
        $mounts = Service::create('Mount_Master')->getMounts($chara['race']);

        // 各箇所の装備を外していく。
        $equipSvc = new Character_EquipmentService();
        foreach($mounts as $mount){
            $equipSvc->changeEquipment($chara['character_id'], $mount['mount_id'], null);
        }

        return array("result" => 'ok');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 装備合成のリクエストを処理する。
     */
    private function processSynth(&$chara) {

        //合成する装備情報
        $uitem = Service::create('User_Item')->getRecord($_GET['synth']);

        //存在チェック
        if(!$uitem)
            return array('result' => "noitem");

        //現在の装備(ベース)
        $currentExuip = $chara["equip"][$_GET['mountId']];

        //現在の装備は無条件に合成不可
        if($uitem['user_item_id'] == $currentExuip['user_item_id'])
            return array('result' => "equipping");

        $maxLv = Service::create('Item_Level_Master')->getMaxLevel($currentExuip['item_id']);

        //現在装備してるのがすでにMAXならリンクしない
        if($maxLv <= $currentExuip['level'])
            return array('result' => "maxlevel");

        $price = $this->getPrice($currentExuip, $uitem);
        $add_exp = $this->getExp($currentExuip, $uitem);

        //マグナが足りない
        if($this->userInfo["gold"] < $price)
            return array('result' => "nomoney");

        //ここまでOKなら合成する

        // 装備変更。
        $uitemSvc = new User_ItemService();

        // 合成処理して、afterに格納。
        $after = $uitemSvc->spendExp($currentExuip['user_item_id'], $add_exp);

        // レコード削除
        $uitemSvc->deleteRecord($uitem['user_item_id']);

        // ゲーム内通貨減算
        Service::create('User_Info')->plusValue($this->user_id, array(
            'gold' => -1 * $price ,
        ));

        //情報を返す
        $response["aex"] = $after["item_exp"];
        $response["alv"] = $after["level"];
        $response["blv"] = $currentExuip['level'];
        $response["bex"] = $currentExuip['item_exp'];
        $response["bgld"] = $this->userInfo["gold"];
        $response["agld"] = $this->userInfo["gold"] - $price;

        //レベルが上がってる場合
        if($currentExuip['level'] < $after["level"]){

        }

        $response["result"] = "ok";

        return $response;
    }

    //-----------------------------------------------------------------------------------------------------
   /*
    今の装備に合成するのにかかるマグナの値段を得る
    */
    public function getPrice($base_uitem, $material_uitem) {

        //開放レベル + 武器レベル /4 * 100くらいを想定 level80->2000

        //ベースアイテム情報を得る
        $base_item = Service::create('Item_Master')->getRecord($base_uitem['item_id']);
        //素材アイテム情報を得る
        $material_item = Service::create('Item_Master')->getRecord($material_uitem['item_id']);

        $eqpSvc = new Equippable_MasterService();

        $baseRearLevel = $eqpSvc->rear_weight_table[$base_item['rear_level']];
        $souceRearLevel = $eqpSvc->rear_weight_table[$material_item['rear_level']];

        //何を合成するかを値段に反映する
        $tmp = floor($baseRearLevel - ($baseRearLevel - $souceRearLevel) + ($material_uitem['level'] / 2));

        if($tmp <= 0)
            $tmp = 1;

        $price = ( ($tmp / 4) * 100 );

        return $price;
    }

    //-----------------------------------------------------------------------------------------------------
   /*
    今の装備に合成すると得られる経験値を得る
    */
    public function getExp($base_uitem, $material_uitem) {

        //ベースアイテム情報を得る
        $base_item = Service::create('Item_Master')->getRecord($base_uitem['item_id']);
        //素材アイテム情報を得る
        $material_item = Service::create('Item_Master')->getRecord($material_uitem['item_id']);

        $eqpSvc = new Equippable_MasterService();

        $baseRearLevel = $eqpSvc->rear_weight_table[$base_item['rear_level']];
        $souceRearLevel = $eqpSvc->rear_weight_table[$material_item['rear_level']];

        $add_exp = (25 - ($baseRearLevel - $souceRearLevel)) + ($material_uitem["level"] * 3);

        //全く同じ装備の場合
        if($base_item["item_id"] == $material_item["item_id"]){

            //0以下の場合は装備レベル分だけ
            if($add_exp <= 0)
                $add_exp = $uitem["level"];

            $add_exp = floor($add_exp * 2);
        }

        //0以下の場合はゴミ合成。1経験値だけボーナス。
        if($add_exp <= 0)
            $add_exp = 1;

//print_r("baseRearLevel=" . $baseRearLevel);
//print_r("souceRearLevel=" . $souceRearLevel);
//print_r("add_exp=" . $add_exp);

        return $add_exp;
    }
}
