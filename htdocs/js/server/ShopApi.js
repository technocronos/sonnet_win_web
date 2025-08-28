//---------------------------------------------------------------------------------------------------------
/**
 * ショップを扱うAPI
 */
var ShopApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * ショップリスト用API
 * @param なし
 */
ShopApi.list = function(cat, currency, callback){
    var get = {"cat":cat, "currency":currency};
    var api = new Api(apiOnShopList, get, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ショップ購入用API
 * @param buy      アイテムID
 * @param cat      カテゴリ　'ITM'
 * @param currency 通貨　'gold' or 'coin'
 * @param num      個数
 */
ShopApi.buy = function(buy, cat, currency, num, callback){
    var get = {"buy":buy, "cat":cat, "currency":currency, "num":num};
    var api = new Api(apiOnShop, get, null);

    if(currency == "gold" || PLATFORM_TYPE == "nati"){
        //マグナ、ネイティブの場合はajaxで呼び出す
        api.transmit(callback);
    }else{
        //課金の場合は直接遷移
        //すぐ遷移するとサウンドが鳴らないので・・
        setTimeout(function(){
          top.location.href = api.url;
        }, 500);
    }
}

