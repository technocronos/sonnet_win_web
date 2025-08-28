<?php

class BattleBuyItemAction extends SmfBaseAction {

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
    protected function doExecute($params) {
        // 指定されているバトル情報をロード。見つからないならエラーリターン。
        $battle = Service::create('Battle_Log')->getRecord($_GET['battleId']);
        if(!$battle) {
            $this->log("BattleBuyItemAction: 指定されているバトル情報が見つからない\n_GET = " . print_r($_GET, true));
            return array("result" => "error", "err_code" => "not_found_battle");
        }

        // バリデーションコードをチェック。
        if($battle['validation_code'] != $_GET['code']) {
            $this->log("BattleBuyItemAction: バリデーションコードが不正\n_GET = " . print_r($_GET, true));
            return array("result" => "error", "err_code" => "invalied_code");
        }

        // 他人のバトルの場合はエラー。
        if($this->user_id != $battle['player_id']) {
            $this->log("BattleBuyItemAction: 他人のバトルで購入をしようとした\n_GET = " . print_r($_GET, true));
            return array("result" => "error", "err_code" => "not_own_battle");
        }

        //POSTとGETをまとめる
        $param = array_merge($_GET, $_POST);

        // バトル種別に応じたバトルユーティリティを取得。
        //$battleUtil = BattleCommon::factory($battle);

        // バトル種別に応じた開始処理。
        //$errorCode = $battleUtil->discontinuRecvBattle($battle, $param);

        $array = $this->processPurchase($param);


        return $array;
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
            return array("result" => "error", "err_code" => "cannot_purchase");

        $recovItem = Service::create('Shop_Content')->needRecord(
            Shop_ContentService::COIN_SHOP, $param["item_id"]
        );

        $array = array();

        $array['item'] = $recovItem['item'];
        $array['price'] = $recovItem['price'];

        $array['item_id'] = $recovItem['item']["item_id"];

        // coinアイテムのuser_itemレコードを取得。
        $coin_info = Service::create('User_Item')->getRecordByItemId($this->user_id, COIN_ITEM_ID);

        $array["coin"] = $coin_info['num'];

        $array["result"] = "ok";

        return $array;
    }
}
