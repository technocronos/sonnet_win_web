<?php

class Mount_MasterService extends Service {

    const PLAYER_WEAPON = 1;
    const PLAYER_BODY = 2;
    const PLAYER_HEAD = 3;
    const PLAYER_SHIELD = 4;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された種族の装備箇所レコードの一覧を返す。
     *
     * @param int       種族ID
     * @return array    装備箇所レコードの一覧
     */
    public function getMounts($race) {

        static $cache = array();

        // キャッシュにあるならキャッシュから返す。
        if(array_key_exists($race, $cache))
            return $cache[$race];

        // 取得。
        $mounts = $this->selectResultset(array(
            'race' => $race,
            'ORDER BY' => 'sort_order',
        ));

        // キャッシュに保存してリターン。
        $cache[$race] = $mounts;
        return $mounts;
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('race', 'mount_id');

    protected $isMaster = true;
}
