//---------------------------------------------------------------------------------------------------------
/**
 * 事前登録ログを扱うAPI
 */
var PreRegLogApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * 事前登録ログ更新
 * @param なし
 */
PreRegLogApi.set = function( code, callback){
    var get = {"code":code};
    var api = new Api(apiPreRegLogSet, get, null);
    api.transmit(callback);
}

