//---------------------------------------------------------------------------------------------------------
/**
 * ユーザーアイテムを扱うAPI
 */
var UserItemApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * ユーザーアイテム取得用API
 * @param user_item_id
 */
UserItemApi.get = function(user_item_id, callback){
    var get = {"user_item_id":user_item_id};
    var api = new Api(apiOnUserItem, get, null);
    api.transmit(callback);
}
