//---------------------------------------------------------------------------------------------------------
/**
 * モンスター図鑑を扱うAPI
 */
var MonsterApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * モンスター図鑑用API
 * @param category　1：種族別 2:レア度別　3：登場地別　4:イベント別　5:倒した一覧
 */
MonsterApi.list = function(category, callback){
    var api = new Api(apiOnMonsterList, {"category": category}, null);
    api.transmit(callback);
}