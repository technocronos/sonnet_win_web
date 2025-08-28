//---------------------------------------------------------------------------------------------------------
/**
 * キャラの情報を扱うAPI
 */
var CharaApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * キャラのアバター画像取得用API
 * @method get
 */
CharaApi.avator = function(prefix, user_id, battleId, side, callback){
    var get = {"prefix":prefix, "user_id" : user_id, "battleId":battleId, "side": side};
    var api = new Api(apiOnCharaImg, get, null);
    api.transmit(callback);
}
