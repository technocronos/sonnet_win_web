<?php

/**
 * 期間限定クエの特殊処理を記述する
 */
class SphereOn98010 extends SphereCommon {

    //-----------------------------------------------------------------------------------------------------
    /**
     * progressCommand()をオーバーライド。
     */
    public function progressCommand(&$leads) {

        //startルームのみ
        if($this->state["current_room"] != "start")
            return parent::progressCommand($leads);

        // コマンド対象になっているユニットを取得。
        $commUnit = $this->getUnit();

        // プレイヤーオーナーのユニットは基底処理
        if( $commUnit->getProperty('player_owner') ) {
            return parent::progressCommand($leads);
    		}

    		//それ以外は何もしない（フォーカスも当てない）
        $this->phaseNext($leads);
        return false;
  	}

}
