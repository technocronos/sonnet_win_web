<?php

/**
 * 電脳世界のソネットの古参を処理するクラス。
 */
class SphereUnit98011Man2 extends SphereUnit {

    //-----------------------------------------------------------------------------------------------------
    /**
     * event() をオーバーライド。
     */
    public function event(&$leads, $event) {

        // HP0で死んだ場合に...
        if($event['name'] == 'exit'  &&  $event['reason'] == 'collapse') {
            $this->sphere->modifyGimmick('set_out_ork',   'rotation', $this->sphere->getStateProp('rotation') + 3);
            $this->sphere->modifyGimmick('set_out',       'rotation', $this->sphere->getStateProp('rotation') + 1);
        }

        return parent::event($leads, $event);
    }

}
