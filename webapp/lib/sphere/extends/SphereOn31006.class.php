<?php

/**
 * 列車の特殊処理を記述する
 */
class SphereOn31006 extends SphereCommon {

    // 初めてクリアした後は後劇を連続実行させる。
    protected $nextQuestId = 31007;

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
                'item_name'=> AppUtil::getText("TEXT_SENMETSU"), 
                'item_value'=>999, 
                'item_type'=>Item_MasterService::TACT_ATT,
                'item_vfx'=>3,
            );

            $this->fireItem($leads, $item, $target_unit->getPos(), $target_unit);

            // 無条件死亡
            $target_unit->collapse();

        }else if($gimmick['type'] == 'uncondition_explode') {
            //無条件自爆
            // 自爆アイテムを自分のいるポイントで起動。
            $item = Service::create('Item_Master')->needRecord(3997);
            $this->fireItem($leads, $item, $unit->getPos(), $unit);

            // 自分は死んだことにする。
            $unit->collapse();
        }


        // あとは、通常通り処理。
        return parent::fireGimmick($leads, $gimmick, $unit);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * progressTurnEndをオーバーライド
     */
    protected function rotateNext(&$leads) {

        // マグマの部屋に入ってるなら回数をカウントする
        if($this->state['memory']['magma_start']  &&  $this->getUnit()->getCode() == 'avatar')
            $this->actMagma($leads);

        return parent::rotateNext($leads);
    }

   //-----------------------------------------------------------------------------------------------------
    /**
     * マグマの広がりを処理する。
     */
    protected function actMagma(&$leads) {

         $this->state['memory']['magma_count']++;

         // 前のセリフ
         switch($this->state['memory']['magma_count']) {
             case 1:
                $this->changetips($leads, array(11, 01));
                $this->changetips($leads, array(11, 02));
                $this->changetips($leads, array(12, 02));
                break;
             case 2:
                $this->changetips($leads, array(10, 01));
                $this->changetips($leads, array(10, 02));
                $this->changetips($leads, array(10, 03));
                $this->changetips($leads, array(11, 03));
                $this->changetips($leads, array(12, 03));
                break;
             case 3:
                $this->changetips($leads, array(9, 01));
                $this->changetips($leads, array(9, 02));
                $this->changetips($leads, array(9, 03));
                $this->changetips($leads, array(9, 04));
                $this->changetips($leads, array(10, 04));
                $this->changetips($leads, array(11, 04));
                $this->changetips($leads, array(12, 04));
                break;
             case 4:
                $this->changetips($leads, array(8, 01));
                $this->changetips($leads, array(8, 02));
                $this->changetips($leads, array(8, 03));
                $this->changetips($leads, array(8, 04));
                $this->changetips($leads, array(8, 05));
                $this->changetips($leads, array(9, 05));
                $this->changetips($leads, array(10, 05));
                $this->changetips($leads, array(11, 05));
                $this->changetips($leads, array(12, 05));
                break;
             case 5:
                $this->changetips($leads, array(7, 01));
                $this->changetips($leads, array(7, 02));
                $this->changetips($leads, array(7, 03));
                $this->changetips($leads, array(7, 04));
                $this->changetips($leads, array(7, 05));
                $this->changetips($leads, array(7, 06));
                $this->changetips($leads, array(8, 06));
                $this->changetips($leads, array(9, 06));
                $this->changetips($leads, array(10, 06));
                $this->changetips($leads, array(11, 06));
                $this->changetips($leads, array(12, 06));
                break;
         }

    }

    //チップを変更、マグマに入ったら死亡ギミック設定、誰かいたら無条件爆破
    private function changetips(&$leads, $pos){

        $leads[] = 'DELAY 200';

        // マップチップをマグマに変更する。
        $leads[] = $this->map->changeSquare($pos, 400);

        //マグマにトラップをしかける
        $this->addGimmick('firetrap' . $pos[0] . "_" . $pos[1], array(
           'trigger'=>'all', 'pos'=>$pos, 'type'=>'trap2', 'lasting'=>'999', 'always'=>'1'
        ));

        //すでに誰かいたら爆破
        foreach($this->getUnits() as $unit) {
            $unitpos = $unit->getPos();
            if($unitpos[0] == $pos[0] && $unitpos[1] == $pos[1]){
                $gimmick = array('type'=>'trap2');
                $this->fireGimmick($leads, $gimmick, $unit);
            }
        }

        return true;
    }
}
