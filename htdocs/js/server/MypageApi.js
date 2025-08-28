
//---------------------------------------------------------------------------------------------------------
/**
 * マイページ情報を扱うAPI
 */
var MypageApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * マイページ画面ステータス取得用API
 * @param なし
 */
MypageApi.status = function(callback){
    var api = new Api(apiOnStatus, null, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * マイページ画面ステータス取得用API
 * @param なし
 */
MypageApi.other = function(userId, callback){
    var get = {"userId": userId};
    var api = new Api(apiOnHisPage, get, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * ステータスUP用API
 * @param charaId,attack1,attack2,attack3,defence1,defence2,defence3,speed,hp_max
 */
MypageApi.paramup = function(charaId,attack1,attack2,attack3,defence1,defence2,defence3,speed,hp_max, callback){
    var get = {"charaId": charaId, "attack1":attack1, "attack2":attack2, "attack3":attack3, "defence1":defence1, "defence2":defence2, "defence3":defence3, "speed":speed, "hp_max":hp_max };
    var api = new Api(apiOnParamUp, get, null);
    api.transmit(callback);
}

