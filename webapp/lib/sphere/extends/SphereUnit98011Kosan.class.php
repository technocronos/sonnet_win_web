<?php

/**
 * 電脳世界のソネットの古参を処理するクラス。
 */
class SphereUnit98011Kosan extends SphereUnit {

    //-----------------------------------------------------------------------------------------------------
    /**
     * event() をオーバーライド。
     */
    public function event(&$leads, $event) {

        // HP0で死んだ場合に...
        if($event['name'] == 'exit'  &&  $event['reason'] == 'collapse') {

            $leads[] = sprintf(AppUtil::getText("sphere_98011_event_1"), $this->getNo());

            // スフィアを閉じるようにする。
            $closeEvent = array('type'=>'close', 'result'=>Sphere_InfoService::FAILURE);
            $this->sphere->pushStateEvent($closeEvent, true);
        }else if($event['name'] == 'x_set_out'){
            $this->progressSetOut($leads);
        }

        return parent::event($leads, $event);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 登場後、1回待機してカウントが解けたときの処理を行う。
     */
    private function progressSetOut(&$leads) {

        $kosan = $this->sphere->getUnitByCode('kosan');
        $kosanNo = sprintf('%03d', $kosan->getNo());

        // プレイヤーキャラの名前を取得。
        $avatar = $this->sphere->getUnitByCode('avatar');
        $avatarNo = sprintf('%03d', $avatar->getNo());

        // 移動＆セリフ
        $leads = array_merge($leads, AppUtil::getTexts("sphere_98011_progressSetOut_1", array("%avatar%", "%kosan%"), array($avatarNo, $kosanNo)));
        //$this->setPos(3, 4);

        // 思考タイプ変更
        //$this->changeBrain('generic');
    }

}
