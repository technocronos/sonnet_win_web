<?php

/**
 * 牧場の狼退治の特殊処理を記述する
 */
class SphereOn13002 extends SphereCommon {

    // 初めてクリアした後は「再起を誓う」を連続実行させる。
    protected $nextQuestId = 13003;

    // ミッション達成までの...
    const MISSION_ENEMIES = 6;      // 撃破数

    // ミッション達成時の報酬金。
    protected $missionReward = 300;


    //-----------------------------------------------------------------------------------------------------
    /**
     * initUnits() をオーバーライド。
     */
    protected function initUnits($roomName, &$roomInfo, $enterUnits, $reason) {

        // 初期配置されているユニットの思考ルーチンを "待機" にする。
        if( !empty($roomInfo['units']) ) {
            foreach($roomInfo['units'] as &$define)
                $define['act_brain'] = 'rest';
        }

        return parent::initUnits($roomName, $roomInfo, $enterUnits, $reason);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * progressTurnEnd() をオーバーライド。
     */
    protected function progressTurnEnd(&$leads) {

        // 主人公ユニットが移動を行っている場合のみ処理する。
        $commUnit = $this->getUnit();
        if($commUnit->getCode() == 'avatar'  &&  isset($this->state['command']['move'])) {

            // 移動後の座標を取得して、モンスターユニットを一つずつ見ていく。
            $commPos = $commUnit->getPos();
            foreach($this->units as $unit) {

                if($unit->getUnion() == 1)
                    continue;

                // マンハッタン距離が5より離れているものは無視。
                if($this->map->getManhattanDist($commPos, $unit->getPos()) > 5)
                    continue;

                // すでに待機でなくなっているものは無視。
                if($unit->getProperty('act_brain') != 'rest')
                    continue;

                // 行動パターンを待機から攻撃にチェンジ。
                $unit->changeBrain('generic');

                // それを示す指揮を追加。
                $leads[] = sprintf(AppUtil::getText("sphere_13002_wolf_notice"), $unit->getNo());
            }
        }

        // あとは基底の処理。
        return parent::progressTurnEnd($leads);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * processItem() をオーバーライド。
     */
    protected function processItem(&$leads, $use) {

        // とりあえず基底の処理。
        $ret = parent::processItem($leads, $use);

        // "area2" で小石が使われたら...
        if($this->state['current_room'] == 'area2'  &&  $use['uitem']['item_id'] == 2003) {

            // 農夫の息子のトラップの範囲。
            static $trap = array('pos'=>array(12,6), 'rb'=>array(14,8));

            // トラップに小石が投げこまれた場合。
            if( $this->map->isHit($use['to'], $trap) ) {

                // トラップの解説指揮とエフェクト
                $leads[] = 'IPRET ' . AppUtil::getText("sphere_13002_trap_name");
                $leads[] = 'DELAY 500';
                $leads[] = 'EFFEC shck 120613061406120713071407120813081408';

                // トラップの範囲にいるユニットすべてにトラップ効果を与える。
                // また、$this->state['x_hitTrap'] にトラップにかけた数を数える。
                // 主人公がかかった場合も一緒に数えちゃってるけど…まあいいや。
                $this->state['x_hitTrap'] = 0;
                $item = array('item_id'=>0, 'item_value'=>999, 'item_type'=>Item_MasterService::TACT_ATT);
                foreach($this->units as $unit) {
                    if( $this->map->isHit($unit->getPos(), $trap) ) {
                        $unit->exposeItem($leads, $item);
                        $this->state['x_hitTrap']++;
                    }
                }

                $leads[] = 'IPRET';

            // トラップの範囲でない場合
            }else {
                $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_13002_trap_error")));
            }
        }

        return $ret;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * fireGimmick() をオーバーライド。
     */
    protected function fireGimmick(&$leads, &$gimmick, $unit) {

        // "area2" において...
        if($this->state['current_room'] == 'area2') {

            // "bravo" ギミックで、農夫の息子に会う前にすべて倒してしまった場合は、台詞を差し替える。
            if($gimmick['name'] == 'bravo'  &&  empty($this->state['memory']['rescue']) ) {
                $gimmick['leads'] = array_merge($gimmick['leads'], $this->replaceEmbedCode(AppUtil::getTexts("sphere_13002_sons_surprise")));
            }

            // "area2_open_reverse" ギミック発動時、モンスターの思考ルーチンをすべてチェンジ。
            if($gimmick['name'] == 'area2_open_reverse') {
                foreach($this->units as $unit) {
                    if($unit->getUnion() == 2)
                        $unit->changeBrain('generic');
                }
            }

            // ミッションのカウントダウン用ギミックの場合は、残り敵数を表示する指揮を発行する。
            if($gimmick['type'] == 'x_countdown') {
                $remain = self::MISSION_ENEMIES - $this->state['termination'];
                if($remain <= 0) {
                    $leads[] = AppUtil::getText("sphere_13002_get_mission");
                    $this->state['gimmicks']['backdoor']['escape_result'] = 'success';
                }else {
                    $leads[] = sprintf(AppUtil::getText("sphere_13002_mission_notice"), $remain);
                }
            }
        }

        return parent::fireGimmick($leads, $gimmick, $unit);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * checkAchievement() をオーバーライド。
     */
    protected function checkAchievement($resultCode) {

        // "area2" からの脱出で、6体以上倒してるなら達成。
        return $this->state['termination'] >= 6
            && $this->state['current_room'] == 'area2';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * progressClose() をオーバーライド。
     */
    protected function progressClose(&$leads, $resultCode) {

        // 初めてのクリアなら、狼をトラップに巻き込んだ数をセット。
        if(!$this->state['cleared']  &&  $resultCode == Sphere_InfoService::SUCCESS) {

            Service::create('Flag_Log')->setValue(
                Flag_LogService::FLAG, $this->info['user_id'], 130020298,
                isset($this->state['x_hitTrap']) ? $this->state['x_hitTrap'] : 0
            );
        }

        return parent::progressClose($leads, $resultCode);
    }
}
