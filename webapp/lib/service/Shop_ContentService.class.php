<?php

class Shop_ContentService extends Service {

    // 課金ショップのID。
    const COIN_SHOP = 0;

    // 通常ショップのID。
    const NORMAL_SHOP = -1;


    //-----------------------------------------------------------------------------------------------------
    /**
     * ショップの販売アイテム一覧を、ページを指定して取得する。
     *
     * @param array     検索条件。以下のキーをすべて含む配列。
     *                      user_id     ユーザID。
     *                      shop_id     ショップID
     *                      category    "ITM":消費アイテム、"EQP":装備品 のどちらか。
     * @param int       1ページあたりの件数。
     * @param int       何ページ目か。0スタート。
     * @return array    Service::selectPage と同様。結果セットには以下の疑似列が追加されている。
     *                      show_only   指定された階級から見て購入不可かどうかを表すフラグ値。
     *                      grade       開放階級のgrade_masterレコード。開放条件がない場合は null。
     *                      hold        現在の所持個数。
     */
    public function getShelf($vals, $numOnPage, $page = 0) {

        // 指定されたユーザのキャラクター情報を取得。
        $chara = Service::create('Character_Info')->needAvatar($vals['user_id']);

        // 検索。
        $condition = array();
        $condition['shop_content.shop_id'] = $vals['shop_id'];
        $condition['item_master.category'] = ($vals['category'] == 'EQP') ? Item_MasterService::$DURABLES : array('ITM', 'SYS');
        $condition['shop_content.unlock_level'] = array('sql'=>'<= ?', 'value'=>$chara['level']);
        $condition['ORDER BY'] = 'shop_content.sort_order';
        $list = $this->selectPage($condition, $numOnPage, $page);

        // 擬似列を追加。
        $uitemSvc = new User_ItemService();
        foreach($list['resultset'] as &$record) {

            $record['show_only'] = false;

            if($vals['user_id'])
                $record['hold'] = $uitemSvc->getHoldCount($vals['user_id'], $record['item_id']);

            if($vals['category'] == 'EQP'){
                $set_data = Service::create('Set_Master')->getRecord($record['item']['set_id']);
                $record['set_name'] = $set_data['set_name'];
            }

            $record = Service::create('Item_Master')->getTransText($record);

        }

        // リターン。
        return $list;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたショップ内ラインナップにおいて、次にリリースされるアイテムを返す。
     *
     * @param array     検索条件。getShelf()の第一引数と同じ。
     * @return array    次にリリースされるレコード。
     */
    public function nextReleaseItem($vals) {

        // 指定されたユーザのキャラクター情報を取得。
        $chara = Service::create('Character_Info')->needAvatar($vals['user_id']);

        // 基本的な検索条件を作成。
        $condition = array();
        $condition['shop_content.shop_id'] = $vals['shop_id'];
        $condition['item_master.category'] = ($vals['category'] == 'EQP') ? Item_MasterService::$DURABLES : 'ITM';
        $condition['shop_content.unlock_level'] = array('sql'=>'> ?', 'value'=>$chara['level']);
        $condition['ORDER BY'] = 'shop_content.unlock_level';
        $condition['LIMIT'] = 1;

        return $this->selectRecord($condition);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ショップの販売アイテムをすべて返す。
     *
     * @param int       ショップID
     * @return array    指定のショップで売っているアイテムの一覧。
     */
    public function getSaleList($shopId) {

        return $this->selectResultset(array(
            'shop_content.shop_id' => $shopId,
            'ORDER BY' => 'shop_content.sort_order',
        ));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('shop_id', 'item_id');

    protected $isMaster = true;


    //-----------------------------------------------------------------------------------------------------
    /**
     * getSelectPhrase, getCountPhrase をオーバーライド。
     * item_masterの列も検索条件に含められるようにする。
     */
    protected function getSelectPhrase() {
        return '
            SELECT shop_content.*
            FROM shop_content
                 INNER JOIN item_master ON shop_content.item_id = item_master.item_id
        ';
    }

    protected function getCountPhrase() {
        return '
            SELECT COUNT(*)
            FROM shop_content
                 INNER JOIN item_master ON shop_content.item_id = item_master.item_id
        ';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * processRecord をオーバーライド。
     * Item_MasterService::getExRecord で取得したレコードを擬似列 "item" に格納する。
     */
    protected function processRecord(&$record) {

        $record['item'] = Service::create('Item_Master')->getExRecord($record['item_id']);
    }
}
