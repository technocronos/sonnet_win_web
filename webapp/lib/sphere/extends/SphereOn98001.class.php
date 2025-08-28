<?php

/**
 * 期間限定クエの特殊処理を記述する
 */
class SphereOn98001 extends SphereCommon {

    protected $nextQuestId = 98002;

    //-----------------------------------------------------------------------------------------------------
    /**
     * ルーム開始直後のタイミングで呼ばれる。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    protected function progressRoomOpen(&$leads) {

        // ダンジョン突入時、レベル調整が発動している場合は表示を行う。
        if($this->state['rotation_all'] == 0) {
            $transcend = $this->getTranscendLevel();
            if($transcend > 0){
                $leads[] = sprintf(AppUtil::getText("sphere_text_difficult_level"), $transcend);

                if(FieldBattle98001Util::DROP_ITEM != ""){
                    $item = Service::create('Item_Master')->getRecord(FieldBattle98001Util::DROP_ITEM);
                    if($item != NULL)
                        $leads[] = str_replace("{0}" , $item["item_name"] , AppUtil::getText("sphere_text_torihoudai"));
                }
            }
        }

        // 開始直後の位置が暗幕の場合は解除を行う。
        foreach($this->units as $unit) {
            if( $unit->getProperty('player_owner') ) {
                foreach($this->map->findCurtainOn($unit->getPos()) as $curtainName)
                    $this->openCurtain($leads, $curtainName, $unit);
            }
        }

        // 先頭のユニットへ。
        $this->phaseNext($leads);
        return false;
    }


}
