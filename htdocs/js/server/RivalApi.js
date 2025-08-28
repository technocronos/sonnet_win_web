//---------------------------------------------------------------------------------------------------------
/**
 * ユーザー対戦画面を扱うAPI
 */
var RivalApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * ユーザー対戦一覧用API
 * @param なし
 */
RivalApi.list = function(callback){
    var api = new Api(apiOnRivalList, null, null);
    api.transmit(callback);
}


//---------------------------------------------------------------------------------------------------------
/**
 * ユーザー対戦確認用API
 * @param rivalId       対戦相手のcharacter_id
 */
RivalApi.confirm = function(rivalId, doBattle, callback){
    var getParam = {"rivalId": rivalId};
    var postParam = null;

    if(doBattle != null)
        postParam = {"doBattle":doBattle};

    var api = new Api(apiOnBattleConfirm, getParam, postParam);
    api.transmit(callback);
}

