<?php

class Character_EquipmentService extends Service {

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたキャラの現在の装備を返す。
     *
     * @param int       キャラクターID
     * @return array    装備箇所を表すmount_idの値をキー、装備しているuser_itemレコードを値とする配列。
     */
    function getEquipments($characterId) {

        static $cache = array();

        // キャッシュにあるならキャッシュから返す。
        if(array_key_exists($characterId, $cache))
            return $cache[$characterId];

        // 指定されたキャラの装備情報を取得。
        $equips = $this->selectResultset(array('character_id'=>$characterId));

        // 何も装備していないならその旨のリターン。
        if(count($equips) == 0)
            return $equips;

        // 装備箇所を表すmount_idの値をキー、装備しているuser_item_idを値とする配列に変換する。
        $equips = ResultsetUtil::colValues($equips, 'user_item_id', 'mount_id');

        // 装備している user_item レコードを取得。
        $uitems = Service::create('User_Item')->getRecordsIn($equips);

        // 値を、user_item_idからuser_itemレコードに変換する。
        foreach($equips as &$value)
            $value = $uitems[$value];

        // キャッシュに保存してリターン。
        $cache[$characterId] = $equips;
        return $equips;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたキャラが、指定された所有アイテムを装備できるのかどうかをチェックする。
     *
     * @param int       キャラクターID
     * @param int       装備箇所ID
     * @param int       所有アイテムID(user_item_id)
     * @param bool      装備可能レベルのチェックまで行うかどうか。
     * @return array    装備できるならtrue、できないならfalse。
     */
    public function isEquippable($charaId, $mountId, $uitemId, $strict = false) {

        // 指定されたキャラのユーザIDを取得。
        $chara = Service::create('Character_Info')->needRecord($charaId);

        // 指定されたアイテムを取得。
        $uitem = Service::create('User_Item')->needRecord($uitemId);

        // 自分のアイテムじゃないならエラー
        if($uitem['user_id'] != $chara['user_id'])
            throw new MojaviException('自分のものでないアイテムを装備しようとした');

        // 装備可能マスタのレコードを取得。
        $equip = Service::create('Equippable_Master')->getRecord(
            $chara['race'], $mountId, $uitem['item_id']
        );

        // 装備可能マスタにレコードがないなら装備不可
        if(!$equip)
            return false;

        // 厳密チェックを指定されているとき、キャラレベルが装備可能レベルに到達していないなら装備できない。
        if($strict  &&  $chara['level'] < $equip['equippable_level'])
            return false;

        // ここまでくればOK
        return true;
   }

    public function getEquippableLevel($charaId, $mountId, $uitemId) {
        // 指定されたキャラのユーザIDを取得。
        $chara = Service::create('Character_Info')->needRecord($charaId);

        // 指定されたアイテムを取得。
        $uitem = Service::create('User_Item')->needRecord($uitemId);

        // 自分のアイテムじゃないならエラー
        if($uitem['user_id'] != $chara['user_id'])
            throw new MojaviException('自分のものでないアイテムを装備しようとした');

        // 装備可能マスタのレコードを取得。
        $equip = Service::create('Equippable_Master')->getRecord(
            $chara['race'], $mountId, $uitem['item_id']
        );

        // 装備可能マスタにレコードがないなら装備不可
        if(!$equip)
            return false;

        // ここまでくればOK
        return $equip['equippable_level'];
        
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザがオーナーになっているキャラが装備しているアイテムとその個数を返す。
     *
     * @param int       ユーザID
     * @return array    user_item_id をキー、装備個数を値とする配列。
     */
    public function getEquippingList($userId) {

        // 指定のユーザがオーナーになっているキャラクターのIDをすべて取得。
        $charaIds = Service::create('Character_Info')->getCharaIds($userId);

        // character_equipmentテーブルにある user_item_id をカウント。
        $sql = '
            SELECT user_item_id
                 , COUNT(user_item_id) AS count
            FROM character_equipment
            WHERE character_id ' . DataAccessObject::buildRightSide($charaIds, $params) . '
            GROUP BY user_item_id
        ';
        $list = $this->createDao(true)->getAll($sql, $params);
        $list = ResultsetUtil::colValues($list, 'count', 'user_item_id');

        // スフィアに持ち出しているアイテムを取得。キャラ一人ずつ処理する。
        foreach($charaIds as $charaId) {

            // 持ち出しているアイテムを取得。
            $takeout = Service::create('Sphere_Info')->getTakeOuts($charaId);

            // 戻り値に追加。
            foreach($takeout as $uitemId => $count)
                $list[$uitemId] = (array_key_exists($uitemId, $list) ? $list[$uitemId] : 0) + $count;
        }

        // リターン
        return $list;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたキャラクターに、指定されたアイテムを装備させる。
     *
     * @param int       キャラクターID。
     * @param string    装備箇所ID。
     * @param int       所有アイテムID(user_item_id)。nullを指定した場合は装備を外す。
     */
    public function changeEquipment($characterId, $mountId, $userItemId) {

        // システムキャラの装備を変えようとしたらエラー。
        if($characterId < 0)
            throw new MojaviException('システムキャラの装備を変えようとした');

        // 装備を外すように指定されている場合はレコードを削除。
        if( is_null($userItemId) ) {
            $this->deleteRecord($characterId, $mountId);

        // 装備するアイテムが指定されている場合。
        }else {

            // 装備できないアイテムを装備しようとしている場合はエラー。
            if( !$this->isEquippable($characterId, $mountId, $userItemId) )
                throw new MojaviException('装備できないアイテムを装備しようとした');

            // まずはINSERTを試行。
            $record = array(
                'character_id' => $characterId,
                'mount_id' => $mountId,
                'user_item_id' => $userItemId,
            );
            $inserted = $this->createDao()->insert($record, false, true);

            // INSERTされていない、つまり既存のレコードがあるならUPDATE
            if(!$inserted) {
                $this->updateRecord(array($characterId, $mountId), array(
                    'user_item_id' => $userItemId,
                ));
            }
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたキャラクターの装備をすべて外す。
     *
     * @param int       キャラクターID。
     */
    public function releaseEquips($characterId) {

        // システムキャラの装備を変えようとしたらエラー。
        if($characterId < 0)
            throw new MojaviException('システムキャラの装備を変えようとした');

        $where = array('character_id'=>$characterId);
        $this->createDao()->delete($where);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたキャラが装備しているアイテムに対して、経験値取得処理＆破損処理を行う。
     *
     * @param int       キャラクターID
     * @param bool      破損判定を行うかどうか。
     * @param array     以下のキーをもつ配列。
     *                      before      処理前の状態。
     *                          キー        装備箇所を表す値。mount_idの値。
     *                          値          その箇所に装備されているuser_itemレコード。
     *                      after       処理後の状態。beforeと同様。
     *                                  破損した箇所はnullになっている。
     */
    function spendEquips($characterId, $judgeBreak, $spends) {

        $uitemSvc = new User_ItemService();

        // 戻り値初期化。
        $result = array('before'=>array(), 'after'=>array());

        // 正規装備箇所の一覧を取得。
        $chara = Service::create('Character_Info')->needRecord($characterId);
        $mounts = Service::create('Mount_Master')->getMounts($chara['race']);

        // 指定されたキャラの装備情報を取得。
        $equips = $this->getEquipments($characterId);

        // 装備箇所を一つずつ処理していく。
        $uitemSvc = new User_ItemService();
        foreach(ResultsetUtil::colValues($mounts, 'mount_id') as $mountId) {

            if( empty($equips[$mountId]) )
                continue;

            // beforeに格納。
            $result['before'][$mountId] = $equips[$mountId];

            // その装備箇所の消耗度を取得。
            $spend = array_key_exists($mountId, $spends) ? $spends[$mountId] : 0;

            // 消耗がないならスキップ。
            if(!$spend) {
                $result['after'][$mountId] = $equips[$mountId];
                continue;
            }

            // 消耗処理して、afterに格納。
            $after = $uitemSvc->spend($equips[$mountId]['user_item_id'], $spend, $judgeBreak);
            $result['after'][$mountId] = $after;

            // 破損したなら、その箇所の装備を外す。
            if(!$after)
                $this->changeEquipment($characterId, $mountId, null);
        }

        // 結果をリターン。
        return $result;
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('character_id', 'mount_id');
}
