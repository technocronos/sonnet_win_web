//---------------------------------------------------------------------------------------------------------
/**
 * 仮想通貨を扱うAPI
 */
var VcoinApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * 仮想通貨出金ログリスト用API
 * @param なし
 */
VcoinApi.list = function(callback){
    var api = new Api(apiVcoinList, null, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 仮想通貨出金ログリスト用API
 * @param なし
 */
VcoinApi.log = function(callback){
    var api = new Api(apiVcoinLog, null, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 仮想通貨出金用API
 * @param amount      出金額
 * @param address      ウォレットアドレス
 */
VcoinApi.send = function(amount, address, callback){
    var get = {"amount":amount, "address":address};
    var api = new Api(apiVcoinSend, get, null);

    api.transmit(callback);

}

