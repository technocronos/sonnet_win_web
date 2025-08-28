<?php

class Item_MasterService extends Service {

    // item_typeの数値を表す定数。
    const RECV_HP = 1;
    const RECV_AP = 2;
    const INCR_PARAM = 3;
    const DECR_PARAM = 4;
    const INCR_EXP = 5;
    const REPAIRE = 6;
    const TACT_ATT = 9;
    const ATTRACT = 10;
    const DTECH_UPPER = 11;
    const RECV_MP = 12;
    const CONTINUE_BATTLE = 13;

    // item_flags のビットマスクを表す定数
    const VIB_EFFECT = 0x0001;
    const DESTRUCT = 0x0002;
    const BARRIER = 0x0004;

    // 壊れない装備系アイテムであることを表す値。
    public static $DURABLES = array('WPN', 'BOD', 'HED', 'ACS');

    // 壊れない装備系アイテムであることを表す値。
    const INFINITE_DURABILITY = 32767;

    // コンフィグ画面で使用できる item_type の一覧。
    public static $ON_CONFIG = array(
        self::RECV_HP, self::RECV_AP, self::INCR_PARAM,  self::DECR_PARAM, self::INCR_EXP,
        self::REPAIRE, self::ATTRACT, self::DTECH_UPPER, self::RECV_MP,
    );

    // クエスト画面で使用できる item_type の一覧。
    public static $ON_FIELD = array(
        self::RECV_HP, self::TACT_ATT,
    );

    // 行動pt回復として誘導をかけるアイテムID
    const AP_RECOVER_ID = 1902;

    // 対戦pt回復として誘導をかけるアイテムID
    const MP_RECOVER_ID = 1901;

    // 装備修理として誘導をかけるアイテムID
    const REPAIRE_ID = 1905;

    // 必殺技パワーアップアイテム使用時に、発生率がいくつになるか
    const DTECH_UPPER_INVOKE = 33;
    const DTECH_UPPER_POWER = 30;

    //レア、Sレア遭遇率
    const RARE_ENCOUNT = 10;
    const SRARE_ENCOUNT = 2;

    const RARE_ENCOUNT_LV1 = 33;
    const RARE_ENCOUNT_LV2 = 33;
    const RARE_ENCOUNT_LV3 = 43;
    const SRARE_ENCOUNT_LV1 = 10;
    const SRARE_ENCOUNT_LV2 = 20;
    const SRARE_ENCOUNT_LV3 = 30;

    //コンティニューアイテムのアイテムID
    const BATTLE_CONTINUE_ID = 1911;

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたカテゴリのアイテムが、個別にパラメータを持つ耐久系のアイテムかどうかを返す。
     *
     * @param string    item_master.categoryの値。
     * @return bool     耐久系ならtrue、消費系ならfalse。
     */
    public static function isDurable($category) {
        return in_array($category, self::$DURABLES);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * マスタにある装備アイテムレコードをすべて返す。
     */
    public function getDurables() {

        return $this->selectResultset(array(
            'category' => self::$DURABLES,
            'ORDER BY' => 'item_id',
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたitem_typeのレコードをすべて返す。
     *
     * @param mixed     item_typeの値。配列で複数指定もできる。
     * @param string    ORDER BY に指定したい列名。
     */
    public function getByType($type, $orderBy = '') {

        return $this->selectResultset(array(
            'item_type' => $type,
            'ORDER BY' => $orderBy,
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された対象に指定されたアイテムを使用できるかをチェックして、
     * 出来ない場合はエラーメッセージを返す。
     *
     * @param int       アイテムID
     * @param int       使用対象のID。何のIDかはitem_typeによる。
     * @return string   エラーメッセージ。使用できるならカラ文字列。
     */
    public function checkItemUse($itemId, $targetId) {

        // 指定されたアイテムのレコードを取得。
        $item = $this->needRecord($itemId);

        // item_typeごとに分岐
        switch($item['item_type']) {

            // HP回復
            case self::RECV_HP:

                // キャラのHPがすでに満タンでないか調べる。
                $chara = Service::create('Character_Info')->needRecord($targetId);
                if($chara['hp'] >= $chara['hp_max'])
                    return AppUtil::getText("checkItemUse_error_RECV_HP");

                break;

            // 行動pt回復
            case self::RECV_AP:

                // 行動ptがすでに満タンでないか調べる。
                $user = Service::create('User_Info')->needRecord($targetId);
                if($user['action_pt'] >= ACTION_PT_MAX)
                    return AppUtil::getText("checkItemUse_error_RECV_AP");

                break;

            // 対戦pt回復
            case self::RECV_MP:

                // 対戦ptがすでに満タンでないか調べる。
                $user = Service::create('User_Info')->needRecord($targetId);
                if($user['match_pt'] >= MATCH_PT_MAX)
                    return AppUtil::getText("checkItemUse_error_RECV_MP");

                break;

            // 振り分けptアップ。
            case self::INCR_PARAM:

                $flagSvc = new Flag_LogService();
                $useCount = $flagSvc->getValue(Flag_LogService::PARAM_UP, $targetId, $itemId);

                if($item['item_limitation'] <= $useCount)
                    return AppUtil::getText("checkItemUse_error_INCR_PARAM");

                break;

            // ステータスダウンの代わりに振り分けptアップ。
            case self::DECR_PARAM:

                // ステータスダウンしすぎてマイナスにならないようにする。
                $chara = Service::create('Character_Info')->needRecord($targetId);
                if(
                       $chara['attack1'] <= $item['item_value']
                    || $chara['attack2'] <= $item['item_value']
                    || $chara['attack3'] <= $item['item_value']
                    || $chara['defence1'] <= $item['item_value']
                    || $chara['defence2'] <= $item['item_value']
                    || $chara['defence3'] <= $item['item_value']
                    || $chara['speed'] <= $item['item_value']
                    || $chara['hp_max'] <= $item['item_value'] * Character_InfoService::HP_SCALE
                ) {
                    return AppUtil::getText("checkItemUse_error_DECR_PARAM");
                }

                break;

            // 経験値増加。特に制限はない。
            case self::INCR_EXP:
                break;

            // 耐久値アップ。特に制限はない。
            case self::REPAIRE:
                break;

            // レアモンスター出現率アップ。特に制限はない。
            case self::ATTRACT:

                // $targetIdはユーザIDになっているので、アバターキャラのIDに直す。
                $targetId = Service::create('Character_Info')->needAvatarId($targetId);

                // 同じ効果が持続中なら使用不可
                if( Service::create('Character_Effect')->getEffectValue($targetId, Character_EffectService::TYPE_ATTRACT) )
                    return AppUtil::getText("checkItemUse_error_ATTRACT");

                break;

            // 必殺技パワーアップ。特に制限はない。
            case self::DTECH_UPPER:

                // 同じ効果が持続中なら使用不可
                if( Service::create('Character_Effect')->getEffectValue($targetId, Character_EffectService::TYPE_DTECH_POWUP) )
                    return AppUtil::getText("checkItemUse_error_DTECH_UPPER");

                break;

            // コンティニュー。特に制限はない。
            case self::CONTINUE_BATTLE:
                break;

            // それ以外は未定義。
            default:
                throw new MojaviException('定義されていないitem_typeです: ' . $item['item_type']);
        }

        return '';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された消費アイテム使用時の効果を、指定されたユーザに施す。
     *
     * @param int       アイテムID
     * @param int       使用対象のID。何のIDかはitem_typeによる。
     * @return array    使用した結果のデータ。item_type列の値によって異なる以下のキーを持つ。
     *                      item_type == RECV_AP
     *                          before  使用前の行動pt
     *                          after   使用後の行動pt
     *                      item_type == RECV_MP
     *                          before  使用前の対戦pt
     *                          after   使用後の対戦pt
     *                      item_type == RECV_HP
     *                          before  使用前のHP
     *                          after   使用後のHP
     *                      item_type == INCR_PARAM
     *                      item_type == DECR_PARAM
     *                          value   獲得した振り分けpt
     *                      item_type == INCR_EXP
     *                          hours   期限時間数
     *                          value   何%増加するか。
     *                      item_type == REPAIRE
     *                          before  使用前の耐久値
     *                          after   使用後の耐久値
     *                      item_type == ATTRACT
     *                          hours   期限時間数
     *                      item_type == DTECH_UPPER
     *                          hours   期限時間数
     */
    public function fireItem($itemId, $targetId) {

        $charaSvc = new Character_InfoService();

        // 指定されたアイテムの情報を取得。
        $item = $this->needRecord($itemId);

        // item_typeごとに分岐
        switch($item['item_type']) {

            // HP回復
            case self::RECV_HP:

                // 現在の値を取得。
                $before = $charaSvc->needRecord($targetId);

                // HP回復。
                $charaSvc->plusValue($targetId, array(
                    'hp' => $item['item_value']
                ));

                // 更新後の値を取得。
                $after = $charaSvc->needRecord($targetId);

                return array('before'=>$before['hp'], 'after'=>$after['hp']);

            // 行動pt回復
            case self::RECV_AP:

                $userSvc = new User_InfoService();

                // 現在の値を取得。
                $before = $userSvc->needRecord($targetId);

                // 行動pt回復。
                $userSvc->plusValue($targetId, array(
                    'action_pt' => $item['item_value']
                ));

                // 更新後の値を取得。
                $after = $userSvc->needRecord($targetId);

                return array('before'=>$before['action_pt'], 'after'=>$after['action_pt']);

            // 対戦pt回復
            case self::RECV_MP:

                $userSvc = new User_InfoService();

                // 現在の値を取得。
                $before = $userSvc->needRecord($targetId);

                // 行動pt回復。
                $userSvc->plusValue($targetId, array(
                    'match_pt' => $item['item_value']
                ));

                // 更新後の値を取得。
                $after = $userSvc->needRecord($targetId);

                return array('before'=>$before['match_pt'], 'after'=>$after['match_pt']);

            // 振り分けpt増大
            case self::INCR_PARAM:

                // 振り分けpt増大
                $charaSvc->plusValue($targetId, array(
                    'param_seed' => $item['item_value'],
                ));

                // 使用回数カウントアップ
                $flagSvc = new Flag_LogService();
                $flagSvc->countUp(Flag_LogService::PARAM_UP, $targetId, $itemId);

                return array('value'=>$item['item_value']);

            // ステータスダウンの代わりに振り分けptアップ。
            case self::DECR_PARAM:

                // ステータスダウンの代わりに振り分けpt増大
                $charaSvc->plusValue($targetId, array(
                    'attack1' =>  -1 * $item['item_value'],
                    'attack2' =>  -1 * $item['item_value'],
                    'attack3' =>  -1 * $item['item_value'],
                    'defence1' => -1 * $item['item_value'],
                    'defence2' => -1 * $item['item_value'],
                    'defence3' => -1 * $item['item_value'],
                    'speed' =>    -1 * $item['item_value'],
                    'hp_max' =>   -1 * $item['item_value'] * Character_InfoService::HP_SCALE,
                    'param_seed' => $item['item_value'] * 8,
                ));

                return array('value'=>$item['item_value'] * 8);

            // character_effectにレコードを作成するタイプのもの。
            case self::INCR_EXP:        // 経験値獲得量が増加。
            case self::ATTRACT:         // レアモンスターとの遭遇率が上昇
            case self::DTECH_UPPER:     // 必殺技の確率と威力が上昇

                // レアモンスターとの遭遇率上昇アイテムの場合、$targetIdはユーザIDになっているので、
                // アバターキャラのIDに直す。
                if($item['item_type'] == self::ATTRACT)
                    $targetId = Service::create('Character_Info')->needAvatarId($targetId);

                // character_effect.type の値を取得。
                switch($item['item_type']) {
                    case self::INCR_EXP:        $effectType = Character_EffectService::TYPE_EXP_INCREASE;   break;
                    case self::ATTRACT:         $effectType = Character_EffectService::TYPE_ATTRACT;        break;
                    case self::DTECH_UPPER:     $effectType = Character_EffectService::TYPE_DTECH_POWUP;    break;
                }

                // 効果レコードを作成。
                Service::create('Character_Effect')->insertRecord(array(
                    'character_id' => $targetId,
                    'type' => $effectType,
                    'value' => $item['item_value'],
                    'expire' => array('sql'=>'NOW() + INTERVAL ? HOUR', 'value'=>$item['item_limitation']),
                    'cause_item_id' => $itemId,
                ));

                return array('hours'=>$item['item_limitation'], 'value'=>$item['item_value']);

            // 装備品の耐久値が増加。
            case self::REPAIRE:

                $uitemSvc = new User_ItemService();

                // 現在の値を取得。
                $uitem = $uitemSvc->needRecord($targetId);

                // 増加。
                $uitemSvc->plusValue($targetId, array(
                    'durable_count' => $item['item_value'],
                ));

                return array('before'=>$uitem['durable_count'], 'after'=>$uitem['durable_count']+$item['item_value']);

            // コンティニュー。
            case self::CONTINUE_BATTLE:
                // 現在の値を取得。
                $before = $charaSvc->needRecord($targetId);

                // HP全回復。
                $charaSvc->plusValue($targetId, array(
                    'hp' => $before["hp_max"]
                ));

                // 更新後の値を取得。
                $after = $charaSvc->needRecord($targetId);

                return array('before'=>$before['hp'], 'after'=>$after['hp']);

            // それ以外は未定義。
            default:
                throw new MojaviException('定義されていないitem_typeです: ' . $item['item_type']);
        }
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたセットIDとカテゴリの装備を返す
     *
     * @param int       セットID
     * @param string    マウントID
     * @return array    指定された地点のクエストレコードの一覧。
     */
    public function getSetItem($setId, $mount) {

        if($mount == 1)
            $category = "WPN";
        else if($mount == 2)
            $category = "BOD";
        else if($mount == 3)
            $category = "HED";
        else if($mount == 4)
            $category = "ACS";

        $condition = array(
            'set_id' => $setId,
            'category' => $category,
        );

        return $this->selectRecord($condition);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 翻訳をする
     */
    public function getTransText($record){
        $columns = ["item_name", "flavor_text"];

        foreach($columns as $column){
            $data = AppUtil::getText("item_master_" . $column . "_" . $record[$this->primaryKey]);

            if($data == "")
                $data = $record[$column];

            $record[$column] = $data;
        }

        return $record;
    }



    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'item_id';

    protected $isMaster = true;

    //-----------------------------------------------------------------------------------------------------
    /**
     * exColumnをオーバーライド。
     * Lv1のitem_level_masterレコードの列を追加する。
     */
    protected function exColumn(&$record) {

        $ex = Service::create('Item_Level_Master')->getRecord($record['item_id'], 1, 0);

        if($ex)
            $record += $ex;
    }

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

    //-----------------------------------------------------------------------------------------------------
    /**
     * getRecordsInをオーバーライドして翻訳をする
     */
    public function getRecordsIn($pks, $keyShift = true) {
        $record = parent::getRecordsIn($pks, $keyShift);

        foreach($record as &$row) {
            $row = $this->getTransText($row);
        }

        return $record;
    }

}
