<?php

class ShopAction extends UserBaseAction {

    public function execute() {

        $shopSvc = new Shop_ContentService();

        // 指定されていないGETパラメータにデフォルト値をセット。
        if(empty($_GET['cat'])) $_GET['cat'] = 'ITM';
        if(empty($_GET['currency'])) $_GET['currency'] = 'gold';
        if(empty($_GET['page'])) $_GET['page'] = 0;

        // ショップ情報を取得。課金で買う場合は課金ショップ、それ以外は地点マスタから取る。
        // …だったけど、通常ショップは場所に関係なく統一された。
        if($_GET['currency'] == 'coin')
            $shop = Place_MasterService::$SPECIAL_SHOP;
        else
            // $shop = Service::create('Place_Master')->getShop($this->userInfo['place_id'], $this->user_id);
            $shop = Place_MasterService::$NORMAL_SHOP;

        // ショップ情報をビュー変数にセット。
        $this->setAttribute('shop', $shop);

        // ショップがないならばそれ用のビューへ。
        if( is_null($shop['shop_id']) )
            return 'Nothing';

        // 購入確定ならばそれ用の処理へ。
        // 所持金不足以外では制御は戻ってこない。
        if( !empty($_POST['num']) )
            $this->processPurchase($shop['shop_id']);

        // 確認画面に入ろうとしている場合。
        if( !empty($_GET['buy'])  &&  empty($_POST['num']) ) {

            // ちゃんと買えるのかチェック。
            $buyItem = $this->canPurchase($shop['shop_id'], $_GET['buy']);

            // 買えるのなら...
            if($buyItem) {

                // 課金で装備品を買おうとしている場合は、個数は1固定だし、プラットフォームの確認画面が
                // 入るので、自前の確認画面をスキップする。
                if($shop['shop_id'] == Shop_ContentService::COIN_SHOP  &&  Item_MasterService::isDurable($buyItem['item']['category'])) {
                    $_POST['num'] = 1;
                    $this->processPurchase($shop['shop_id']);

                // それ以外は確認画面へ。
                }else {
                    $this->setAttribute('sale', $buyItem);
                    $this->setAttribute('currencyName', ($_GET['currency'] == 'coin') ? PLATFORM_CURRENCY_NAME : GOLD_NAME);
                    return 'Confirm';
                }
            }
        }

        // 以下、購入画面用の処理。

        // 商品の一覧をビュー変数にセット。
        $shelf = array(
            'shop_id' => $shop['shop_id'],
            'category' => $_GET['cat'],
            'user_id' => $this->user_id
        );

        //スマホはなるぺくページングしない。100件でもいいが負荷を見て・・。
        if(Common::getCarrier() == "android" || Common::getCarrier() == "iphone")
          $page_const = 30;
        else
          $page_const = 6;

        $list = $shopSvc->getShelf($shelf, $page_const, $_GET['page']);
        $this->setAttribute('list', $list);


        // 次にリリースされるアイテムを取得。
        $this->setAttribute('next', $shopSvc->nextReleaseItem($shelf));

        // アイテム選択時のURLをビュー変数にセット。
        $this->setAttribute('itemLink', Common::genContainerURL(array(
            '_self'=>true, 'buy'=>'--id--'
        )));

        return View::SUCCESS;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ちゃんと買えるのかどうかをチェックする。
     * 買える場合は Shop_ContentService から取得したレコードを返す。買えない場合はNULL。
     * 買えない場合のエラー処理も行っている。
     */
    public function canPurchase($shopId, $itemId, $num = 1) {

        // 個数の妥当性チェック。
        if($num <= 0)
            throw new MojaviException('購入個数がおかしい');

        // レコード取得。needRecord を使って取得できない、つまり売っていない場合は例外を投げる。
        $record = Service::create('Shop_Content')->needRecord($shopId, $itemId);

        // ユーザのアバターキャラを取得。
        $chara = Service::create('Character_Info')->needAvatar($this->user_id);

        // 開放レベルに到達していないのはエラー。
        if($chara['level'] < $record['unlock_level'])
            throw new MojaviException('開放レベルに到達していないアイテムを買おうとした');

        // ゲーム内通貨で買おうとしている場合に、お金が足りない場合はビューにエラーを設定。
        if($shopId != Shop_ContentService::COIN_SHOP  &&  $this->userInfo['gold'] < $record['price'] * $num) {
            $this->setAttribute('error', GOLD_NAME.'が足りないのだ…もっと地に足つけるのだ…');
            return null;
        }

        // ここまでくればOK
        return $record;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * アイテムの購入を処理する。
     */
    public function processPurchase($shopId) {

        // ちゃんと買えるのかチェック。買えないなら処理しない。
        $sale = $this->canPurchase($shopId, $_GET['buy'], $_POST['num']);
        if(!$sale)
            return;

        // 課金で購入の場合
        if($shopId == Shop_ContentService::COIN_SHOP) {

            // プラットフォームの決済画面のURLを取得。
            // 戻り先は今のURLから"buy"を取り除いたもの。
            $redirectUrl = AppUtil::readyPayment(array(
                'item_type' => 'IT',
                'item_id' => $sale['item_id'],
                'item_name' => $sale['item']['item_name'],
                'unit_price' => $sale['price'],
                'description' => $sale['item']['flavor_text'],
                'amount' => $_POST['num'],
                'backto' => array('buy'=>null),
            ));

            // ユーザを決済画面へ飛ばす。
            Controller::getInstance()->redirect($redirectUrl);

        // ゲーム内通貨の場合
        }else {

            // ショップチュートリアル中なら、ココでステップアップ
            Service::create('User_Info')->tutorialStepUp($this->user_id, User_InfoService::TUTORIAL_SHOPPING);

            // ゲーム内通貨減算
            Service::create('User_Info')->plusValue($this->user_id, array(
                'gold' => -1 * $sale['price'] * $_POST['num'],
            ));

            // アイテム付与
            $svc = new User_ItemService();
            $uitemId = $svc->gainItem($this->user_id, $sale['item_id'], $_POST['num']);

            // 結果画面へリダイレクト。
            $backto = ViewUtil::serializeBackto(array('buy'=>null));
            Common::redirect('User', 'ItemGet', array('uitemId'=>$uitemId, 'backto'=>$backto));
        }
    }
}
