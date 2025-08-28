//---------------------------------------------------------------------------------------------------------
/**
 * スフィア情報を扱うAPI
 */
var SphereApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * スフィアコマンド送信用API
 * @method get
 * 
 * @param get_param スフィア情報まるごと送信されたものを受け取る
 * 
 */
SphereApi.command = function(get_param, callback){
    var get = AppUtil.toObject(get_param);
    var api = new Api(apiOnSphereCommand, get, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * スフィアコマンド送信用API
 * @method get
 * 
 * @param get_param スフィア情報まるごと送信されたものを受け取る
 * 
 */
SphereApi.itemlist = function(get_param, callback){
    var get = AppUtil.toObject(get_param);
    var api = new Api(apiOnSphereItemList, get, null);
    api.transmit(callback);
}
