//---------------------------------------------------------------------------------------------------------
/**
 * 申請情報を扱うAPI
 */
var ApproachApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * 申請リスト用API
 * @method get
 * @param userId
 *        type receive/send
 */
ApproachApi.list = function(side, callback){
    var get = {"side": side};
    var api = new Api(apiOnApproachList, get, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 申請承認、拒否、キャンセル、確認済みにする用API
 * @method post
 * 
 * @postparam approach_id
 * @postparam action
 *        accept　承認
 *        reject  拒否
 *        cancel  キャンセル
 *        clear   確認済み
 */
ApproachApi.accept = function(approach_id,callback){
    var post = {"approach_id": approach_id, "accept": 1};
    var api = new Api(apiOnApproachList, null, post);
    api.transmit(callback);
}
ApproachApi.reject = function(approach_id, callback){
    var post = {"approach_id": approach_id, "reject": 1};
    var api = new Api(apiOnApproachList, null, post);
    api.transmit(callback);
}
ApproachApi.cancel = function(approach_id, callback){
    var post = {"approach_id": approach_id, "cancel": 1};
    var api = new Api(apiOnApproachList, null, post);
    api.transmit(callback);
}
ApproachApi.clear = function(approach_id,callback){
    var post = {"approach_id": approach_id, "clear": 1};
    var api = new Api(apiOnApproachList, null, post);
    api.transmit(callback);
}


//---------------------------------------------------------------------------------------------------------
/**
 * 申請送信用API
 * @method  post
 * @param  get   companionId 対象ユーザーID
 * @param  post  approach　申請
 * @param  post  dissolve　申請解除
 */
ApproachApi.send = function(companionId, approach, dissolve, callback){
    var get = {"companionId" : companionId};
    var post = {"approach" : approach, "dissolve" : dissolve};

    var api = new Api(apiOnApproach, get, post);
    api.transmit(callback);
}
