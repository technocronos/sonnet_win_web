<?php

/**
 * ハロウィンの洞窟の特殊処理を記述する
 */
class SphereOn98051 extends SphereCommon {

    // 初めてクリアした後は後劇を連続実行させる。
    //protected $nextQuestId = 31007;

    //-----------------------------------------------------------------------------------------------------
    /**
     * fireGimmick() をオーバーライド。
     */
    protected function fireGimmick(&$leads, &$gimmick, $unit) {

        // trapの場合はﾄﾗｯﾌﾟを起動する
        if($gimmick['type'] == 'trap') {

            // ユーザの初回突入レベルを取得。
            $firstLevel = Service::create('Flag_Log')->getValue(Flag_LogService::FIRST_TRY, $this->info['user_id'], $this->info['quest_id']);

            //ユーザの初回突入レベル分消費させる
            $damage = $firstLevel;

            // ﾄﾗｯﾌﾟアイテムを自分のいるポイントで起動。
            $item = array(
                'item_id'=>0, 
                'item_name'=>AppUtil::getText("TEXT_MINE"), 
                'item_value'=>$damage, 
                'item_type'=>Item_MasterService::TACT_ATT,
                'item_vfx'=>3,
            );

            $this->fireItem($leads, $item, $unit->getPos(), $unit);

        }else if($gimmick['type'] == 'trap2') {

            // ﾄﾗｯﾌﾟアイテムを自分のいるポイントで起動。
            $item = array(
                'item_id'=>0, 
                'item_name'=>AppUtil::getText("TEXT_MAGMA"), 
                'item_value'=>999, 
                'item_type'=>Item_MasterService::TACT_ATT,
                'item_vfx'=>1,
            );

            $this->fireItem($leads, $item, $unit->getPos(), $unit);

            // 無条件死亡
            $unit->collapse();
        }else if($gimmick['type'] == 'marutinator_fire') {

            $target_unit = $this->getUnitByCode($gimmick['target_unit']);

            // ﾄﾗｯﾌﾟアイテムを自分のいるポイントで起動。
            $item = array(
                'item_id'=>0, 
                'item_name'=>AppUtil::getText("TEXT_SENMETSU"), 
                'item_value'=>999, 
                'item_type'=>Item_MasterService::TACT_ATT,
                'item_vfx'=>3,
            );

            $this->fireItem($leads, $item, $target_unit->getPos(), $target_unit);

            // 無条件死亡
            $target_unit->collapse();

        }else if($gimmick['type'] == 'uncondition_explode') {
            //無条件自爆
            $target_unit = $this->getUnitByCode("tamane");

            // 自爆アイテムを自分のいるポイントで起動。
            $item = Service::create('Item_Master')->needRecord(3997);
            $this->fireItem($leads, $item, $target_unit->getPos(), $target_unit);

            // 自分は死んだことにする。
            $target_unit->collapse();
        }


        // あとは、通常通り処理。
        return parent::fireGimmick($leads, $gimmick, $unit);
    }
}
