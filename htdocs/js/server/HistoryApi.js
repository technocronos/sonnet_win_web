//---------------------------------------------------------------------------------------------------------
/**
 * 履歴情報を扱うAPI
 */
var HistoryApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * 履歴リスト用API
 * @method get
 * 
 * @param userId
 * @param type
 *                 comment   コメントの履歴
 *                 history   コメント以外の履歴
 * @param category me：自分の履歴 member：仲間の履歴
 * @param page     ページ数
 */
HistoryApi.list = function(userId,type,category,page, callback){
    var get = {"userId": userId, "type":type, "category": category, "page":page};
    var api = new Api(apiOnHistoryList, get, null);
    api.transmit(callback);
}

