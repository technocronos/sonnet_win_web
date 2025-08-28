//---------------------------------------------------------------------------------------------------------
/**
 * おしらせを扱うAPI
 */
var NoticeApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * お知らせリスト用API
 * @param なし
 */
NoticeApi.list = function(page, callback){
    var get = {"page":page};
    var api = new Api(apiOnNotice, get, null);
    api.transmit(callback);
}