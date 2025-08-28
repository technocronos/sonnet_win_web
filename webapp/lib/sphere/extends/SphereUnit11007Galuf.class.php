<?php

/**
 * 師匠討伐戦の師匠を処理するクラス。
 */
class SphereUnit11007Galuf extends SphereUnit {

    //-----------------------------------------------------------------------------------------------------
    /**
     * event() をオーバーライド。
     */
    public function event(&$leads, $event) {

        switch($event['name']) {

            // HP0で死んだ場合に...
            case 'exit':
                if($event['reason'] == 'collapse') {

                    // ターミネートの回数に応じてなら、それ用の処理を行う。
                    if(0 == $this->data['x_terminate_step'])
                        return $this->progressFirstTerminated($leads);
                    else
                        return $this->progressSecondTerminated($leads);
                }
                break;

            // ゴブリンを召還したあとの待機カウントが解けたら、それ用の処理をする。
            case 'x_set_out':
                $this->progressSetOut($leads);
                break;
        }

        return parent::event($leads, $event);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 最初に倒されたときの処理を行う。
     */
    private function progressFirstTerminated(&$leads) {

        $avatar = $this->sphere->getUnitByCode('avatar');
        $avatarNo = sprintf('%03d', $avatar->getNo());
        $galuf =  $this->sphere->getUnitByCode('galuf');
        $galufNo =  sprintf('%03d', $galuf->getNo());

        // 一度倒されたのを覚えておく。
        $this->data['x_terminate_step'] = 1;

        // まだクリアしていないなら...
        if( !$this->sphere->getStateProp('cleared') ) {

            // 師匠のセリフ。
            $leads = array_merge($leads, AppUtil::getTexts("sphere_11007_progressFirstTerminated_1", array("%avatar%", "%galuf%"), array($avatarNo, $galufNo)));

            // 師匠を障害物を超えて移動してから、さらにセリフ。
            $path = $this->sphere->getMap()->getThroughRoute($galuf->getPos(), array(13,9), 'y');
            $leads[] = "UMOVE {$galufNo} {$path}";

            $leads = array_merge($leads, AppUtil::getTexts("sphere_11007_progressFirstTerminated_2", array("%avatar%", "%galuf%"), array($avatarNo, $galufNo)));

        // クリア済みの場合。
        }else {

            // 師匠のセリフ。
            $leads = array_merge($leads, AppUtil::getTexts("sphere_11007_progressFirstTerminated_4", array("%avatar%", "%galuf%"), array($avatarNo, $galufNo)));

            // 師匠を障害物を超えて移動してから、さらにセリフ。
            $path = $this->sphere->getMap()->getThroughRoute($galuf->getPos(), array(13,9), 'y');
            $leads[] = "UMOVE {$galufNo} {$path}66666";
            $leads[] = "UALGN {$galufNo} 1";

            $leads = array_merge($leads, AppUtil::getTexts("sphere_11007_progressFirstTerminated_3", array("%avatar%", "%galuf%"), array($avatarNo, $galufNo)));

        }
        $this->setPos(18, 9);

        // 敵出現＆セリフ
        $this->sphere->triggerGimmick($leads, 'first_summon_1', $galuf);

        // HPを満タンに回復＆その場に待機するようにする＆アイテムを所持させる。
        $this->data['hp'] = $this->data['hp_max'];
        $leads[] = sprintf("UVALS {$galufNo} hp %05d", $this->data['hp']);
        $this->changeBrain('rest');
        $leads = array_merge($leads, $this->supplyItem(-1002));
        $leads = array_merge($leads, $this->supplyItem(-1002));
        $leads = array_merge($leads, $this->supplyItem(-3003));
        $leads = array_merge($leads, $this->supplyItem(-3003));

        // ギミック "derailment" を取り除く
        $this->sphere->removeGimmick('derailment');

        // ギミック "mojoh_anger", "second_summon_1", "set_out" 起動タイミングをセット。
        $this->sphere->modifyGimmick('mojoh_anger',   'rotation', $this->sphere->getStateProp('rotation') + 2);
        $this->sphere->modifyGimmick('second_summon', 'rotation', $this->sphere->getStateProp('rotation') + 4);
        $this->sphere->modifyGimmick('set_out',       'rotation', $this->sphere->getStateProp('rotation') + 6);

        return false;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 二回目に倒されたときの処理を行う。
     */
    private function progressSecondTerminated(&$leads) {

        // ギミック "finish" を起動する。
        $this->sphere->triggerGimmick($leads, 'finish', $this);

        return false;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ゴブリンを召還したあとの待機カウントが解けたときの処理を行う。
     */
    private function progressSetOut(&$leads) {

        $avatar = $this->sphere->getUnitByCode('avatar');
        $avatarNo = sprintf('%03d', $avatar->getNo());
        $galufNo = sprintf('%03d', $this->getNo());

        // プレイヤーキャラの名前を取得。
        $avatarName = $this->sphere->getUnitByCode('avatar')->getProperty('name');

        //まだgenericでない場合は移動してgeneric
        if($this->getProperty("act_brain") != "generic"){
            // 移動＆セリフ
            if( !$this->sphere->getStateProp('cleared') ) {
                $leads = array_merge($leads, AppUtil::getTexts("sphere_11007_progressSetOut_1", array("%avatar%", "%galuf%", "%avatarName%"), array($avatarNo, $galufNo, $avatarName)));

            }else {
                $leads[] = "UMOVE {$galufNo} 4444";

                $leads = array_merge($leads, AppUtil::getTexts("sphere_11007_progressSetOut_2", array("%avatar%", "%galuf%", "%avatarName%"), array($avatarNo, $galufNo, $avatarName)));
            }
            $this->setPos(14, 9);

            // 思考タイプ変更
            $this->changeBrain('generic');
            
        }else{
            if( !$this->sphere->getStateProp('cleared') ) {
                $leads = array_merge($leads, AppUtil::getTexts("sphere_11007_progressSetOut_3", array("%avatar%", "%galuf%", "%avatarName%"), array($avatarNo, $galufNo, $avatarName)));
            }else {
                $leads = array_merge($leads, AppUtil::getTexts("sphere_11007_progressSetOut_4", array("%avatar%", "%galuf%", "%avatarName%"), array($avatarNo, $galufNo, $avatarName)));
            }
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * defineBattleLines()をオーバーライド。
     */
    protected static function defineBattleLines($battleData) {

        // 戻り値初期化。
        $set = array();

        $set['death_match'] = array(
            'open' =>   AppUtil::getText("sphere_11007_defineBattleLines_death_match"),
        );

        $set['danger'] = array(
            'open' =>   AppUtil::getText("sphere_11007_defineBattleLines_danger"),
        );

        $set['snipe'] = array(
            'open' =>   AppUtil::getText("sphere_11007_defineBattleLines_snipe"),
        );

        $set['superior2'] = array(
            'open' =>   AppUtil::getText("sphere_11007_defineBattleLines_superior2_open"),
            'win' =>    AppUtil::getText("sphere_11007_defineBattleLines_superior2_win"),
        );

        $set['inferior2'] = array(
            'open' =>   AppUtil::getText("sphere_11007_defineBattleLines_inferior2_open"),
            'lose' =>   AppUtil::getText("sphere_11007_defineBattleLines_inferior2_lose"),
            'timeup' => AppUtil::getText("sphere_11007_defineBattleLines_inferior2_timeup"),
        );

        $set['default'] = array(
            'open' =>   AppUtil::getText("sphere_11007_defineBattleLines_default_open"),
            'win' =>    AppUtil::getText("sphere_11007_defineBattleLines_default_win"),
            'lose' =>   AppUtil::getText("sphere_11007_defineBattleLines_default_lose"),
            'draw' =>   AppUtil::getText("sphere_11007_defineBattleLines_default_draw"),
            'timeup' => AppUtil::getText("sphere_11007_defineBattleLines_default_timeup"),
        );

        // リターン。
        return $set;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getEffectScore() をオーバーライド。
     */
    protected function getEffectScore($uitem, $unit) {

        $score = parent::getEffectScore($uitem, $unit);

        // 自分以外への回復アイテム効果をプラス評価しない。
        if($score > 0  &&  $uitem['item_type'] == Item_MasterService::RECV_HP  &&  $unit !== $this)
            $score = 0;

        return $score;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * exposeItem() をオーバーライド。
     */
    public function exposeItem(&$leads, $item, $trigger = null) {

        // 先に基底の処理を行う。
        $ret = parent::exposeItem($leads, $item, $trigger);

        // 精霊の証を食らったら...
        if($item['item_id'] == 3998) {

            // まだHPが残っているなら、ギミック "mojoh_surprise" をあとで起動。
            if($this->data['hp'] > 0)
                $this->sphere->kickGimmick($leads, 'mojoh_surprise', $trigger);

            // rest を解く。
            $this->changeBrain('generic');
        }

        return $ret;
    }
}
