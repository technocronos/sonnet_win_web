<?php

/**
 * ツクール海岸の特殊処理を記述する
 */
class SphereOn14001 extends SphereCommon {

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
            //海岸洞窟捜索活動クエストをクリアしているかどうか
            case "14003_cleared":
                return ((bool)Service::create('Flag_Log')->getValue(Flag_LogService::CLEAR, $this->info['user_id'], 14003) == (bool)$value);

            //師匠討伐クエストをクリアしているかどうか
            case "11007_cleared":
                return ((bool)Service::create('Flag_Log')->getValue(Flag_LogService::CLEAR, $this->info['user_id'], 11007) == (bool)$value);

            // その他の条件は基底実行。
            default:
                return parent::judgeCondition($name, $value, $owner, $reason);
        }
    }
}
