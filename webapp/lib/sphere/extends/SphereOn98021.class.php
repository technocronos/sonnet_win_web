<?php

/**
 * アトランティスの呪い・本編クエの特殊処理を記述する
 */
class SphereOn98021 extends SphereCommon {

    // クリアした後は何度でも「後劇」を連続実行させる。
    protected $nextQuestId = 98022;
    protected $nextQuestRepeat = true;

    //-----------------------------------------------------------------------------------------------------
    /**
     * progressCommand()をオーバーライド。
     */
    public function progressCommand(&$leads) {

        //last_roomルームのみ
        if($this->state["current_room"] != "last_room")
            return parent::progressCommand($leads);

        $avatar = $this->getUnitByCode('avatar');
        $avatarNo = sprintf('%03d', $avatar->getNo());
        $shakuwa =  $this->getUnitByCode('shakuwa');
        $shakuwaNo =  sprintf('%03d', $shakuwa->getNo());
        $sophia =  $this->getUnitByCode('sophia');
        $sophiaNo =  sprintf('%03d', $sophia->getNo());

        // プレイヤーキャラの名前を取得。
        $avatarName = $this->getUnitByCode('avatar')->getProperty('name');

        // セリフ。
        $leads = array_merge($leads, AppUtil::getTexts("sphere_98021_progressCommand_1", array("%avatar%", "%shakuwa%", "%avatarName%"), array($avatarNo, $shakuwaNo, $avatarName)));

	      //シャクワ、主人公の前へ
        $shakuwapath = $this->getMap()->getThroughRoute($shakuwa->getPos(), array(6,6), 'y');
        $leads[] = "UMOVE {$shakuwaNo} {$shakuwapath}";
        $leads[] = "UALGN {$shakuwaNo} 0";

        $leads = array_merge($leads, AppUtil::getTexts("sphere_98021_progressCommand_2", array("%avatar%", "%shakuwa%", "%avatarName%"), array($avatarNo, $shakuwaNo, $avatarName)));

	      //シャクワ、ソフィアの前へ
        $shakuwapath = $this->getMap()->getThroughRoute(array(6,6), array(3,3), 'x');
        $leads[] = "UMOVE {$shakuwaNo} {$shakuwapath}";
        $leads[] = "UALGN {$shakuwaNo} 1";

        $leads[] = "ENVCG FF7A7A";
        $leads[] = "SEPLY se_arts1";
        $leads[] = "VIBRA 10";
        $leads[] = "DELAY 500";
        $leads[] = "VIBRA 00";
        $leads[] = "ENVCG FFFFFF";

        // ギミック起動
        $this->triggerGimmick($leads, 'sophia_die', $shakuwa);

        return;
  	}

    //-----------------------------------------------------------------------------------------------------
    /**
     * processSkipBattle()をオーバーライド。
     */
    protected function processSkipBattle(&$leads, $challenger, $defender) {

        // 戦闘を出来試合にする。
        if($challenger->getCode() == 'shakuwa'  &&  $defender->getCode() == 'sophia') {

            // バトルイベントを作成してキューへ。
            $battle = array();
            $battle['type'] = 'battle2';
            $battle['challenger'] = $challenger->getNo();
            $battle['defender'] = $defender->getNo();
            $battle['total']['challenger'] = 999;
            $battle['total']['defender'] =   999;
            $this->pushStateEvent($battle);

            // 基底の処理は行わない。
            return;
        }

        return parent::processSkipBattle($leads, $challenger, $defender);
    }

}
