<?php

class EquipChangeAction extends UserBaseAction {

    public function execute() {
        $charaSvc = new Character_InfoService();

        // 省略されているGETパラメータを補う。
        if(empty($_GET['charaId'])) $_GET['charaId'] = $charaSvc->needAvatarId($this->user_id);
        if(empty($_GET['page']))    $_GET['page'] = 0;

        // 指定されているキャラをチェック。
        $chara = $charaSvc->needRecord($_GET['charaId']);
        if($chara['user_id'] != $this->user_id)
            throw new MojaviException('他人の装備を変更しようとした');

        // フィールドクエスト中の場合は装備変更不可
        if($chara['sally_sphere'])
            Common::redirect(array('action'=>'Equip', 'charaId'=>$_GET['charaId'], 'result'=>'sphere', 'backto'=>$_GET['backto']));

        // 変更する装備が選択されている場合の処理。
        if( !empty($_GET['change']) )
            $this->processChange();

        // 合成が選択されている場合の処理。
        if( !empty($_GET['gousei']) )
            return $this->processGousei();

        // 装備箇所の情報を取得。
        $mount = Service::create('Mount_Master')->needRecord($chara['race'], $_GET['mountId']);
        $this->setAttribute('mount', $mount);

        // 装備可能なアイテム一覧を取得。
        $condition = array('user_id'=>$this->user_id, 'race'=>$chara['race'], 'mount_id'=>$_GET['mountId']);
		$numOnPage = (Common::getCarrier() != "android" && Common::getCarrier() != "iphone") ? 6 : 30;

        $list = Service::create('User_Item')->getHoldList($condition, $numOnPage, $_GET['page']);

		foreach($list['resultset'] as &$row){
	        $row['set'] = Service::create('Set_Master')->getRecord($row['set_id']);
		}

        $this->setAttribute('list', $list);

        // アイテム名をクリックしたときの遷移先をセット。
        $this->setAttribute('nameCallback', array($this, 'makeItemName'));

        // 合成をクリックしたときの遷移先をセット。
        $this->setAttribute('nameCallbackGousei', array($this, 'makeItemNameGousei'));

		if($_GET['result'] == "gousei"){

	        // キャラ情報を取得
	        $charas = Service::create('Character_Info')->needExRecord($_GET['charaId']);
			//現在の装備(ベース)
			$currentExuip = $charas["equip"][$_GET['mountId']];
	        $this->setAttribute('currentExuip', $currentExuip);

			//合成した装備
	        $sourceItem = Service::create('Item_Master')->needRecord($_GET['src']);
	        $this->setAttribute('sourceItem', $sourceItem);

	        $this->setAttribute('result', $_GET['result']);
	        $this->setAttribute('afterexp', $_GET['afterexp']);
	        $this->setAttribute('afterlv', $_GET['afterlv']);
	        $this->setAttribute('beforeexp', $_GET['beforeexp']);
	        $this->setAttribute('beforelv', $_GET['beforelv']);

		    $maxLv = Service::create('Item_Level_Master')->getMaxLevel($currentExuip['item_id']);

			//現在装備してるのがMAX
		    if($maxLv <= $currentExuip['level'])
				$this->setAttribute('max', true);

		}

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 装備変更のリクエストを処理する。
     */
    private function processChange() {

        // GETリクエストだけで装備変更が行えてしまうので、簡単ながらCSRFに備える。
        if( !Common::validateSign() )
            Common::redirect(array('_self'=>true, 'change'=>''));

        // 装備変更。
        $equipSvc = new Character_EquipmentService();
        $change = ($_GET['change'] == 'NONE') ? null : $_GET['change'];
        $equipSvc->changeEquipment($_GET['charaId'], $_GET['mountId'], $change);

        // 結果画面へリダイレクト。
        Common::redirect(array('action'=>'Equip', 'charaId'=>$_GET['charaId'], 'result'=>'change', 'backto'=>$_GET['backto']));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 装備合成のリクエストを処理する。
     */
    private function processGousei() {

		$request = $this->getContext()->getRequest();

		//合成する装備情報
        $uitem = Service::create('User_Item')->getRecord($_GET['gousei']);

		//存在チェック
		if(!$uitem)
			$this->setAttribute('error', "noitem");

        // キャラ情報を取得
        $chara = Service::create('Character_Info')->needExRecord($_GET['charaId']);

		//現在の装備(ベース)
		$currentExuip = $chara["equip"][$_GET['mountId']];

		//現在の装備は無条件に合成不可
		if($uitem['user_item_id'] == $currentExuip['user_item_id'])
			$this->setAttribute('error', "equipping");

	    $maxLv = Service::create('Item_Level_Master')->getMaxLevel($currentExuip['item_id']);

		//現在装備してるのがすでにMAXならリンクしない
	    if($maxLv <= $currentExuip['level'])
			$this->setAttribute('error', "maxlevel");

		$price = $this->getPrice($currentExuip, $uitem);
		$add_exp = $this->getExp($currentExuip, $uitem);

		//マグナが足りない
	    if($this->userInfo["gold"] < $price)
			$this->setAttribute('error', "nomoney");

		if($_POST && !$request->getAttribute('error')){

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

	        // 結果画面へリダイレクト。
	        Common::redirect(array('action'=>'EquipChange', 'charaId'=>$_GET['charaId'], 'mountId'=>$_GET['mountId'],'result'=>'gousei', 'afterexp'=>$after["item_exp"],'afterlv'=>$after["level"], 'src'=>$uitem['item_id'], 'beforelv'=>$currentExuip['level'], 'beforeexp'=>$currentExuip['item_exp'], 'backto'=>$_GET['backto']));
			
		}

		if($_GET["confirm"]){
			$this->setAttribute('uitem', $uitem);
			$this->setAttribute('chara', $chara);
			
			$this->setAttribute('needgold', $price);

			return "Confirm";
		}
    }

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
		$tmp = $baseRearLevel - ($baseRearLevel - $souceRearLevel + $material_uitem['level']);

		if($tmp <= 0)
			$tmp = 1;

		$price = ( ($tmp / 4) * 100 );

//print_r("price = " . $price);
//print_r("<br>");

		return $price;
	}

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

    //-----------------------------------------------------------------------------------------------------
    /**
     * ユーザアイテムレコードを引数にとって、アイテム名を出力するHTMLを返す。
     * テンプレートから呼ばれるコールバック関数。
     */
    public function makeItemName($uitem) {

        // キャラ情報を取得
        $chara = Service::create('Character_Info')->needRecord($_GET['charaId']);

        // 装備についてのデータを取得。
        $equip = Service::create('Equippable_Master')->needRecord($chara['race'], $_GET['mountId'], $uitem['item_id']);

        // 装備可能なレベルかどうかを取得。
        //$equippable = ($equip['equippable_level'] <= $chara['level']);

        //装備可能レベルは廃止
        $equippable = true;

        // 現在装備していない、かつ、装備可能ならリンクを張る。
        if($uitem['free_count'] > 0  &&  $equippable) {
            $href = Common::genContainerURL(array(
                '_self'=>true, '_sign'=>true, 'change'=>$uitem['user_item_id'],
            ));
        }

        // アイテム名のHTMLを作成。
        if($href)
            //スマホ版は装備するに文言変更プラス、クラス指定。
            if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone")
              $html = ViewUtil::tag('a', array('href'=>$href, 'class'=>'buttonlike next'), '装備する');
			else
              $html = ViewUtil::tag('a', array('href'=>$href), $uitem['item_name']);
        else
            //スマホ版は文言表示しない。
            if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone")
              $html = "";
            else
              $html = ViewUtil::html($uitem['item_name']);

        // 装備可能なレベルでないなら、アイテム名に続けて警告文を出す。
        //if(!$equippable)
            //$html .= sprintf('<br /><span style="color:#696969">Lv<span style="color:#CC3300">%d</span>から装備可能</span>', $equip['equippable_level']);

        // リターン。
        return $html;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * ユーザアイテムレコードを引数にとって、アイテム名を出力するHTMLを返す。
     * テンプレートから呼ばれるコールバック関数。合成用に追加。
     */
    public function makeItemNameGousei($uitem) {

        // キャラ情報を取得
        $chara = Service::create('Character_Info')->needExRecord($_GET['charaId']);

		//現在の装備
		$currentExuip = $chara["equip"][$_GET['mountId']];

		//現在の装備は無条件に文字列を返さない
		if($uitem['user_item_id'] == $currentExuip['user_item_id'])
			return;

	    $maxLv = Service::create('Item_Level_Master')->getMaxLevel($currentExuip['item_id']);

        //装備してるものがない
		if(!$currentExuip)
			return "<span style='color:#696969'>装備中のものが無いため<br/>現在合成できません</span>";

		//現在装備してるのがすでにMAXならリンクしない
	    if($maxLv <= $currentExuip['level'])
			return "<span style='color:#696969'>装備対象がレベルMAXのため<br/>合成できません</span>";


        // 現在装備していないならリンクを張る。
        if($uitem['free_count'] > 0) {
            $href = Common::genContainerURL(array(
                '_self'=>true, '_sign'=>true, 'gousei'=>$uitem['user_item_id'], 'confirm'=>true, '_backto'=>true,
            ));
        }

        // アイテム名のHTMLを作成。
        if($href)
            $html = ViewUtil::tag('a', array('href'=>$href,'class'=>'buttonlike next'), "合成する");		

        // リターン。
        return $html;
    }
}
