<?php

class GachaDetailAction extends UserBaseAction {

    public function execute() {

        $gachaSvc = new Gacha_MasterService();

        // 指定されているガチャを本当に購入できるのかチェックしておく。
        if( !$gachaSvc->canPurchase($this->user_id, $_GET['gachaId']) )
            Common::redirect('User', 'GachaList');

        // フリーチケット使用で、ユーザの確認が取れているならばそれ用の処理へ。
        if($_POST){
            if($_POST["gold_ok"])
                $this->useGold();
            else
                $this->useFreeticket();
        }

        // 課金orﾌﾘｰﾁｹｯﾄでまわす を押しているなら、それ用の処理へ。
        if( !empty($_GET['go']) ) {
            $this->processConfirm();
            return 'Confirm';
        }

        // 以下、ガチャの内容を表示する場合。

        // ガチャの詳細と中身のリストを取得。
        $gacha = $gachaSvc->needRecord($_GET['gachaId']);
        $this->setAttribute('gacha', $gacha);

		$list = $gachaSvc->getContents($_GET['gachaId']);
		if($gacha['gacha_id'] != 9997 && $gacha['gacha_id'] != 9998){
			foreach($list as $row){
				if($set_id != $row['item']['set']['set_id'])
					$list_set[]['item']['set'] = $row['item']['set'];

                $set_id = $row['item']['set']['set_id'];
			}
			$list = $list_set;
		}

        $this->setAttribute('list', $list);

        // フリーチケットの数を取得。
        if($gacha['freeticket_item_id']) {
            $userItemSvc = new User_ItemService();
            $this->setAttribute('freeticketCount',
                $userItemSvc->getHoldCount($this->user_id, $gacha['freeticket_item_id'])
            );
        }

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 課金orﾌﾘｰﾁｹｯﾄでまわす を押しているときの処理
     * 課金の場合は決済画面にリダイレクトするので制御は戻らない。
     */
    private function processConfirm() {

        // ガチャのデータを取得。
        $gacha = Service::create('Gacha_Master')->needRecord($_GET['gachaId']);

        // 課金でまわす場合
        if($_GET['go'] == 'charge') {

            // 決済準備。戻り先は、今のURLから"go"を取り除いたもの。
            $redirectUrl = AppUtil::readyPayment(array(
                'item_type' => 'GC',
                'item_id' => $gacha['gacha_id'],
                'item_name' => $gacha['gacha_name'],
                'unit_price' => $gacha['price'],
                'description' => $gacha['flavor_text'],
                'backto' => array('go'=>null),
            ));

            // ユーザを決済画面へ飛ばす。
            Controller::getInstance()->redirect($redirectUrl);

        //マグナでまわす場合
        }else if($_GET['go'] == 'gold'){
            
            $this->setAttribute('gold', true);
            $this->setAttribute('gacha', $gacha);

            // リターンして、確認画面に行く。

        // フリーチケットでまわす場合。
        }else{

            $this->setAttribute('gacha', $gacha);

            // リターンして、確認画面に行く。
        }
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * フリーチケット使用でガチャをまわす処理。
     */
    private function useGold() {

        $gachaSvc = new Gacha_MasterService();
        $userItemSvc = new User_ItemService();

        // ガチャの情報を取得。
        $gacha = $gachaSvc->needRecord($_GET['gachaId']);

        //足りない場合はエラー
        if($this->userInfo['gold'] < $gacha['price'])
            Common::redirect(array('_self'=>true, 'go'=>null));

        // ガチャからアイテムを一つ引く。
        $itemIds = $gachaSvc->drawItem($_GET['gachaId']);
        $itemId = $itemIds[0]["item_id"];

        // 引いたアイテムをユーザに付与。
        $uitemId = $userItemSvc->gainItem($this->user_id, $itemId);

        // ゲーム内通貨減算
        Service::create('User_Info')->plusValue($this->user_id, array(
            'gold' => -1 * $gacha['price'],
        ));

        // 結果画面へ。
        $backto = ViewUtil::serializeBackto(array('go'=>null));
        Common::redirect('User', 'ItemGet', array('uitemId'=>$uitemId, 'backto'=>$backto));

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * フリーチケット使用でガチャをまわす処理。
     */
    private function useFreeticket() {

        $gachaSvc = new Gacha_MasterService();
        $userItemSvc = new User_ItemService();

        // ガチャの情報を取得。
        $gacha = $gachaSvc->needRecord($_GET['gachaId']);

		//枚数チェック
		$freeticketCount = $userItemSvc->getHoldCount($this->user_id, $gacha['freeticket_item_id']);
        // 規定数持ってない場合はとりあえず、ガチャ詳細に飛ばす。
        if($freeticketCount < $gacha['freeticket_count'])
            Common::redirect(array('_self'=>true, 'go'=>null));

        // フリーチケットアイテムのuser_itemレコードを取得。
        $ticketItem = $userItemSvc->getRecordByItemId($this->user_id, $gacha['freeticket_item_id']);
        // 持ってない場合はとりあえず、ガチャ詳細に飛ばす。
        if(!$ticketItem)
            Common::redirect(array('_self'=>true, 'go'=>null));

        // ガチャからアイテムを一つ引く。
        $itemIds = $gachaSvc->drawItem($_GET['gachaId']);
        $itemId = $itemIds[0]["item_id"];

        // 引いたアイテムをユーザに付与。
        $uitemId = $userItemSvc->gainItem($this->user_id, $itemId);

        // フリーチケットを消費。
        $userItemSvc->consumeItem($ticketItem['user_item_id'], $gacha['freeticket_count']);

        // 結果画面へ。
        $backto = ViewUtil::serializeBackto(array('go'=>null));
        Common::redirect('User', 'ItemGet', array('uitemId'=>$uitemId, 'backto'=>$backto));
    }
}
