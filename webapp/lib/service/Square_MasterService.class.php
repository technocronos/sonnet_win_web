<?php

class Square_MasterService extends Service {

    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'square_id';

    protected $isMaster = true;

    public static $CATEGORY = array(
        'grass' => '通常草原' ,
        'wetland' => '湿原' ,
        'dungeon' => '通常洞窟' ,
        'colddungeon' => '氷の洞窟' ,
        'olddungeon' => '古の洞窟' ,
        'seadungeon' => '海底洞窟' ,
        'criverdungeon' => 'サイバー' ,
        'extradungeon' => '特別ダンジョン' ,
        'skydungeon' => '空ダンジョン' ,
        'penalcolony' => '流刑地' ,
        'penalcolony2' => '廃棄物処理場' ,
        'sand' => '砂漠' ,
        'westland' => '荒地' ,
        'facility' => '施設' ,
        'facility2' => '研究所' ,
        'base' => '基地' ,
        'castleunder' => '城地下' ,
        'castle' => '城' ,
        'city' => '町' ,
        'extracity' => '特殊町' ,
        'ship' => '船' ,
        'sea' => '海' ,
        'sand' => '砂漠' ,
        'snow' => '雪山' ,
        'room' => '部屋' ,
        'horror' => 'ホラー' ,
        'moon' => '月' ,
        'volcano' => '火山' ,
        'death' => '死' ,
        'common' => '共通' ,
    );

    //-----------------------------------------------------------------------------------------------------
    // 集計関連メソッド。

    /**
     * 引数で指定された日付範囲でユーザ登録数を集計する。
     *
     * @param string    集計範囲開始日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @param string    集計範囲終了日。「xxxx/xx/xx xx:xx:xx」の形式であること。
     * @return array    登録日をインデックス、登録人数を値とする配列。
     */
    public function selectSquare($fromId, $toId, $condition) {

        // まずは登録日別に集計。
/*
        $sql = "
            SELECT *
            FROM square_master
            WHERE square_id >= ?
              AND square_id < ?
            ORDER BY square_id ASC
        ";
*/
        $sql = "
            SELECT *
            FROM square_master
        ";

        if(!is_null($condition)){
            $str = implode("','" ,$condition);
            $sql .= "
                WHERE category in ('" . $str . "')
            ";
        }

        $sql .= "
            ORDER BY square_id ASC
        ";


        return $this->createDao(true)->getAll($sql, array($fromId, $toId));
    }
}
