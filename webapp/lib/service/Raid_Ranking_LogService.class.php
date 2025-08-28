<?php

class Raid_Ranking_LogService extends Service {

    //-----------------------------------------------------------------------------------------------------
    /**
     * そのレイドダンジョンIDのログ個数を得る
     *
     * @param user_id     レイドダンジョンID。
     */
    public function getCount($raid_dungeon_id) {

        // SQL作成＆実行
        $sql = "
          SELECT count(*)
          FROM raid_ranking_log
          WHERE raid_dungeon_id = " . $raid_dungeon_id;

         return $this->createDao(true)->getOne($sql);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたレコードの値をセットする。レコードがない場合は作成する。
     *
     * @param int   flag_group の値。このクラスの定数を使用する。
     * @param int   owner_id の値
     * @param int   flag_id の値。
     * @param int   flag_value の値。
     */
    public function setValue($raid_dungeon_id, $user_id, $point, $rank) {

        // キー重複無視でインサート
        $record = array(
            'raid_dungeon_id' => $raid_dungeon_id,
            'user_id' => $user_id,
            'point' => $point,
            'rank' => $rank,
        );

        $this->createDao()->insert($record, false, true);
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('raid_dungeon_id', 'user_id');
}
