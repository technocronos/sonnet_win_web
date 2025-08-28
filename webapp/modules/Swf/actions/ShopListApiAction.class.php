<?php

/**
 * ---------------------------------------------------------------------------------
 * ショップリストを返す
 * ---------------------------------------------------------------------------------
 */
class ShopListApiAction extends ApiBaseAction {

    protected function doExecute($params) {
        // 指定されていないGETパラメータにデフォルト値をセット。
        if(empty($_GET['cat'])) $_GET['cat'] = 'ITM';
        if(empty($_GET['currency'])) $_GET['currency'] = 'gold';

        $array = [];


        if($_GET['currency'] == 'coin')
            $shop = Place_MasterService::$SPECIAL_SHOP;
        else
            $shop = Place_MasterService::$NORMAL_SHOP;

        return $this->MakeShopList($shop, $_GET['cat']);

/*
        //課金ショップ
        $shop = Place_MasterService::$SPECIAL_SHOP;

        $array["shop"]["2"]["1"] = array_merge($this->MakeShopList($shop, 'ITM'));
        $array["shop"]["2"]["2"] = array_merge($this->MakeShopList($shop, 'EQP'));

        //マグナショップ
        $shop = Place_MasterService::$NORMAL_SHOP;

        $array["shop"]["1"]["1"] = array_merge($this->MakeShopList($shop, 'ITM'));
        $array["shop"]["1"]["2"] = array_merge($this->MakeShopList($shop, 'EQP'));

        $array['result'] = 'ok';


        return $array;
*/
    }

//-----------------------------------------------------------------------------------------------------
/**
 * ショップ一覧の情報をswfに渡す
 * shop_1_2_0_item_id
 * shop_1_2_1_item_id
 *  ...
 * 
 * 最初の数字は　マグナ = 1 コイン = 2
 * 次の数字は　アイテム = 1 装備   = 2
 * 最後の数字はリスト連番
 *
 */
    private function MakeShopList($shop, $category) {
        //ショップ情報をセットする
        $shopSvc = new Shop_ContentService();

        $arr = [];
        $page_const = 10000;

        // 商品の一覧をビュー変数にセット。
        $shelf = array(
            'shop_id' => $shop['shop_id'],
            'category' => $category,
            'user_id' => $this->user_id
        );

        $list = $shopSvc->getShelf($shelf, $page_const);

        foreach($list["resultset"] as $i => $resultset){
            foreach($resultset as $key => $value){
                if($key != "item"){
                    $arr[$i][$key] = $value;
                }else{
                    foreach($value as $keyItm => $valueItm){
                        $arr[$i][$keyItm] = $valueItm;
                    }
                }
            }

            $str = AppUtil::itemEffectStr($resultset["item"]);
            $arr[$i]["effect"] = $str;
        }

        $arr["Num"] = $list["totalRows"];

        //次にリリースされるアイテムを得る
        $arr["next"] = $shopSvc->nextReleaseItem($shelf);

        return $arr;
    }
}
