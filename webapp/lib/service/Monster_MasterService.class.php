<?php

class Monster_MasterService extends Service {

    // category の値の一覧
    public static $CATEGORIES = array(
        1 => '植物',    2 => '水棲',    3 => 'ｿﾞﾝﾋﾞ',   4 => '蟲',       5 => '猛獣',
        6 => '人間',    7 => '亜人',    8 => '機械',    9 => '幻影',    10 => '伝説',
    );

    // rare_level の値の一覧
    public static $RARE_LEVELS = array(
        1 => 'ノーマル',
        2 => 'レア',
        3 => 'Sレア',
    );

    public static function getCategorys() {
        $array = array();

        foreach(self::$CATEGORIES as $key=>$value){
            $array[$key] = AppUtil::getText("Monster_Master_CATEGORIES" . $key);
        }

        return $array;
    }

    public static function getRareLevels() {
        $array = array();

        foreach(self::$RARE_LEVELS as $key=>$value){
            $array[$key] = AppUtil::getText("Monster_Master_RARE_LEVELS" . $key);
        }

        return $array;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された登場地(appearance_area)の値に対応するテキストを返す。
     */
    public static function getAppearanceText($value) {

        if($_GET['value'] == 0) {
            return AppUtil::getText("quest_master_quest_name_99999");
        }else if($_GET['value'] < 10000) {
            $place = Service::create('Place_Master')->needRecord($_GET['value']);
            return $place['place_name'];
        }else {
            $place = Service::create('Quest_Master')->needRecord($_GET['value']);
            return $place['quest_name'];
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された分類のモンスターをページ分けして取得する。
     *
     * @param string    分類のフィールド名。monster_master の列名で指定する。
     * @param int       指定したフィールドの値。
     * @param int       1ページあたりの件数。
     * @param int       何ページ目か。0スタート。
     * @return array    Service::selectPage と同様。
     */
    public function getMonsterList($field, $value, $numOnPage, $page) {

        $condition = array("monster_master.{$field}"=>$value, 'ORDER BY'=>'character_info.exp');

        return $this->selectPage($condition, $numOnPage, $page);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された分類のモンスターをページ分けして取得する。
     *
     * @param int       モンスターのcharacter_id
     * @return int      キャプチャーできるならキャプチャー用character_id、できないならnull
     */
    public function isCaptureable($characterId) {

        // 同じモンスターのレベル違いの場合は、IDを統一して、キャプチャー用character_idとする。
        if( in_array($characterId, array(-3202, -3102, -2202, -1303, -1302, -1203, -1202, -1103, -1102)) )
            $characterId = (int)ceil($characterId / 100) * 100 - 1;     // character_idはマイナス値であることに注意。

        // そのIDがマスターにあるならキャプチャー可能。
        return $this->countRecord(array('character_id'=>$characterId)) ? $characterId : null;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 図鑑に登録されているモンスターの数を返す。
     */
    public function getMonsterCount() {

        return $this->countRecord();
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 翻訳をする
     */
    public function getTransText($record){
        $columns = ["habitat", "flavor_text"];

        foreach($columns as $column){
            $data = AppUtil::getText("monster_master_" . $column . "_" . $record[$this->primaryKey]);

            if($data == "")
                $data = $record[$column];

            $record[$column] = $data;
        }

        return $record;
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $isMaster = true;

    protected $primaryKey = 'character_id';


    //-----------------------------------------------------------------------------------------------------
    /**
     * getSelectPhraseをオーバーライド。
     * item_masterをJOINして検索等で使用できるようにする。
     * また、item_level_master の列も一緒に取得するようにする。
     */
    protected function getSelectPhrase() {
        return '
           SELECT *
                , character_info.exp AS level
           FROM monster_master
                INNER JOIN character_info USING (character_id)
        ';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * processResultset をオーバーライド。
     * Unit_MasterService::getRecord で取得できる列も追加する。
     */
    protected function processResultset(&$resultset) {

        $ids = ResultsetUtil::colValues($resultset, 'character_id');
        $units = Service::create('Unit_Master')->getRecordsIn($ids);

        foreach($resultset as &$record){
            $record += (array)$units[ $record['character_id'] ];
            $record = $this->getTransText($record);
        }
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
