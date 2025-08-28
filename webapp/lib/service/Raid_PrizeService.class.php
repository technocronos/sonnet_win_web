<?php

class Raid_PrizeService extends Service {

    const PRIZE_KIND_BTC = 1;
    const PRIZE_KIND_ITEM = 2;

    //-----------------------------------------------------------------------------------------------------
    /**
     * 配信の一覧を、ページを指定して取得する。
     *
     * @param int       1ページあたりの件数。
     * @param int       何ページ目か。0スタート。
     * @return array    DataAccessObject::getPage と同様。
     */
    public function getList($raid_dungeon_id) {

        // SQL作成＆実行
        $sql = "
          SELECT *
          FROM raid_prize
          WHERE raid_dungeon_id = " . $raid_dungeon_id;

        return $this->createDao(true)->getAll($sql, array($status));
    }

    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('raid_dungeon_id', 'rank_id', 'join_prize_kind');
}
