<?php

class User_ItemService extends Service {

    // 装備系アイテムで、durable_countがいくつになったら100%壊れるか。-1未満であること。
    const USEFUL_LIMIT = -20;

    // 装備系アイテムで、durable_countがいくつ以下になったら警告を出すか。
    const USEFUL_WARN = 7;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザが持っている指定のアイテムのレコードを一つ取得する。
     * 持っていない場合はnullを返す。
     *
     * @param int       ユーザID
     * @param int       アイテムID
     * @return array    取得したuser_itemレコード。持っていない場合はnull。
     */
    public function getRecordByItemId($userId, $itemId) {

        return $this->selectRecord(array(
            'user_item.user_id' => $userId,
            'user_item.item_id' => $itemId,
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザが持っている指定の種別のアイテムレコードを一つ取得する。
     * 持っていない場合はnullを返す。
     * 指定の種別のアイテムを複数持っている場合は効果値がもっとも低いものを取得する。
     *
     * @param int       ユーザID
     * @param int       アイテム種別
     * @return array    取得したuser_itemレコード。持っていない場合はnull。
     */
    public function getRecordByType($userId, $itemType) {

        // 修理アイテムの一覧を取得。
        $items = Service::create('Item_Master')->getByType($itemType, 'item_value');

        // 効果の小さいものから順に見ていく。
        foreach($items as $item) {

            // 所持レコードを探す。
            $uitem = $this->getRecordByItemId($userId, $item['item_id']);

            // 持っているならそれを返す。
            if($uitem)
                return $uitem;
        }

        // ココまで来るのは持っていないから。nullを返す。
        return null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザが持っているアイテムの一覧をページ分けして取得する。
     * 結果セットの列には装備していない個数を表す擬似列 "free_count" が追加される。
     *
     * @param array     検索条件。以下のキーを含む配列。
     *                      user_id         必須。ユーザID。
     *                      category        省略可能。カテゴリで絞る場合に、item_master.categoryの値。
     *                                      配列での複数指定も可能。
     *                      mount_id        省略可能。装備可能品を表示する場合に、そのmount_master.mount_id
     *                      race            省略可能。パラメータmountを指定する場合は必須。
     *                      item_on_config  省略可能。コンフィグ中に使用できるもののみにするならtrueを指定する。
     *                      item_on_field   省略可能。フィールドで使用できるもののみにするならtrueを指定する。
     * @param int       1ページあたりの件数。0を指定した場合はページ分けを行わない。
     * @param int       何ページ目か。0スタート。ページ分けしないなら省略できる。
     * @return array    Service::selectPage と同様。
     *                  ただし、第二引数に 0 を指定した場合は結果セットが返る。
     */
    public function getHoldList($condition, $numOnPage, $page = 0) {

        // WHERE条件を作成。
        $where = array();
        $where['user_item.user_id'] = $condition['user_id'];
        $where['ORDER BY'] = 'user_item.create_at DESC';

        if(!empty($condition['category']))       $where['item_master.category'] = $condition['category'];
        if(!empty($condition['item_on_config'])) $where['item_master.item_type'] = Item_MasterService::$ON_CONFIG;
        if(!empty($condition['item_on_field']))  $where['item_master.item_type'] = Item_MasterService::$ON_FIELD;

        if(!empty($condition['mount_id'])) {
            $where['user_item.item_id'] = array(
                'sql' => '
                    IN (
                        SELECT item_id
                        FROM equippable_master
                        WHERE race = ?
                          AND mount_id = ?
                    )
                ',
                'value' => array($condition['race'], $condition['mount_id']),
            );
        }

        // DBから取得。
        if($numOnPage)
            $result = $this->selectPage($where, $numOnPage, $page);
        else
            $result = $this->selectResultset($where);

        // 取得した結果セットへの参照を取得。
        if($numOnPage)
            $resultset = &$result['resultset'];
        else
            $resultset = &$result;

        // ユーザ所属キャラが装備しているアイテム個数を取得する。
        $equipList = Service::create('Character_Equipment')->getEquippingList($condition['user_id']);

        // 所持個数から装備個数を引いた数を求めて、擬似列 "free_count" として格納する。
        foreach($resultset as &$record) {
            $equipCount = array_key_exists($record['user_item_id'], $equipList) ? $equipList[$record['user_item_id']] : 0;
            $record['free_count'] = $record['num'] - $equipCount;

            $record = Service::create('Item_Master')->getTransText($record);
        }unset($record);

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザが指定のアイテムをいくつ持っているかを返す。
     *
     * @param int       ユーザID
     * @param int       アイテムID。
     *                  複数のアイテムで同時に取得したい場合は配列で指定する。
     * @return mixed    アイテムIDを単独で指定したなら、そのアイテムの所持個数。
     *                  配列を使って複数指定したなら、アイテムIDをキー、個数を値とする配列。
     */
    public function getHoldCount($userId, $itemId) {

        // SQLのパラメータを初期化。
        $params = array();
        $params[] = $userId;

        // SQL作成。
        $sql = '
            SELECT item_id
                 , SUM(num) AS count
            FROM user_item
            WHERE user_id = ?
              AND item_id ' . DataAccessObject::buildRightSide($itemId, $params) . '
            GROUP BY item_id
        ';

        // 実行。
        $recordset = $this->createDao(true)->getAll($sql, $params);

        // アイテムIDが複数指定されたなら、[アイテムID] ⇒ [数] のペアにして返す。
        if( is_array($itemId) ) {

            // アイテムIDをキー、カウントを値とする配列を作成。
            $result = ResultsetUtil::colValues($recordset, 'count', 'item_id');

            // 指定されたアイテムのレコードが一つもなかった場合に備えて、0を補う。
            foreach($itemId as $id) {
                if( !is_null($id)  &&  !isset($result[$id]) )
                    $result[$id] = 0;
            }

            // リターン。
            return $result;

        // アイテムIDが単一で指定されたなら数だけ返す。
        }else {
            return $recordset ? $recordset[0]['count'] : 0;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザが、指定のアイテムを、指定の対象に使用できるかどうかをチェックする。
     * 不正な理由で使用できない場合は例外を投げる。
     * 正当な理由で使用できない場合はエラーメッセージを返す。
     *
     * @param int       アイテムを使おうとしているユーザのID
     * @param int       使おうとしているユーザアイテムID
     * @param int       使用対象のID。何のIDかはitem_typeによる。
     * @return string   エラーメッセージ。問題ないならカラ文字列。
     */
    public function checkUsing($userId, $uitemId, $targetId) {

        // 指定された user_item レコードを取得。
        $uitem = $this->needRecord($uitemId);

        // 自分のものでないならエラー。
        if($uitem['user_id'] != $userId)
            throw new MojaviException('自分のものではないアイテムを使おうとした。');

        // すべて装備中ならエラー。
        $equipList = Service::create('Character_Equipment')->getEquippingList($userId);
        $freeCount = $uitem['num'] - (isset($equipList[$uitemId]) ? $equipList[$uitemId] : 0);
        if($freeCount <= 0)
            throw new MojaviException('装備中のアイテムを使用しようとした');

        // コンフィグ中に使用できるものではないならエラー。
        if(!in_array($uitem['item_type'], Item_MasterService::$ON_CONFIG))
            throw new MojaviException('コンフィグ中に使用できないアイテムを使おうとした。');

        // 対象の正当性をチェック。
        switch($uitem['item_type']) {

            // 行動pt回復、対戦pt回復、レアモンスター遭遇率上昇の場合は自分以外には使えない。
            case Item_MasterService::RECV_AP:
            case Item_MasterService::RECV_MP:
            case Item_MasterService::ATTRACT:
                if($targetId != $userId)
                    throw new MojaviException('他のユーザにアイテムを使おうとした。');
                break;

            // キャラクターのステータスを調整するものは、対象は自分のキャラでないとNG
            case Item_MasterService::RECV_HP:
            case Item_MasterService::INCR_PARAM:
            case Item_MasterService::DECR_PARAM:
            case Item_MasterService::INCR_EXP:
                $chara = Service::create('Character_Info')->needRecord($targetId);
                if($chara['user_id'] != $userId)
                    throw new MojaviException('他のユーザのキャラにアイテムを使おうとした。');
                break;

            // アイテムを修理するものの場合は、対象は自分のアイテムでないとNG
            case Item_MasterService::REPAIRE:
                $target = $this->needRecord($targetId);
                if($target['user_id'] != $userId)
                    throw new MojaviException('他のユーザのアイテムに耐久回復を使おうとした。');
                break;
        }

        // ここまできたら不正な理由ではない。正当な理由をチェック。
        return Service::create('Item_Master')->checkItemUse($uitem['item_id'], $targetId);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザが、指定されたアイテムを手放す(捨てるorプレゼント)ことができるかどうか
     * チェックする。
     *
     * @param int       ユーザID
     * @param int       所持アイテムID
     * @param int       手放す数。-1を指定すると持っている数すべてになる。
     * @return string   手放すことが出来ない場合に、その理由を説明するコード。以下のいずれか。
     *                      equipping   装備中
     *                      forbidden   システムによって禁止されている
     *                      few_num     指定された数だけ持っていない
     *                  手放せるならカラ文字。
     */
    public function checkDisposable($ownerId, $uitemId, $disposeNum = 1) {

        // user_itemレコードを取得。
        $uitem = $this->needRecord($uitemId);

        // 捨てる数に-1が指定されている場合の対応。
        if($disposeNum == -1)
            $disposeNum = $uitem['num'];

        // 自分のものでないならエラー。
        if($uitem['user_id'] != $ownerId)
            throw new MojaviException('自分のものでないアイテムを捨てるorプレゼントしようとした');

        // そんなに持ってないならエラー。
        if($uitem['num'] < $disposeNum)
            return 'few_num';

        // 装備中でないかチェック。
        $equipList = Service::create('Character_Equipment')->getEquippingList($ownerId);
        if($uitem['num'] - (int)$equipList[$uitemId] < $disposeNum)
            return 'equipping';

        // プレゼント不可でないかチェック。
        if(!$uitem['present_flg'])
            return 'forbidden';

        // ここまでくればOK。
        return '';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザに指定のアイテムを付与する。
     *
     * @param int   ユーザID
     * @param int   アイテムID
     * @param int   付与する個数。耐久系の場合は1でなければならない。
     * @return int  アイテム付与で作成・更新されたレコードのuser_item_id。
     *              装備アイテムを複数付与した場合は最後にINSERTされたレコードのものになる。
     */
    public function gainItem($userId, $itemId, $gainNum = 1) {

        // 一応、エラーチェック。
        if($gainNum <= 0)
            throw new MojaviException('個数が不正です。');

        // 指定のアイテムの情報を取得。なかったらエラー。
        $itemSvc = new Item_MasterService();
        $item = $itemSvc->needRecord($itemId);

        // 消費系か耐久系かをチェック。
        $isDurable = Item_MasterService::isDurable($item['category']);

        // 耐久系アイテムを一度に複数与えようとしている場合は再帰して対処する。
        if($isDurable  &&  $gainNum > 1) {

            for($i = 0 ; $i < $gainNum ; $i++)
                $ret = $this->gainItem($userId, $itemId);

            return $ret;
        }

        // 消費系の場合はカウントアップを試行する。
        if(!$isDurable) {

            $uitem = $this->getRecordByItemId($userId, $itemId);

            // あるならカウントアップしてリターン。
            if($uitem) {
                $this->plusValue($uitem['user_item_id'], array('num'=>$gainNum));
                return $uitem['user_item_id'];
            }

            // ないなら後続の処理でレコードを作成する。
        }

        // レコードINSERT。
        $record = array(
            'user_id' => $userId,
            'item_id' => $itemId,
            'durable_count' => $item['durability'],
            'num' => $gainNum,
        );
        return $this->insertRecord($record, true);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザ所持アイテムを、指定のユーザへ移動する。
     * ただし、耐久系のアイテムのみ。
     *
     * @param int       user_item_id
     * @param int       受け取り先のユーザID
     */
    public function moveDurable($userItemId, $takerId) {

        $this->updateRecord($userItemId, array(
            'user_id' => $takerId,
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザ所持アイテムを使用する。
     *
     * @param int       ユーザアイテムID
     * @param int       使用対象のID。Item_MasterService::fireItem() と同様。
     * @return array    使用した結果のデータ。Item_MasterService::fireItem() と同様。
     */
    public function useItem($userItemId, $targetId) {

        // 使おうとしているアイテムを取得。
        $uitem = $this->needRecord($userItemId);

        // アイテム使用時の効果を発動。
        $result = Service::create('Item_Master')->fireItem($uitem['item_id'], $targetId);

        // アイテムを消費。
        $this->consumeItem($userItemId);

        // 効果をリターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザ所持アイテムを指定された数だけ減らす。
     *
     * @param int       ユーザアイテムID
     * @param int       減らす数。すべて捨てる場合は 0x7FFFFFFF を指定する。
     */
    public function consumeItem($uitemId, $consumeNum = 1) {

        // まず現在の個数を取る。
        $sql = '
            SELECT num
            FROM user_item
            WHERE user_item_id = ?
        ';
        $currentNum = $this->createDao(true)->getOne($sql, $uitemId);

        // 減らして、個数0になるならレコード削除。
        // そうではないなら、num列カウントダウン。
        if($currentNum <= $consumeNum)
            $this->deleteRecord($uitemId);
        else
            $this->plusValue($uitemId, array('num'=>-1 * $consumeNum));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された耐久系アイテムの経験値取得処理＆破損処理を行う。
     *
     * @param int       処理対象のuser_item_id。
     * @param int       使用度数
     * @param bool      破損判定を行うかどうか。
     * @return array    処理後のuser_itemレコード。損壊した場合はnull。
     */
    public function spend($userItemId, $spend, $judgeBreak = true) {

        // 更新前のレコードを取得。
        $before = $this->needRecord($userItemId);

        if($before["evolution"] == 1)
            $spend = 0;

        // 経験値と耐久値を増減。耐久値は無限値の場合は減らさないことに注意。
        $this->updateRecord($userItemId, array(
            'durable_count' => array(
                'sql' => 'durable_count - IF(durable_count = ?, 0, ?)',
                'value' => array(Item_MasterService::INFINITE_DURABILITY, $spend)
            ),
            'item_exp' => array('sql'=>'item_exp + ?', 'value'=>$spend)
        ));

        // 更新後のレコードを取得。
        $after = $this->needRecord($userItemId);

        // 引数で破損判定を行うように指定されていて、かつ、耐久値が0を下回ったら破損判定。
        if($judgeBreak  &&  $after['durable_count'] < 0) {

            // durable_countのマイナス幅に応じた確率で壊す。
            $rand = mt_rand(self::USEFUL_LIMIT, -1);
            if($after['durable_count'] <= $rand) {

                // レコード削除
                $this->deleteRecord($userItemId);

                // 履歴を残す。
                Service::create('History_Log')->insertRecord(array(
                    'user_id' => $after['user_id'],
                    'type' => History_LogService::TYPE_ITEM_BREAK,
                    'ref1_value' => $after['item_id'],
                ));

                // null を返す。
                return null;
            }

        }

        // 以降、破損していない場合。

        // レベルアップしていたら履歴を残す。
        if($before['level'] < $after['level']) {
            Service::create('History_Log')->insertRecord(array(
                'user_id' => $after['user_id'],
                'type' => History_LogService::TYPE_ITEM_LVUP,
                'ref1_value' => $after['item_id'],
                'ref2_value' => $after['level'],
            ));
        }

        // 更新後のレコードを返す。
        return $after;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された耐久系アイテムの経験値取得処理のみを行う。破損処理は行わない。
     *
     * @param int       処理対象のuser_item_id。
     * @param int       使用度数
     * @return array    処理後のuser_itemレコード。
     */
    public function spendExp($userItemId, $spend) {

        // 更新前のレコードを取得。
        $before = $this->needRecord($userItemId);

        // 経験値を増減。
        $this->updateRecord($userItemId, array(
            'item_exp' => array('sql'=>'item_exp + ?', 'value'=>$spend)
        ));

        // 更新後のレコードを取得。
        $after = $this->needRecord($userItemId);

        if($before["evolution"] == 0 && $after["evolution"] == 1){
            // 進化した場合は耐久値を無限値に。
            $this->updateRecord($userItemId, array(
                'durable_count' => Item_MasterService::INFINITE_DURABILITY,
            ));

            // 更新後のレコードを取得。
            $after = $this->needRecord($userItemId);

        }

        // レベルアップしていたら履歴を残す。
        if($before['level'] < $after['level']) {
            Service::create('History_Log')->insertRecord(array(
                'user_id' => $after['user_id'],
                'type' => History_LogService::TYPE_ITEM_LVUP,
                'ref1_value' => $after['item_id'],
                'ref2_value' => $after['level'],
            ));
        }

        // 更新後のレコードを返す。
        return $after;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のユーザの所持アイテムをすべて廃棄する。
     *
     * @param int       ユーザID
     */
    public function discardItem($userId) {

        $this->createDao()->delete( array('user_id'=>$userId) );
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'user_item_id';


    //-----------------------------------------------------------------------------------------------------
    /**
     * getSelectPhraseをオーバーライド。
     * item_masterをJOINして検索等で使用できるようにする。
     * また、item_level_master の列も一緒に取得するようにする。
     */
    protected function getSelectPhrase() {
        $sql = '
           SELECT user_item.*
                , item_level_master.level
                , item_level_master.attack1
                , item_level_master.attack2
                , item_level_master.attack3
                , item_level_master.defence1
                , item_level_master.defence2
                , item_level_master.defence3
                , item_level_master.speed
                , item_level_master.defenceX
                , item_level_master.evolution
           FROM user_item
                INNER JOIN item_master USING (item_id)
                LEFT OUTER JOIN item_level_master ON (
                        item_level_master.item_id = user_item.item_id
                    AND item_level_master.exp = (
                            SELECT MAX(exp)
                            FROM item_level_master
                            WHERE item_id = user_item.item_id
                              AND exp <= user_item.item_exp
                        )
                )
        ';

        global $TABLE_DATABASE;

        foreach($TABLE_DATABASE as $key=>$value){
            if(strstr($sql, $key)){
                $sql = str_replace($key , $value . "." . $key , $sql);
            }
        }

        return $sql;

    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * processResultset をオーバーライド。
     * Item_MasterService::getRecord で取得できる列も追加する。
     */
    protected function processResultset(&$resultset) {

        $itemIds = ResultsetUtil::colValues($resultset, 'item_id');
        $itemRecords = Service::create('Item_Master')->getRecordsIn($itemIds);

        foreach($resultset as &$record)
            $record = array_merge($record, $itemRecords[$record['item_id']]);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * processRecord をオーバーライド。
     * processResultset を経由するようにする。
     */
    protected function processRecord(&$record) {

        $set = array($record);
        $this->processResultset($set);
        $record = $set[0];
    }
}
