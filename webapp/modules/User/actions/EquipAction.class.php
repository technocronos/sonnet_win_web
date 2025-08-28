<?php

class EquipAction extends UserBaseAction {

    public function execute() {

        // 指定されているキャラクタを取得。
        $chara = Service::create('Character_Info')->needExRecord($_GET['charaId']);

        // 装備箇所の一覧を取得。
        $mounts = Service::create('Mount_Master')->getMounts($chara['race']);
        $this->setAttribute('mounts', $mounts);

		foreach($mounts as $mount){
			if($chara['equip'][$mount['mount_id']]['set_id']){
		        $set_data = Service::create('Set_Master')->getRecord($chara['equip'][$mount['mount_id']]['set_id']);
				$chara['equip'][$mount['mount_id']]['set'] = $set_data;
			}
		}

        $this->setAttribute('character', $chara);

        // 他人のキャラだったらエラー。
        if($chara['user_id'] != $this->user_id)
            throw new MojaviException('他人のキャラで振り分けをしようとした');

        // ボタンが押されている場合に...
        if($_POST) {

            // フィールドクエスト中の場合は装備変更不可
            if($chara['sally_sphere'])
                Common::redirect(array('_self'=>true, 'result'=>'sphere'));

            // 選択された機能を実行して結果画面へ。
            if($_POST['function'] == 'auto_equip')
                $this->processAutoEquipment($chara);
            else
                $this->processAllRelease($chara);

            Common::redirect(array('_self'=>true, 'result'=>$_POST['function']));
        }

        // 装備箇所の一覧を取得。
        $mounts = Service::create('Mount_Master')->getMounts($chara['race']);
        $this->setAttribute('mounts', $mounts);

        // 表情の一覧をセット。
        $this->setAttribute('faceOptions', Character_InfoService::$AVATAR_FACES);

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 自動装備を処理する。
     */
    public function processAutoEquipment($chara) {

        $uitemSvc = new User_ItemService();
        $equipSvc = new Character_EquipmentService();

        // とりあえず今の装備を外す
        $this->processAllRelease($chara);

        // 装備箇所の一覧を取得。
        $mounts = Service::create('Mount_Master')->getMounts($chara['race']);
        $this->setAttribute('mounts', $mounts);

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
            if($decision)
                $equipSvc->changeEquipment($chara['character_id'], $mount['mount_id'], $decision['user_item_id']);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 装備全解除を処理する。
     */
    public function processAllRelease($chara) {

        // 装備箇所の一覧を取得。
        $mounts = Service::create('Mount_Master')->getMounts($chara['race']);
        $this->setAttribute('mounts', $mounts);

        // 各箇所の装備を外していく。
        $equipSvc = new Character_EquipmentService();
        foreach($mounts as $mount)
            $equipSvc->changeEquipment($chara['character_id'], $mount['mount_id'], null);
    }
}
