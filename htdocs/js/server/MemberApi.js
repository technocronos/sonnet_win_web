//---------------------------------------------------------------------------------------------------------
/**
 * 仲間を扱うAPI
 */
var MemberApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * 仲間リスト用API
 * @method get
 * @param userId
 */
MemberApi.list = function(userId, callback){
    var get = {"userId": userId};
    var api = new Api(apiOnMemberList, get, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 仲間を探す用API
 * @method get
 * @param userId
 */
MemberApi.search = function(callback){
    var api = new Api(apiOnMemberSearch, null, null);
    api.transmit(callback);
}


