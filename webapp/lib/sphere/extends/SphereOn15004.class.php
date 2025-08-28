<?php

/**
 * ヌメール湿原の特殊処理を記述する
 */
class SphereOn15004 extends SphereCommon {

    // 初めてクリアした後は「愛よふたたび」を連続実行させる。
    //protected $nextQuestId = 15005;

    // ミッション達成までの...
#     const MISSION_ENEMIES = 5;      // 撃破数

    // ミッション達成時の報酬金。
#     protected $missionReward = 400;


    //-----------------------------------------------------------------------------------------------------
    /**
     * processSkipBattle()をオーバーライド。
     */
    protected function processSkipBattle(&$leads, $challenger, $defender) {

        // マリーが絡む戦闘を出来試合にする。
        if($challenger->getCode() == 'marie'  ||  $defender->getCode() == 'marie') {

            $opposite = ($challenger->getCode() == 'marie') ? $defender : $challenger;
            $damage = ($opposite->getProperty('character_id') == -3102) ? 72 : 100 + mt_rand(-20, 20);

            // バトルイベントを作成してキューへ。
            $battle = array();
            $battle['type'] = 'battle2';
            $battle['challenger'] = $challenger->getNo();
            $battle['defender'] = $defender->getNo();
            $battle['total']['challenger'] = ($challenger->getCode() == 'marie') ? $damage : 0;
            $battle['total']['defender'] =   ($challenger->getCode() == 'marie') ? 0 : $damage;
            $this->pushStateEvent($battle);

            // 基底の処理は行わない。
            return;
        }

        return parent::processSkipBattle($leads, $challenger, $defender);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * checkAchievement() をオーバーライド。
     */
#     protected function checkAchievement($resultCode) {
#
#         return $this->state['x_mission_count'] >= self::MISSION_ENEMIES;
#     }
}
