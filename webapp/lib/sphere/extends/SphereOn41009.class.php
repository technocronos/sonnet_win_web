<?php

/**
 * レジスタンスアジトの特殊処理を記述する
 */
class SphereOn41009 extends SphereCommon {

    // 初めてクリアした後は後劇を連続実行させる。
    protected $nextQuestId = 41007;

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
            // 霊子力研究所クエストをクリアしている／いない
            case "reishi_cleared":
          			//もう霊子力研究所クエストをクリアしてるかどうか
                return ((bool)Service::create('Flag_Log')->getValue(Flag_LogService::CLEAR, $this->info['user_id'], 31031) == (bool)$value);

            // その他の条件は基底実行。
            default:
                return parent::judgeCondition($name, $value, $owner, $reason);
        }

    }
}
