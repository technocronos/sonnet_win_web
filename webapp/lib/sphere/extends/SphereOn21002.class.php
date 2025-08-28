<?php

/**
 * エルフの迷い森の特殊処理を記述する
 */
class SphereOn21002 extends SphereCommon {

    //-----------------------------------------------------------------------------------------------------
    /**
     * "enemy7-ignitter" のギミックを処理する。
     */
    protected function igniteEnemy7(&$leads, &$gimmick, $unit) {

        // 3回に1回、"enemy7" を起動する。
        if(++$this->state['x_enemy7_count'] % 3 == 0)
            $this->triggerGimmick($leads, 'enemy7', $unit);
    }
}
