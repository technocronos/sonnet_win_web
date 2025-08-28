//---------------------------------------------------------------------------------------------------------
/**
 * バトル情報を扱うAPI
 */
var BattleApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * バトル結果取得、送信用API
 * @method get
 * 
 * @param battleId    バトルID
 * @param side        challenge：仕掛けた defend：仕掛けられた
 * @param repaireId   修理したuser_item_id
 * 
 * @method post バトル結果を送信する。postの場合はバトル終了処理。
 * 
 */
BattleApi.result = function(battleId, side, repaireId, post,  callback){
    var get = {"battleId": battleId, "side":side, "repaireId": repaireId };
    var api = new Api(apiOnBattleResult, get, post);
    api.transmit(callback);
}


//---------------------------------------------------------------------------------------------------------
/**
 * ランキング一覧を取得する
 * @method get
 * 
 * @param battleId    バトルID
 *     const GRADEPT_DAILY = 11;
 *     const GRADEPT_WEEKLY = 12;
 * 
 */
BattleApi.ranking = function(type, count, page, callback){
    var get = {"type": type, "count":count, "page":page };
    var api = new Api(apiOnBattleRanking, get, null);
    api.transmit(callback);
}
