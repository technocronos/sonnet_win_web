<?php

/**
 * 大脱走の主人公を処理するクラス。
 */
class SphereUnit31009Hero extends SphereUnit {

    //-----------------------------------------------------------------------------------------------------
    /**
     * event() をオーバーライド。
     */
    public function event(&$leads, $event) {

        switch($event['name']) {
            // HP0で死んだ場合に...
            case 'exit':
                if($event['reason'] == 'collapse') {
          					//誰に倒されたか
          					$enemy = $this->sphere->getUnit($event['trigger']);

          					//最後(31009002)に出てくるオーディンに倒されたときのみ
          					if($enemy->getCode() == "woden_last")
	                	return $this->progressTerminated($leads);
                }
                break;
        }

        return parent::event($leads, $event);
    }

	//restをオーバーライド
    public function brainRest() {

        // 何もしない。
        return array();
	}

    //-----------------------------------------------------------------------------------------------------
    /**
     * オーディンに倒されたときの処理を行う。
     */
    private function progressTerminated(&$leads) {

        $avatar = $this->sphere->getUnitByCode('avatar');
        $avatarNo = sprintf('%03d', $avatar->getNo());
        $galuf =  $this->sphere->getUnitByCode('Galuf');
        $galufNo =  sprintf('%03d', $galuf->getNo());
        $woden =  $this->sphere->getUnitByCode('woden_last');
        $wodenNo =  sprintf('%03d', $woden->getNo());

        // プレイヤーキャラの名前を取得。
        $avatarName = $this->sphere->getUnitByCode('avatar')->getProperty('name');

        // セリフ。
        $leads = array_merge($leads, AppUtil::getTexts("sphere_31009_progressTerminated_1", array("%avatar%"), array($avatarNo)));

        // 主人公二歩下がる。
        $heropath = $this->sphere->getMap()->getThroughRoute($avatar->getPos(), array(2,3), 'y');
        $leads[] = "UMOVE {$avatarNo} {$heropath}";
        $leads[] = "UALGN {$avatarNo} 0";

        $avatar->setPos(2, 3);

        // セリフ。
        $leads[] = "SPEAK {$galufNo} " . AppUtil::getText("DRAMA_CODE_shisyou") . " {$avatarName}!!";

	      //ガラフ、前へ
        $galufpath = $this->sphere->getMap()->getThroughRoute($galuf->getPos(), array(3,3), 'y');
        $leads[] = "UMOVE {$galufNo} {$galufpath}";

        $galuf->setPos(3, 3);

        $leads = array_merge($leads, AppUtil::getTexts("sphere_31009_progressTerminated_2", array("%avatar%", "%galuf%", "%woden%"), array($avatarNo, $galufNo, $wodenNo)));

        // ギミック起動
        $this->sphere->triggerGimmick($leads, 'disappear', $galuf);

        return false;
    }

}
