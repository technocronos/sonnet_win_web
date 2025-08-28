<?php

/**
 * 湿原のマリーを処理するクラス。
 */
class SphereUnit15004Marie extends SphereUnit {

    //-----------------------------------------------------------------------------------------------------
    /**
     * event() をオーバーライド。
     */
    public function event(&$leads, $event) {

        // HP0で死んだ場合に...
        if($event['name'] == 'exit'  &&  $event['reason'] == 'collapse') {

            // 消える前にセリフを表示するようにする。
            $avatar = $this->sphere->getUnitByCode('avatar');
            $avatarNo = sprintf('%03d', $avatar->getNo());

            if($avatar)
                $leads = array_merge($leads, AppUtil::getTexts("sphere_15004_event_1", array("%avatar%"), array($avatarNo)));

            $marieNo = sprintf('%03d', $this->getNo());

            $leads = array_merge($leads, AppUtil::getTexts("sphere_15004_event_2", array("%marie%"), array($marieNo)));

            // スフィアを閉じるようにする。
            $closeEvent = array('type'=>'close', 'result'=>Sphere_InfoService::FAILURE);
            $this->sphere->pushStateEvent($closeEvent, true);
        }

        return parent::event($leads, $event);
    }

}
