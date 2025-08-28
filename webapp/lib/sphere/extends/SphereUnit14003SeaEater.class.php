<?php

/**
 * 海岸洞窟のシーイーターを処理するクラス。
 */
class SphereUnit14003SeaEater extends SphereUnit {

    //-----------------------------------------------------------------------------------------------------
    /**
     * damageHp をオーバーライド。
     */
    public function damageHp(&$leads, $damage, $trigger = null, $means = null) {

        // まずは基底の処理。
        $ret = parent::damageHp($leads, $damage, $trigger, $means);

        // まだ死んでないなら、"vomit_enemy" を起動。
        if($this->data['hp'] > 0) {
            $this->sphere->pushStateEvent(array(
                'type' => 'gimmick',
                'name' => 'vomit_enemy',
                'trigger' => $this->getNo(),
            ));
        }

        // リターン。
        return $ret;
    }
}
