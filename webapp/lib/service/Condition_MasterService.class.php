<?php

class Condition_MasterService extends Service {

    // value_type列の値。
    const QUEST_OPEN = 1;   // クエストのオープン／クローズ
    const SHOP_ID = 2;      // 地点のショップID
    const PLACE_OPEN = 3;   // 地点のオープン／クローズ


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数に指定された値種別が、指定されたユーザにおいて何になっているかを返す。
     *
     * @param int       値種別。このクラスで定義されている定数を使う。
     * @param int       値をもつ主体となるもののID
     * @param int       ユーザID
     * @param reference flavor_text列の値もほしい場合はそれを格納したい変数をここに指定する。
     * @return mixed    最後に該当するレコードの "go_value" の値。
     *                  指定のレコードが一つもない場合は false。
     *                  レコードはあるが一つも該当しない場合は null。
     */
    public function getValue($valueType, $ownerId, $userId, &$text = '') {

        // 指定された値の変遷を定義しているレコードを降順ですべて取得。
        $conditions = $this->selectResultset(array(
            'value_type' => $valueType,
            'owner_id' => $ownerId,
            'ORDER BY' => 'sequence DESC',
        ));

        // レコードが一つもないなら null リターン。
        if(count($conditions) == 0)
            return false;

        $flagSvc = new Flag_LogService();

        // レコードを後ろから順番に見ていく。
        foreach($conditions as $condition) {

            // "flag_group" 列が 0 なら無条件に該当する。
            if($condition['flag_group'] == 0)
                $flagOn = true;

            // それ以外はユーザが持っているフラグ次第。
            else
                $flagOn = $flagSvc->getValue($condition['flag_group'], $userId, $condition['flag_id']);

            // "flag_group2" 列が使用されている場合は、それも加味する。
            if( !is_null($condition['flag_group2']) ) {
                if( !$flagSvc->getValue($condition['flag_group2'], $userId, $condition['flag_id2']) )
                    $flagOn = false;
            }

            // 該当したなら、その値を使用。
            if($flagOn) {
                $text = $condition['flavor_text'];
                return $condition['go_value'];
            }
        }

        // ここまで来るのは、該当のレコードがないため。
        $text = '';
        return null;
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('value_type', 'owner_id', 'sequence');

    protected $isMaster = true;
}
