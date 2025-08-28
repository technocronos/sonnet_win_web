<?php

/**
 * 電脳世界のソネットのオークキングを処理するクラス。
 */
class SphereUnit98011Orkking extends SphereUnit {

    //-----------------------------------------------------------------------------------------------------
    /**
     * event() をオーバーライド。
     */
    public function event(&$leads, $event) {

        if($event['name'] == 'x_set_out_ork'){
            $this->progressSetOut($leads);
        }

        return parent::event($leads, $event);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ３回待機したら動き出す
     */
    private function progressSetOut(&$leads) {
        $ork = $this->sphere->getUnitByCode('orkking');
        $orkNo = sprintf('%03d', $ork->getNo());

        $leads = array_merge($leads, AppUtil::getTexts("sphere_98011_progressSetOut_2", array("%ork%"), array($orkNo)));

        $this->changeBrain('generic');
    }
}
