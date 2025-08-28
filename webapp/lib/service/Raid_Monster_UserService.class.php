<?php

class Raid_Monster_UserService extends Service {


    //-----------------------------------------------------------------------------------------------------
    /**
     * レイドダンジョンID内でモンスターがすでに倒されているか調べる
     *
     * @param user_id     ユーザーID。
     */
    public function getMonsterId($raid_dungeon_id, $monster_id) {

        // SQL作成＆実行
        $sql = "
          SELECT raid_monster_user.*
          FROM raid_monster_user left outer join raid_dungeon on 
          			raid_monster_user.raid_dungeon_id = raid_dungeon.id
          WHERE raid_dungeon_id = " . $raid_dungeon_id . " and monster_id = " . $monster_id . " and raid_monster_user.create_at >= raid_dungeon.start_at 
                AND raid_monster_user.create_at < raid_dungeon.end_at ";

        return $this->createDao(true)->getAll($sql, array($status));
    }

    public function getMonsterIdByDate($raid_dungeon_id, $monster_id, $date) {

        // SQL作成＆実行
        $sql = "
          SELECT raid_monster_user.*
          FROM raid_monster_user left outer join raid_dungeon on 
          			raid_monster_user.raid_dungeon_id = raid_dungeon.id
          WHERE raid_dungeon_id = " . $raid_dungeon_id . " and monster_id = " . $monster_id . " and raid_monster_user.create_at >= '" . $date . " 00:00:00' 
                AND raid_monster_user.create_at < '" . $date . " 23:59:59'";

        return $this->createDao(true)->getRow($sql, array($status));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * ユーザーのランキングを返す
     *
     * @param user_id     ユーザーID。
     */
    public function getUserRank($raid_dungeon_id) {

        // SQL作成＆実行
        $sql = "
          SELECT raid_dungeon_id, user_id, sum(point) as total_point
          FROM raid_monster_user left outer join raid_dungeon on 
          			raid_monster_user.raid_dungeon_id = raid_dungeon.id
          WHERE raid_dungeon_id = " . $raid_dungeon_id . " AND 
          			raid_monster_user.create_at >= raid_dungeon.start_at AND raid_monster_user.create_at < raid_dungeon.end_at 
          GROUP BY raid_dungeon_id,user_id
          ORDER BY total_point DESC
          ";

        return $this->createDao(true)->getAll($sql, array($status));
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * クリアされているか調べる
     *
     * @param user_id     ユーザーID。
     */
    public function is_clear($raid_dungeon_id) {

        //倒すべきモンスターの数
        $monSvc = new Monster_MasterService();
        $monsters = $monSvc->getMonsterList("appearance_area", 0, 1000, 0);
        $monster_count = count($monsters["resultset"]);

        $date = date('Y-m-d', strtotime("now"));

        $defeat_count = $this->getDefeatCount($raid_dungeon_id, $date);

        return ($defeat_count >= $monster_count ? true : false);

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 倒すべきモンスターの数を返す
     *
     * @param user_id     レイドダンジョンID。
     */
    public function getDefeatCountAll($raid_dungeon_id) {

        // SQL作成＆実行
        $sql = "
          SELECT count(*)
          FROM raid_monster_user left outer join raid_dungeon on 
          			raid_monster_user.raid_dungeon_id = raid_dungeon.id
          WHERE raid_dungeon_id = " . $raid_dungeon_id . " AND 
                raid_monster_user.create_at >= raid_dungeon.start_at AND raid_monster_user.create_at < raid_dungeon.end_at
          ";

         return $this->createDao(true)->getOne($sql);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 倒すべきモンスターの数を返す
     *
     * @param user_id     レイドダンジョンID。
     */
    public function getDefeatCount($raid_dungeon_id, $date) {

        // SQL作成＆実行
        $sql = "
          SELECT count(*)
          FROM raid_monster_user left outer join raid_dungeon on 
          			raid_monster_user.raid_dungeon_id = raid_dungeon.id
          WHERE raid_dungeon_id = " . $raid_dungeon_id . " AND raid_monster_user.create_at >= '" . $date . " 00:00:00' 
                AND raid_monster_user.create_at < '" . $date . " 23:59:59'
          ";

         return $this->createDao(true)->getOne($sql);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 一番最後に倒したのはいつかを返す
     *
     * @param user_id     レイドダンジョンID。
     */
    public function getLatestDefeatDate($raid_dungeon_id) {

        // SQL作成＆実行
        $sql = "
          SELECT MAX(raid_monster_user.create_at)
          FROM raid_monster_user
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
    public function setValue($raid_dungeon_id, $user_id, $monster_id, $point) {

        // キー重複無視でインサート
        $record = array(
            'raid_dungeon_id' => $raid_dungeon_id,
            'user_id' => $user_id,
            'monster_id' => $monster_id,
            'point' => $point,
        );

        $this->createDao()->insert($record, false, true);
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('id');
}
