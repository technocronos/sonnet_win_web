//---------------------------------------------------------------------------------------------------------
/**
 * ガチャ画面を扱うAPI
 */
var GachaApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * ガチャ用API
 */
GachaApi.gacha = function(callback){
    var api = new Api(apiOnGacha, null, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ガチャラインナップ用API
 * @param gachaId
 */
GachaApi.list = function(gachaId, callback){
    var api = new Api(apiOnGachaLineup, {"gachaId": gachaId}, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ガチャを回す用
 */
GachaApi.play = function(gachaId, go, count){
    getparam = {"gachaId":gachaId, "go":go, "count": count};

    //クエリ文字にする
    var query = "&" + AppUtil.serialize(getparam)

    var url = AppUtil.htmlspecialchars_decode(apiOnGachaPlay);

    //コンテナ経由型URLでない場合はエスケープしない
    if(URL_TYPE == "container")
        url = url + encodeURIComponent(query);
    else
        url = url + query;

    //すぐ遷移するとサウンドが鳴らないので・・
    setTimeout(function(){
      window.location.href = url;
    }, 500);
}


