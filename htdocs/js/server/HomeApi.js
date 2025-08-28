//---------------------------------------------------------------------------------------------------------
/**
 * ホームの情報を扱うAPI
 */
var HomeApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * ホームサマリー用API
 * @method get
 */
HomeApi.summary = function(dataId, firstscene, his_user_id, sphereId, callback){
    var get = {"dataId":dataId, "firstscene":firstscene, "his_user_id": his_user_id, "sphereId":sphereId};
    var api = new Api(apiOnHomeSummary, get, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 引き継ぎ用API
 * @method get
 */
HomeApi.inherit = function(inherit_code, callback){
    var get = {"inherit_code":inherit_code};
    var api = new Api(apiOnHomeInherit, get, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ユーザー登録用API
 * @method post
 */
HomeApi.regist = function(name, callback){
    var post = {"name":name};
    var api = new Api(apiOnUserRegist, null, post);
    api.transmit(callback);
}
