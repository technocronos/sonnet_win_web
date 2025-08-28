<?php

/**
 * 流刑地の波止場の特殊処理を記述する
 */
class SphereOn51001 extends SphereCommon {

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

    /**
     * testCondition()をオーバーライド。カスタム条件を付けくわえる。
     */
    public function judgeCondition($name, $value, &$owner, $reason) {
        switch($name) {
            // 地下下水道クエストをクリアしている／いない
            case "gesui_cleared":

          			//もう地下下水道クエストをクリアしてるかどうか
                return ((bool)Service::create('Flag_Log')->getValue(Flag_LogService::CLEAR, $this->info['user_id'], 51002) == (bool)$value);

            // 夢魔クエストをクリアしている／いない
            case "muma_cleared":

          			//もう夢魔クエストをクリアしてるかどうか
                return ((bool)Service::create('Flag_Log')->getValue(Flag_LogService::CLEAR, $this->info['user_id'], 51004) == (bool)$value);

            // その他の条件は基底実行。
            default:
                return parent::judgeCondition($name, $value, $owner, $reason);
        }

    }
}
