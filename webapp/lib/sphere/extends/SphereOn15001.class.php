<?php

/**
 * 故郷の村の特殊処理を記述する
 */
class SphereOn15001 extends SphereCommon {

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
       			//シショーに報告その２をクリアしている
            case "11005_cleared":
                return ((bool)Service::create('Flag_Log')->getValue(Flag_LogService::CLEAR, $this->info['user_id'], 11005) == (bool)$value);

            //海岸洞窟捜索活動クエストをクリアしているかどうか
            case "14003_cleared":
                return ((bool)Service::create('Flag_Log')->getValue(Flag_LogService::CLEAR, $this->info['user_id'], 14003) == (bool)$value);

            //愛、思い出してクエストをクリアしているかどうか
            case "15004_cleared":
                return ((bool)Service::create('Flag_Log')->getValue(Flag_LogService::CLEAR, $this->info['user_id'], 15004) == (bool)$value);

            // その他の条件は基底実行。
            default:
                return parent::judgeCondition($name, $value, $owner, $reason);
        }
    }
}
