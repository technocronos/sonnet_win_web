//---------------------------------------------------------------------------------------------------------
/**
 * 戦歴情報を扱うAPI
 */
var BattleLogApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * 戦歴リスト用API
 * @method get
 * 
 * @param charaId  キャラID
 * @param tourId   Tournament_MasterServiceの定数
 * @param side     challenge：仕掛けた defend：仕掛けられた
 * @param page     ページ数
 */
BattleLogApi.list = function(charaId, tourId, side, page, callback){
    var get = {"charaId": charaId, "tourId":tourId, "side": side, "page":page};
    var api = new Api(apiOnBattleHistory, get, null);
    api.transmit(callback);
}
