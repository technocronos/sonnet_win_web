<?php

/**
 * ポートモールの特殊処理を記述する
 */
class SphereOn31001 extends SphereCommon {

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

    //-----------------------------------------------------------------------------------------------------
    /**
     * fireGimmick() をオーバーライド。
     */
    protected function fireGimmick(&$leads, &$gimmick, $unit) {

        // このクエの特殊イベントを処理する
        if($gimmick['type'] == 'runaway') {
            //主人公押し売りから逃げる
            $avatar = $this->getUnitByCode('avatar');

            $route = $this->map->getThroughRoute($avatar->getPos(), array(16,11), 'x');
            $leads[] = sprintf('UMOVE %03d %s', $avatar->getNo(), $route);
            $avatar->setPos(array(16,11));
        }else if($gimmick['type'] == 'avatar_go'){
            //主人公助けに入る
            $avatar = $this->getUnitByCode('avatar');

            $route = $this->map->getThroughRoute($avatar->getPos(), array(19,5), 'x');
            $leads[] = sprintf('UMOVE %03d %s', $avatar->getNo(), $route);
            $avatar->setPos(array(19,5));
        }

        // あとは、通常通り処理。
        return parent::fireGimmick($leads, $gimmick, $unit);

    }


    /**
     * testCondition()をオーバーライド。カスタム条件を付けくわえる。
     */
    public function judgeCondition($name, $value, &$owner, $reason) {
        switch($name) {
            // 採石場の火の玉クエストをクリアしている／いない
            case "31003_cleared":

          			//もう採石場の火の玉クエストをクリアしてるかどうか
                return ((bool)Service::create('Flag_Log')->getValue(Flag_LogService::CLEAR, $this->info['user_id'], 31003) == (bool)$value);

            // その他の条件は基底実行。
            default:
                return parent::judgeCondition($name, $value, $owner, $reason);
        }

    }
}
