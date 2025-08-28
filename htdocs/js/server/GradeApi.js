//---------------------------------------------------------------------------------------------------------
/**
 * ユーザー対戦画面を扱うAPI
 */
var GradeApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * 階級表一覧用API
 * @param なし
 */
GradeApi.list = function(callback){
    var api = new Api(apiOnGradeList, null, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 階級別ユーザー一覧用API
 * @param なし
 */
GradeApi.user = function(gradeId, page, callback){
    var get = {"gradeId": gradeId, "page":page};
    var api = new Api(apiOnGradeUser, get, null);
    api.transmit(callback);
}
