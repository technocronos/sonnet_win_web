<?php

// 文字列連結演算子を使っているため、クラス定義内で定義できない。しょうがないのでココで…
Place_MasterService::$SPECIAL_SHOP = array(
    'shop_id' => Shop_ContentService::COIN_SHOP,
    'flavor_text' => PLATFORM_CURRENCY_NAME . 'のｳﾙﾄﾗﾊﾟﾜｰで物が買えるのじゃ｡安いぞ!買ってけ',
);

class Place_MasterService extends Service {

    // ゲーム開始時の地点。
    const INITIAL_PLACE = 11;

    // 課金ショップの情報
    public static $SPECIAL_SHOP;

    // 通常ショップの情報
    public static $NORMAL_SHOP = array(
        'shop_id' => Shop_ContentService::NORMAL_SHOP,
        'flavor_text' => 'ﾏｸﾞﾅで買うｼｮｯﾌﾟなのだ',
    );

    // ショップがない場合の汎用情報
    public static $NO_SHOP = array(
        'shop_id' => null,
        'flavor_text' => 'ｺｺ､ｼｮｯﾌﾟないのだ｡買い物したいなら他の場所にいくしかないのだ',
    );


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたplace_idの背景画像への物理パスを返す。
     */
    public static function getBgImage($placeId) {

        return sprintf('%s/placeBg/%02d.jpg', IMG_RESOURCE_DIR, $placeId);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザが、指定された地域内で移動可能な地点の一覧を返す。
     *
     * @param int       ユーザID
     * @param int       地域ID
     * @return array    地点レコードの配列
     */
    public function getMovablePlaces($userId, $regionId) {

        // 指定の地域内の地点一覧を取得。
        $places = $this->getPlaces($regionId);

        // 移動不可な地点を排除。
        foreach($places as $index => $place) {
            if( !$this->isMovable($userId, $place) )
                unset($places[$index]);
        }

        // リターン。
        return $places;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザが、移動可能な地域のレコード一覧を返す。
     *
     * @param int       ユーザID
     * @return array    地域レコードの配列
     */
    public function getMovableRegions($userId) {

        // 地域の一覧を取得。
        $regions = $this->getPlaces(0);

        // 一つずつ見ていく。
        foreach($regions as $index => $region) {

            // 地域内の地点一覧を取得。
            $places = $this->getPlaces($region['place_id']);

            // 地点を一つずつ見て、移動可能な地点を一つでも見つけたら、次の地域へ。
            foreach($places as $place) {
                if( $this->isMovable($userId, $place) )
                    continue 2;
            }

            // 移動可能な地点が一つも見付からない場合、その地域は移動可能ではない。
            unset($regions[$index]);
        }

        // リターン。
        return $regions;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザが、指定された地域に移動可能かどうかを返す。
     *
     * @param int       ユーザID
     * @param int       地域ID、あるいは地域レコード。
     * @return bool     移動できるならtrue、できないならfalse。
     */
    public function isMovable($userId, $place) {

        // 引数を地域レコードに統一する。
        if( !is_array($place) )
            $place = $this->needRecord($place);

        // 地点IDとユーザのフラグ状態から、取得する。
        return (bool)Service::create('Condition_Master')->getValue(
            Condition_MasterService::PLACE_OPEN, $place['place_id'], $userId
        );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された地点の通常ショップの情報を返す。
     *
     * @param int       地点ID
     * @param int       ユーザID
     * @return array    以下のキーを持つ配列。
     *                      shop_id         ショップのID。ショップがない場合は null
     *                      flavor_text     ショップ画面で表示する飾りテキスト
     */
    public function getShop($placeId, $userId) {

        // 地点IDとユーザのフラグ状態から、ショップIDを取得。
        $shopId = Service::create('Condition_Master')->getValue(
            Condition_MasterService::SHOP_ID, $placeId, $userId, $flavorText
        );

        // ショップがまったく定義されていないなら、それ用の値を返す。
        if($shopId === false) {
            return self::$NO_SHOP;

        // リターン。
        }else {
            return array(
                'shop_id' => $shopId ? $shopId : null,
                'flavor_text' => $flavorText,
            );
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザが指定の場所に移動したときの、リダイレクトすべきクエストのIDを返す。
     *
     * @param int       ユーザID
     * @param int       地点ID
     * @return int      リダイレクトすべきクエストのID。ない場合はNULL。
     */
    public function getEventOnMove($userId, $placeId) {

        // 地点レコードを取得。
        $place = $this->needRecord($placeId);

        // 到着イベントがないなら、クエストはない。
        if(!$place['arrival_event'])
            return null;

        // あるなら、そのクエストをクリアしているかどうかを取得。
        $cleared = Service::create('Flag_Log')->getValue(Flag_LogService::CLEAR, $userId, $place['arrival_event']);

        // クリアしているなら自動クエストはなし、クリアしてないなら自動クエスト。
        return $cleared ? null : $place['arrival_event'];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された地域の地点の一覧を返す。
     *
     * @param int       地域ID
     * @return array    地域レコードの配列
     */
    public function getPlaces($regionId) {

        return $this->selectResultset(array(
            'region_id' => $regionId,
            'ORDER BY' => 'place_id',
        ));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 翻訳をする
     */
    public function getTransText($record){
        $columns = ["place_name"];

        foreach($columns as $column){
            $data = AppUtil::getText("place_master_" . $column . "_" . $record[$this->primaryKey]);

            if($data == "")
                $data = $record[$column];

            $record[$column] = $data;
        }

        return $record;
    }

    // 基底メンバの上書き。
    //=====================================================================================================

    protected $isMaster = true;

    protected $primaryKey = 'place_id';

    /**
     * getRecordをオーバーライドして多言語対応
     */
    public function getRecord(/* 可変引数 */) {
        $args = func_get_args();
        $record = parent::getRecord($args[0]);

        $record = $this->getTransText($record);

        return $record;
    }

    /**
     * selectResultsetをオーバーライドして多言語対応
     */
    public function selectResultset($where) {
        $record = parent::selectResultset($where);

        foreach($record as &$row){
            $row = $this->getTransText($row);
        }

        return $record;
    }
}
