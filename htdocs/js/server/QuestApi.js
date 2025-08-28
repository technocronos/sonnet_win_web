//---------------------------------------------------------------------------------------------------------
/**
 * クエスト関連を扱うAPI
 */
var QuestApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * クエストギブアップ用API
 * @param なし
 */
QuestApi.giveup = function(callback){
    var api = new Api(apiOnGiveup, null, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * クエストリスト
 * @param なし
 */
QuestApi.list = function(callback){
    var api = new Api(apiOnQuestList, null, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * クエスト結果
 * @param sphereId  クエストのsphereId
 */
QuestApi.field_end = function(sphereId, callback){
    var get = {"sphereId":sphereId};
    var api = new Api(apiOnFieldEnd, get, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * クエスト準備
 * @param get questId  クエストのquestId
 * @param get placeId  クエストのplaceId
 * @param post slot    持ち出すアイテム
 */
QuestApi.ready = function(questId, placeId, consume_pt, slot, callback){
    var get = {"questId":questId, "placeId":placeId, "consume_pt":consume_pt};
    var post = slot;

    var api = new Api(apiOnReady, get, post);
    api.transmit(callback);
}