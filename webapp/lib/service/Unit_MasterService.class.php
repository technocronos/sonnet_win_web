<?php

class Unit_MasterService extends Service {

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたIDのユニット用追加情報を取得する。
     *
     * @param int       キャラクタID
     * @return array    追加情報を収めた配列。
     */
    public function getInfo($charaId) {

        // レコードを取得。
        $result = $this->getRecord($charaId);

        // 見つからなかったらデフォルト値を返す。
        if(!$result) {
            return array(
                'move_pow' => 40,
                'battle_brain' => 50,
            );
        }

        // 不要な列を削除してリターン。
        unset($result['character_id']);
        if($result['reward_exp'] == -1)   unset($result['reward_exp']);
        if($result['reward_gold'] == -1)  unset($result['reward_gold']);

        return $result;
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'character_id';

    protected $isMaster = true;
}
