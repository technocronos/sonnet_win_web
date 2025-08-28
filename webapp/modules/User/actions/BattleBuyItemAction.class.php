<?php

class BattleBuyItemAction extends UserBaseAction {

  /*
   復旧処理
   サーバ側
   　１、バトルリロードでエラーにしないようにバトルログのstatusをCREATEDに戻す
   　２、FieldBattleUtil::battle_createでcontinue_count>0の場合は新規レコードを作成しないで続きのレコードを渡す。こちらも1の処理は必要。
       GETされる情報
       Array
       (
           [battleId] => 5657
           [code] => Kcscuv2Iqu0zxvOGyJKjGXlHw4tTNCei
           [item_id] => 1911
           [item_name] => リレイザー
           [module] => User
           [action] => BattleBuyItem
           [t] => 1446607500
           [opensocial_app_id] => 100447
           [opensocial_viewer_id] => 6358860
           [opensocial_owner_id] => 6358860
       )
       こっちはPOST
       array(7) {
         ["result"]=>
         &string(0) ""
         ["code"]=>
         &string(32) "Kcscuv2Iqu0zxvOGyJKjGXlHw4tTNCei"
         ["time"]=>
         &string(1) "4"
         ["hpP"]=>
         &string(1) "0"
         ["hpE"]=>
         &string(2) "25"
         ["starP"]=>
         &string(1) "8"
         ["starE"]=>
         &string(1) "6"
       }
   */
    public function execute() {
        // 指定されているバトル情報をロード。見つからないならエラーリターン。
        $battle = Service::create('Battle_Log')->getRecord($_GET['battleId']);
        if(!$battle) {
            $this->log("BattleBuyItemAction: 指定されているバトル情報が見つからない\n_GET = " . print_r($_GET, true));
            Common::varDump('error');;
        }

        // バリデーションコードをチェック。
        if($battle['validation_code'] != $_GET['code']) {
            $this->log("BattleBuyItemAction: バリデーションコードが不正\n_GET = " . print_r($_GET, true));
            Common::varDump('error');;
        }

        // 他人のバトルの場合はエラー。
        if($this->user_id != $battle['player_id']) {
            $this->log("BattleBuyItemAction: 他人のバトルで購入をしようとした\n_GET = " . print_r($_GET, true));
            Common::varDump('error');;
        }

        //POSTとGETをまとめる
        $param = array_merge($_GET, $_POST);

        // バトル種別に応じたバトルユーティリティを取得。
        $battleUtil = BattleCommon::factory($battle);

        // バトル種別に応じた開始処理。
        $errorCode = $battleUtil->discontinuRecvBattle($battle, $param);

        $this->processPurchase($param);

        return View::SUCCESS;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * ちゃんと買えるのかどうかをチェックする。
     * 買える場合は Shop_ContentService から取得したレコードを返す。買えない場合はNULL。
     * 買えない場合のエラー処理も行っている。
     */
    public function canPurchase($param) {

        // レコード取得。needRecord を使って取得できない、つまり売っていない場合は例外を投げる。
        $record = Service::create('Shop_Content')->needRecord(Shop_ContentService::COIN_SHOP, $param["item_id"]);

        // ユーザのアバターキャラを取得。
        $chara = Service::create('Character_Info')->needAvatar($this->user_id);

        // 開放レベルに到達していないのはエラー。
        if($chara['level'] < $record['unlock_level'])
            throw new MojaviException('開放レベルに到達していないアイテムを買おうとした');

        // ここまでくればOK
        return $record;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * アイテムの購入を処理する。
     */
    public function processPurchase($param) {

        // ちゃんと買えるのかチェック。買えないなら処理しない。
        $sale = $this->canPurchase($param);
        if(!$sale)
            return;

        if(PLATFORM_TYPE != "nati"){
            // プラットフォームの決済画面のURLを取得。
            // 戻り先は今のURLから"buy"を取り除いたもの。
            $redirectUrl = AppUtil::readyPayment(array(
                'item_type' => 'IT',
                'item_id' => $sale['item_id'],
                'item_name' => $sale['item']['item_name'],
                'unit_price' => $sale['price'],
                'description' => $sale['item']['flavor_text'],
                'amount' => 1,
                'backto' => array('module' => 'Swf', 'action' => 'Battle'),
                'backto_sp' => Common::genURL('Swf', 'Battle', $param),
                'finish_param' => $param,
            ));

            // ユーザを決済画面へ飛ばす。
            Controller::getInstance()->redirect($redirectUrl);
        }else{
            $api_list = array();
            //APIリスト
            $api_list = array(
                'apiOnShop' => Common::genContainerUrl('Swf', 'ShopApi', array(), true), //ショップリストのURL
                'apiOnHomeSummary' => Common::genContainerUrl('Swf', 'HomeSummaryApi', array(), true), //サマリーAPIのURL
            );
            $this->setAttribute('api_list', $api_list);

            $recovItem = Service::create('Shop_Content')->needRecord(
                Shop_ContentService::COIN_SHOP, $param["item_id"]
            );

            $this->setAttribute('item', $recovItem['item']);
            $this->setAttribute('price', $recovItem['price']);

            $this->variable_list['item_id'] = $recovItem['item']["item_id"];
            $this->variable_list['price'] = $recovItem['price'];

            $this->setAttribute('bitem_backtoUrl', Common::genURL('Swf', 'Battle', $param));
            $this->setAttribute('bitem_finishUrl', Common::genURL($param['finish']['module'], $param['finish']['action'], array('dataId'=>null), true));
        }
    }
}
