<?php

/**
 * コバイヤ火山の特殊処理を記述する
 */
class SphereOn21008 extends SphereCommon {

    // 初めてクリアした後は後劇を連続実行させる。
    //protected $nextQuestId = 31007;

    //-----------------------------------------------------------------------------------------------------
    /**
     * fireGimmick() をオーバーライド。
     */
    protected function fireGimmick(&$leads, &$gimmick, $unit) {

        // このクエの特殊イベントを処理する
        if($gimmick['type'] == 'unit_move') {
            //サラマンダー道をあける
            $saram = $this->getUnitByCode('saram');

            $route = $this->map->getThroughRoute($saram->getPos(), array(3,7), 'x');
            $leads[] = sprintf('UMOVE %03d %s', $saram->getNo(), $route);
            $saram->setPos(array(3,7));
        }

        // あとは、通常通り処理。
        return parent::fireGimmick($leads, $gimmick, $unit);

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * progressTurnEndをオーバーライド
     */
    public function progressTurnEnd(&$leads) {

        // コマンドと、コマンド対象になっているユニットを取得。
        $command = $this->state['command'];
        $commUnit = $this->getUnit();

        //プレイヤーの場合
        if($commUnit->getCode() == 'avatar' || $commUnit->getCode() == 'elena'){
            //マグマを踏んだ場合
            if($this->getGraph_no($command["move"]["to"]) == 400){
                $this->fireTrap($leads, $commUnit);
            }
        }

        return parent::progressTurnEnd($leads);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * そのポジションにあるマップチップのIDを得る
     */
    private function getGraph_no($pos){
        $structure = $this->map->getStructure();
        $maptips = $this->map->getMapTips();

        return $maptips[$structure[$pos[1]][$pos[0]]]["graph_no"];
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * トラップを作動させる
     */
    private function fireTrap(&$leads, $unit) {

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

        return true;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * replaceEmbedCode() をオーバーライド。
     */
    protected function replaceEmbedCode($leads) {

        $_leads = array();

        // 置き換え。
        foreach($leads as $lead) {

            // あとはコマンドごとに...
            switch( substr($lead, 0, 5) ) {

                case 'LINES':
                    $arr = explode(" ", $lead);
                    if($arr[1] == "%elena%" && !$this->getUnitByCode('elena')){
                        $lead = str_replace(array("LINES", "%elena%"), array("SPEAK", "%avatar% " . AppUtil::getText("text_log_body_-20100") . " "), $lead);
                    }

                    $_leads[] = $lead;
                    break;
                default:
                    $_leads[] = $lead;
            }

        }

        // あとは、通常通り処理。
        return parent::replaceEmbedCode($_leads);
    }

}
