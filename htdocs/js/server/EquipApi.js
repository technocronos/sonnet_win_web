//---------------------------------------------------------------------------------------------------------
/**
 * 装備、アイテムを扱うAPI
 */
var EquipApi = new Api();

//---------------------------------------------------------------------------------------------------------
/**
 * 装備、アイテムリスト用API
 * @param なし
 */
EquipApi.list = function(callback){
    var api = new Api(apiOnEquipList, null, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 自動装備、装備をはずす、装備を変更、合成用API
 * @param func "auto"/"release"  自動装備/解除
 *        change                 変更するuser_item_id
 *        synth                  合成するuser_item_id
 *        mountId                変更・合成するmoundID
 */
EquipApi.change = function(func, change, synth, mountId, callback){
    var get = {"func": func, "change":change,  "synth": synth, "mountId": mountId};

    var api = new Api(apiOnEquipChange, get, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * アイテム使用API
 * @param uitemId                ユーザーアイテムID
 *        targetId               使う対象ユーザーアイテムID
 */
EquipApi.use = function(uitemId, targetId, callback){
    var get = {"uitemId": uitemId, "targetId":targetId};

    var api = new Api(apiOnItemUseFire, get, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 装備、アイテム廃棄API
 * @param uitemId                捨てるユーザーアイテムID
 */
EquipApi.discard = function(uitemId, callback){
    var get = {"uitemId": uitemId};

    var api = new Api(apiOnDiscard, get, null);
    api.transmit(callback);
}

//---------------------------------------------------------------------------------------------------------
/**
 * 合成の値段を得る用API
 * @param base_id                   合成ベースのuser_item_id
 *        source_id                 合成ソースのuser_item_id
 */
EquipApi.getPrice = function(base_id,source_id, callback){
    var get = {"base_id": base_id, "source_id":source_id};
    var api = new Api(apiOnEquipChange, get, null);
    api.transmit(callback);
}

