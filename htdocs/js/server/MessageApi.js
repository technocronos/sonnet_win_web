//---------------------------------------------------------------------------------------------------------
/**
 * メッセージを扱うAPI
 */
var MessageApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * メッセージリスト用API
 * @method get
 * @param userId
 *        type receive/send
 */
MessageApi.list = function(userId, type, callback){
    var get = {"userId": userId, "type": type};
    var api = new Api(apiOnMessageList, get, null);
    api.transmit(callback);
}


//---------------------------------------------------------------------------------------------------------
/**
 * メッセージ送信用API
 * @method post
 * @param  companionId　送信対象ID
 */
MessageApi.send = function(companionId, body, callback){
    var get = {"companionId": companionId};
    var post = {"body":body};

    var api = new Api(apiOnMessage, get, post);
    api.transmit(callback);
}
