<?php

/**
 * プレイ中に消耗する値(行動ptや装備の耐久値)を回復＆購入するためのページを表示する。
 *
 * GETパラメータ)
 *      type        以下のいずれか。
 *                      ap      行動pt不足
 *                      mp      対戦pt不足
 *                      repaire 装備品の修理
 *      targetId    不足の対象値。
 *                  type=ap,mp なら省略可能。
 *                  type=repaire ならユーザアイテムID
 *      backto      「戻る」リンクの戻り先。ViewUtil::serializeBackto() の戻り値で指定。
 *      useto       回復アイテムを使用した後に飛ばすページ。ViewUtil::serializeBackto() の戻り値で指定。
 *                  省略時はbacktoが使用される。
 */
class SuggestAction extends SmfBaseAction {

    protected function doExecute($params) {

        $array = array();

        //APIリスト
        $array = array(
            'apiOnShop' => Common::genContainerUrl('Api', 'ShopApi', array(), true), //ショップリストのURL
            'apiOnHomeSummary' => Common::genContainerUrl('Api', 'HomeSummaryApi', array(), true), //サマリーAPIのURL
            'suggest_nexturl' => Common::genContainerUrl('Api', 'Suggest', $_GET, true), //サマリーAPIのURL
        );

        // フォームが送信されている場合、それを処理する。エラー以外では制御は戻ってこない。
        if($_POST) {
            if($_POST['mode'] == 'use'){
                $array = $this->processUse();
                return $array;
            }else{
                $array = $this->processBuy();
                return $array;
            }
        }

        // 回復アイテムを持っているかどうか取得してみる。
        $uitem = $this->getRecoverItem();

        // 持っているならビューにセット。
        if($uitem) {
            $array['uitem'] = $uitem;

            $array['item_id'] = $uitem["item_id"];
            $array['price'] = 0;

        // 持っていないなら、回復アイテムの情報と価格をビューにセットする。
        }else {

            $recovItem = Service::create('Shop_Content')->needRecord(
                Shop_ContentService::COIN_SHOP, $this->getRecoverId()
            );

            $array['item'] = $recovItem['item'];
            $array['price'] = $recovItem['price'];

            $array['item_id'] = $recovItem['item']["item_id"];

        }

        // バトルランキングに関する情報を取得。
        //$rankSvc = new Ranking_LogService();
        //$this->setAttribute('term', $rankSvc->getRankingTerm(Ranking_LogService::GRADEPT_WEEKLY));
        //$this->setAttribute('prev', $rankSvc->getRankingTerm(Ranking_LogService::GRADEPT_WEEKLY, 'prev'));
        //$this->setAttribute('cycle', $rankSvc->getRankingCycle());

        //$this->setAttribute('variable_list', $this->variable_list);


        // coinアイテムのuser_itemレコードを取得。
        $coin_info = Service::create('User_Item')->getRecordByItemId($this->user_id, COIN_ITEM_ID);

        $array["coin"] = $coin_info['num'];

        $array["result"] = "ok";

        return $array;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 回復アイテムのIDを返す。
     */
    private function getRecoverId() {

        switch($_GET['type']) {

            // 行動pt不足の場合。
            case 'ap':
                return Item_MasterService::AP_RECOVER_ID;

            // 対戦pt不足の場合。
            case 'mp':
                return Item_MasterService::MP_RECOVER_ID;

            // 装備品修理の場合。
            case 'repaire':
                return Item_MasterService::REPAIRE_ID;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 回復アイテムを持っているかどうか調べて、持っているならそのレコードを返す。
     */
    private function getRecoverItem() {

        // 提案種別と回復アイテムの種別の対応表。
        static $TYPES = array(
            'ap' =>      Item_MasterService::RECV_AP,
            'mp' =>      Item_MasterService::RECV_MP,
            'repaire' => Item_MasterService::REPAIRE,
        );

        // 回復アイテムの種別を取得。
        $itemType = $TYPES[ $_GET['type'] ];
        if( !$itemType )
            throw new MojaviException('不明な提案タイプです。');

        // その種別の所持レコードを探索してリターン。
        return Service::create('User_Item')->getRecordByType($this->user_id, $itemType);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 「使う」フォームの送信を処理する。
     */
    private function processUse() {
        $array = array();

        $uitemSvc = new User_ItemService();

        // 使うアイテムの所持レコードを取得。
        $uitem = $this->getRecoverItem();
        if(!$uitem){
            $array["result"] = "error";
            $array["err_code"] = "no_item";
            return $array;
        }

        // 対象IDを取得。
        $targetId = $this->getTargetId();

        // 本当に使用できるかチェックする。
        $error = $uitemSvc->checkUsing($this->user_id, $uitem['user_item_id'], $targetId);

        // この画面に来てるのに、正当な理由(満タンだから使ってもしょうがない等)で使えないとは考えにくい。
        // エラー画面は出さずに戻り先にとばす。
        if($error){
            $array["result"] = "error";
            $array["err_code"] = $error;
            return $array;
        }

        // 使用。
        $uitemSvc->useItem($uitem['user_item_id'], $targetId);

        // usetoで示されているページに戻す。
        //$serial = empty($_GET['useto']) ? $_GET['backto'] : $_GET['useto'];
        //Common::redirect( ViewUtil::unserializeBackto($serial) );

        $array["result"] = "ok";

        return $array;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 「買う」フォームの送信を処理する。
     */
    private function processBuy() {
        $array = array();

        // 回復アイテムの情報を取得。
        $recovItem = Service::create('Shop_Content')->needRecord(
            Shop_ContentService::COIN_SHOP, $this->getRecoverId()
        );

        // coinアイテムのuser_itemレコードを取得。
        $coin_info = Service::create('User_Item')->getRecordByItemId($this->user_id, COIN_ITEM_ID);

        //足りない場合はエラー
        if($coin_info['num'] < $recovItem['price']){
            //Common::redirect('User', 'Suggest', $_GET);
            $array["result"] = "error";
            $array["err_code"] = "short_coin";
            return $array;
        }

        //ログ作成
        Service::create('Coin_Log')->insertRecord(array(
            'user_id' => $this->user_id,
            'item_type' => "IT",
            'item_id' => $recovItem['item']['item_id'],
            'amount' => $_POST['num'],
            'unit_price' => $recovItem['price'],
        ));

        // コイン減算
        Service::create('User_Item')->consumeItem($coin_info['user_item_id'], $recovItem['price'] * $_POST['num']);

        // アイテム付与
        for($i = 0; $i < $_POST['num']; $i++)
            $uitemId = Service::create('User_Item')->gainItem($this->user_id, $recovItem['item']['item_id']);

        //Common::redirect('User', 'Suggest', $_GET);
        $array["result"] = "ok";

        return $array;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 回復対象のIDを返す。
     */
    private function getTargetId() {

        switch($_GET['type']) {

            // 行動pt不足、対戦pt不足なら対象は自分。
            case 'ap':
            case 'mp':
                return $this->user_id;

            // 装備品修理の場合、対象は GET で指定されている。
            case 'repaire':
                return $_GET['targetId'];
        }
    }
}
