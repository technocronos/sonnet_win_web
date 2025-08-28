//---------------------------------------------------------------------------------------------------------
/**
 * ヘルプ情報を扱うAPI
 */
var HelpApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * ヘルプリスト用API
 * @method get
 */
HelpApi.list = function(callback){
    var api = new Api(apiOnHelpList, null, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ヘルプ詳細用API
 * @method get
 * 
 * @param id
 */
HelpApi.detail = function(id, callback){
    var get = {"id": id};
    var api = new Api(apiOnHelpList, get, null);
    api.transmit(callback);
}
