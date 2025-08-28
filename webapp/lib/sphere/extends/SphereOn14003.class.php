<?php

/**
 * 海岸洞窟の特殊処理を記述する
 */
class SphereOn14003 extends SphereCommon {

    // 初めてクリアした後は「少年発見！」を連続実行させる。
    protected $nextQuestId = 14004;

    // ミッション達成までの...
    const MISSION_ENEMIES = 5;      // 撃破数

    // ミッション達成時の報酬金。
    protected $missionReward = 400;

    // ケケル君について...
    const KEKERU_RECOVER_ITEM = 1002;
    const KEKERU_ATTACK_ITEM = 2001;
    const KEKERU_RECOVER_COUNT = 3;
    const KEKERU_ATTACK_COUNT = 4;


    //-----------------------------------------------------------------------------------------------------
    /**
     * fireGimmickをオーバーライド。
     */
    protected function fireGimmick(&$leads, &$gimmick, $unit) {

        // "b2" でゴブリンを見つけたら、その次の主人公ユニットの行動で、"hit_fish" ギミックが
        // 起動するようにする。
        if($this->state['current_room'] == 'b2'  &&  $gimmick['name'] == 'find_goblin') {
            $this->state['gimmicks']['hit_fish']['rotation'] = $this->state['rotation'] + 2;

        // "b2" で "hit_fish" が起動した場合の処理。
        }else if($this->state['current_room'] == 'b2'  &&  $gimmick['name'] == 'hit_fish') {

            // ゴブリンに接近していないなら処理する。
            if( array_key_exists('approach_goblin', $this->state['gimmicks']) )
                $this->fireFishHitting($leads);

        // "b2" で "shoot_fish" が起動した場合、"fisher1", "fisher2" 以外が起動した場合は無視する。
        }else if($this->state['current_room'] == 'b2'  &&  $gimmick['name'] == 'shoot_fish') {

            if($unit->getCode() != 'fisher1'  &&  $unit->getCode() != 'fisher2')
                $gimmick['type'] = 'disabled';

        // "seaporch" で "sea_escape" が起動した場合の処理。
        }else if($this->state['current_room'] == 'seaporch'  &&  $gimmick['name'] == 'sea_escape') {

            // ケケル君を救出している場合は type:goal にする。
            if($this->state['memory']['kekeru_rescue'])
                $gimmick['type'] = 'goal';

        // "seaporch" で "vomit_enemy" が起動した場合の処理。
        }else if($this->state['current_room'] == 'seaporch'  &&  $gimmick['name'] == 'vomit_enemy') {

            // 前振りと、出現ポイントをセット。
            $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_14003_seaeater_vomit")));
            $gimmick['unit']['pos'] = $this->getUnitByCode('seaeater')->getPos();
        }

        // type:stream を独自に処理する。
        if($gimmick['type'] == 'stream') {
            $this->fireStream($leads, $gimmick);
            return false;
        }else {
            return parent::fireGimmick($leads, $gimmick, $unit);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ゴブリンの魚釣りの "ヒット" を処理する。
     */
    protected function fireFishHitting(&$leads) {

        // 関連ユニットを取得。
        $avatar = $this->getUnitByCode('avatar');
        $fish = $this->getUnitByCode('fish');
        $fisher1 = $this->getUnitByCode('fisher1');
        $fisher2 = $this->getUnitByCode('fisher2');

        // いずれかのユニットが欠けていたら起動しない。そんなはずはないけど...
        if(!$avatar || !$fish || !$fisher1)
            return;

        // セリフ
        $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_14003_avatar_speak1")));

        // 魚をゴブリンのもとへ強制移動。
        $route = $this->map->getThroughRoute($fish->getPos(), array(7,6), 'x');
        $leads[] = sprintf('UMOVE %03d %s', $fish->getNo(), $route);
        $fish->setPos(array(7,6));

        // ゴブリンのセリフ
        $leads = array_merge($leads, AppUtil::getTexts("sphere_14003_goblin_speak1", "%03d", sprintf("%03d", $fisher1->getNo())));

        // 魚の移動力を0に。ルーチンを "target" に。
        $fish->setProperty('move_pow', 0);
        $fish->changeBrain('target');

        // ゴブリンのルーチンを "target" に。
        $fisher1->changeBrain('target');
        if($fisher2)
            $fisher2->changeBrain('target');
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * type:streamのギミックを処理する。
     */
    protected function fireStream(&$leads, $gimmick) {

        $tip = 1720;

        // 主人公ユニットを取得。
        $avatar = $this->getUnitByCode('avatar');

        // ストリームの先端を初期化。
        $head = $gimmick['begin'];
        $leads[] = sprintf('RPBG1 %02d %02d %04d', $head[0], $head[1], $this->map->getSwfTipNo($tip));

        // 主人公ユニットを運んでいるかどうかのフラグを初期化。
        $carry = false;

        // ストリーム経路を一つずつ処理していく。
        for($i = 0 ; $i < strlen($gimmick['path']) ; $i++) {

            // まだ主人公を運んでない場合...
            if(!$carry) {

                // ストリームの先端が主人公と同じ場所になったなら、運ぶようにする。
                if($avatar->getPos() == $head) {
                    $carry = true;
                    if($gimmick['on_carry'])
                        $leads = array_merge($leads, $this->replaceEmbedCode($gimmick['on_carry']));
                }
            }

            // 経路から次の方向を取得。
            $dir = $gimmick['path']{$i};

            // 先端を進める。
            $head[0] += ($dir-5) % 3;
            $head[1] += (int)(($dir-5) / 3);

            // 進めた箇所のマップチップを変更する。
            $leads[] = 'DELAY 200';
            $leads[] = sprintf('RPBG1 %02d %02d %04d', $head[0], $head[1], $this->map->getSwfTipNo($tip));

            // 主人公を運んでいるなら、一緒に動かす。
            if($carry)
                $leads[] = sprintf('UMOVE %03d %s', $avatar->getNo(), $dir);
        }

        // 最後に主人公ユニットの位置を更新して、ギミックチェックする。
        if($carry) {
            $avatar->setPos($head);
            $this->checkGimmickByUnit($avatar);
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * progressTurnEndをオーバーライド
     */
    protected function progressTurnEnd(&$leads) {

        // ケケル君を救助している状態で主人公ユニットのターンが終わろうとしているならケケル君の行動を
        // 処理する。
        if($this->state['memory']['kekeru_rescue']  &&  $this->getUnit()->getCode() == 'avatar')
            $this->actKekeru($leads);

        return parent::progressTurnEnd($leads);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * ケケル君の行動を処理する。
     */
    protected function actKekeru(&$leads) {

        // 行動を決める。
        $action = $this->brainKekeru();

        // 行動の種類ごとに処理する。
        $avatar = $this->getUnit();
        switch($action['type']) {

            // 回復。
            case 'recover':

                // 前のセリフ
                switch($this->state['x_kekeru_recover']) {
                    case 0:
                        $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_14003_kekeru_speak1")));
                        break;
                    case 1:
                        $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_14003_kekeru_speak2")));
                        break;
                    case 2:
                        $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_14003_kekeru_speak3")));
                        break;
                }

                // 回復
                $item = Service::create('Item_Master')->needRecord(self::KEKERU_RECOVER_ITEM);
                $this->fireItem($leads, $item, $avatar->getPos());

                // 後のセリフ
                switch($this->state['x_kekeru_recover']) {
                    case 0:
                        $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_14003_kekeru_speak4")));
                        break;
                }

                // 回数カウント。
                $this->state['x_kekeru_recover']++;
                break;

            // 攻撃。
            case 'attack':

                // 前のセリフ
                switch($this->state['x_kekeru_attack']) {
                    case 0:
                        $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_14003_kekeru_attack1")));
                        break;
                    case 1:
                        $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_14003_kekeru_attack2")));
                        break;
                    case 2:
                        $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_14003_kekeru_attack3")));
                        break;
                    case 3:
                        $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_14003_kekeru_attack4")));
                        break;
                }

                // 攻撃
                $item = Service::create('Item_Master')->needRecord(self::KEKERU_ATTACK_ITEM);
                $this->fireItem($leads, $item, $action['target']->getPos());

                // 後のセリフ
                switch($this->state['x_kekeru_attack']) {
                    case 0:
                        $leads = array_merge($leads, $this->replaceEmbedCode(AppUtil::getTexts("sphere_14003_kekeru_speak5")));
                        break;
                }

                // 回数カウント。
                $this->state['x_kekeru_attack']++;
                break;
        }
    }


    /**
     * ケケル君の行動を決定する。
     *
     * @return array    次のキーを含む配列。
     *                      type    "recover":回復、"attack":攻撃
     *                      target  攻撃の場合に、対象のユニット
     *                  何もしない場合はカラの配列。
     */
    protected function brainKekeru() {

        $avatar = $this->getUnit();

        // まだくすりびんを持っているなら...
        if($this->state['x_kekeru_recover'] < self::KEKERU_RECOVER_COUNT) {

            // 主人公のHPが65%を切っているなら回復をする。
            if($avatar->getProperty('hp') / $avatar->getProperty('hp_max') <= 0.65)
                return array('type'=>'recover');
        }

        // まだ爆弾を持っているなら...
        if($this->state['x_kekeru_attack'] < self::KEKERU_ATTACK_COUNT) {

            // ユニットマップを取得。
            $unitMap = $this->map->getUnitMap();

            // 距離1～3まで、順次見ていく。
            for($dist = 1 ; $dist <= 3 ; $dist++) {

                // マスを取得して一つずつ見ていく。
                $poses = $this->map->getNeighbors($avatar->getPos(), $dist);
                foreach($poses as $pos) {

                    // その場所にいるユニットを取得。
                    $unit = $unitMap[ $pos[1] ][ $pos[0] ];

                    // 敵ユニットがいたなら、攻撃する。
                    if($unit  &&  $unit->getUnion() != 1)
                        return array('type'=>'attack', 'target'=>$unit);
                }
            }
        }

        // ここまできたら何もしない。
        return array();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * processSkipBattle()をオーバーライド。
     */
    protected function processSkipBattle(&$leads, $challenger, $defender) {

        // "b2" での特定の戦闘を出来試合にする。
        if($this->state['current_room'] == 'b2') {

            // 魚釣り関連のユニットのコードを列挙。
            $fishers = array('fisher1', 'fisher2', 'fish');

            // 双方とも魚つり関連なら処理する。
            if( in_array($challenger->getCode(), $fishers)  &&  in_array($defender->getCode(), $fishers) ) {

                // それぞれが与えるダメージを定義
                $damages = array('fisher1'=>35, 'fisher2'=>28, 'fish'=>42);

                // バトルイベントを作成してキューへ。
                $battle = array();
                $battle['type'] = 'battle2';
                $battle['challenger'] = $challenger->getNo();
                $battle['defender'] = $defender->getNo();
                $battle['total']['challenger'] = $damages[ $defender->getCode() ];
                $battle['total']['defender'] = $damages[ $challenger->getCode() ];
                $this->pushStateEvent($battle);

                // ギミック "fish_great" を起動する。
                $this->pushStateEvent(array(
                    'type' => 'gimmick',
                    'name' => 'fish_great',
                    'trigger' => $challenger->getNo(),
                ));

                // 基底の処理は行わない。
                return;
            }

        // ミッションでの、特定の戦闘を出来試合に。
        }else if($this->state['current_room'] == 'fishpond2') {

            // どちらかが釣り上げた魚なら処理する。
            if($challenger->getProperty('x_quarry')  ||  $defender->getProperty('x_quarry')) {

                // バトルイベントを作成してキューへ。
                $battle = array();
                $battle['type'] = 'battle2';
                $battle['challenger'] = $challenger->getNo();
                $battle['defender'] = $defender->getNo();
                $battle['total']['challenger'] = $challenger->getProperty('x_quarry') ? 50 : $defender->getProperty('hp');
                $battle['total']['defender'] =   $defender->getProperty('x_quarry') ? 50 : $challenger->getProperty('hp');
                $this->pushStateEvent($battle);

                // 基底の処理は行わない。
                return;
            }
        }

        return parent::processSkipBattle($leads, $challenger, $defender);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * rotateNext() をオーバーライド。
     */
    protected function rotateNext(&$leads) {

        // ミッション用の部屋なら...
        if($this->state['current_room'] == 'fishpond2') {

            // 魚の釣り上げと催促を処理する。
            $this->processMissionFishing($leads);

            // 魚の補充を処理する。
            $this->processFishAppearance($leads);
        }

        parent::rotateNext($leads);
    }

    /**
     * 魚の釣り上げと催促を処理する。
     */
    protected function processMissionFishing(&$leads) {

        // 釣り上げたあとの移動座標。キルサイト。
        $KILL_SIGHT = array(3,12);

        // "fisher1" がすでにいないなら処理しない。
        $fisher1 = $this->getUnitByCode('fisher1');
        if(!$fisher1)
            return;

        // 現在、キルサイトにすでに魚がいるなら処理しない。
        foreach($this->units as $unit) {
            if( $unit->getUnion() == 2  &&  $unit->getPos() == $KILL_SIGHT )
                return;
        }

        // ユニットを一つずつみていく。
        foreach($this->units as $unit) {

            // 魚以外は無視。
            if($unit->getUnion() != 2)
                continue;

            // 釣り上げ範囲に入っていないなら無視。
            if( !SphereMap::isHit( $unit->getPos(), array('pos'=>array(5,13), 'rb'=>array(6,15)) ) )
                continue;

            // ここまで来たなら釣り上げる。

            // ゴブリン前説。
            if($this->state['x_mission_count'] >= self::MISSION_ENEMIES) {
                $leads[] = sprintf("UALGN %03d 2", $fisher1->getNo());
                $leads = array_merge($leads, AppUtil::getTexts("sphere_14003_goblin_arrogance", "%03d", sprintf("%03d", $fisher1->getNo())));
            }else {
                $leads = array_merge($leads, AppUtil::getTexts("sphere_14003_goblin_get_fish", "%03d", sprintf("%03d", $fisher1->getNo())));
            }

            // 魚をキルサイトに強制移動。
            $route = $this->map->getThroughRoute($unit->getPos(), $KILL_SIGHT, 'y');
            $leads[] = sprintf('UMOVE %03d %s', $unit->getNo(), $route);
            $unit->setPos($KILL_SIGHT);

            // 魚の移動力を0に。ルーチンを "generic" に。
            if($unit->getCode() != 'seaeater') {
                $unit->setProperty('move_pow', 0);
                $unit->changeBrain('generic');
                $unit->setProperty('x_quarry', true);

            // シーイーター釣り上げちゃった場合。
            }else {

                $leads = array_merge($leads, AppUtil::getTexts("sphere_14003_seaeater_speak", "%03d", sprintf("%03d", $unit->getNo())));
                $leads = array_merge($leads, AppUtil::getTexts("sphere_14003_goblin_confuse", "%03d", sprintf("%03d", $fisher1->getNo())));

                $this->state['x_seaeater_hit'] = true;
            }

            // イライラカウントをリセット。
            $this->state['x_irritate_count'] = 0;

            break;
        }

        // ここまで来たならヒットなし。
        // まだミッション達成していないなら、イライラカウントを+1。2以上なら爆弾発射。
        if( $this->state['x_mission_count'] < self::MISSION_ENEMIES  &&  ++$this->state['x_irritate_count'] >= 3 ) {

            $leads[] = sprintf("UALGN %03d 2", $fisher1->getNo());
            $leads = array_merge($leads, AppUtil::getTexts("sphere_14003_goblin_angly_faster", "%03d", sprintf("%03d", $fisher1->getNo())));

            $avatar = $this->getUnitByCode('avatar');
            $item = Service::create('Item_Master')->needRecord(2001);
            $this->fireItem($leads, $item, $avatar->getPos(), $fisher1);
        }
    }

    /**
     * 魚の補充を処理する。
     */
    protected function processFishAppearance(&$leads) {

        // 補充タイマーを管理。
        $this->state['x_fish_supply_timer']--;
        if($this->state['x_fish_supply_timer'] < 0)  $this->state['x_fish_supply_timer'] = 0;

        // 補充タイミングが来ていないなら補充しない。
        if($this->state['x_fish_supply_timer'] > 0)
            return;

        // 魚の数を数える。4匹以上いるなら補充しない。
        $count = 0;
        foreach($this->units as $unit) {
            if($unit->getUnion() == 2) {
                if(++$count >= 4)
                    return;
            }
        }

        // 4匹未満なら補充。
        $this->pushStateEvent( array('type'=>'gimmick', 'name'=>'fish_supply') );
        $this->state['x_fish_supply_timer'] = 2;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * removeUnit() をオーバーライド。
     */
    public function removeUnit(&$leads, $unitNo, $reason, $triggerUnit = null) {

        // 基底の処理。
        $exitUnit = parent::removeUnit($leads, $unitNo, $reason, $triggerUnit);

        // ミッション用の部屋ならで、敵ユニットが死んだ場合...
        if($this->state['current_room'] == 'fishpond2'  &&  $exitUnit->getUnion() != 1) {

            // "fisher1" を取得。すでにいないなら処理しない。
            $fisher1 = $this->getUnitByCode('fisher1');
            if(!$fisher1)
                return $exitUnit;

            // 主人公ユニットを取得。すでにいないなら処理しない。
            $avatar = $this->getUnitByCode('avatar');
            if(!$avatar)
                return $exitUnit;

            // まだシーイーターを釣ってなくて、それがゴブリンの場合は、爆弾発射。
            if(!$this->state['x_seaeater_hit']  &&  $exitUnit->getUnion() == 3) {

                $leads[] = sprintf("UALGN %03d 2", $fisher1->getNo());
                $leads = array_merge($leads, AppUtil::getTexts("sphere_14003_goblin_angly_attacked", "%03d", sprintf("%03d", $fisher1->getNo())));

                $avatar = $this->getUnitByCode('avatar');
                $item = Service::create('Item_Master')->needRecord(2001);
                $this->fireItem($leads, $item, $avatar->getPos(), $fisher1);

            // それがキルサイトに上げられた魚の場合は、カウントアップする。
            }else if( $exitUnit->getProperty('x_quarry') ) {

                // カウントアップ
                $this->state['x_mission_count']++;

                // 6匹目未満の場合、ゴブリンに解説を出させる。
                if($this->state['x_mission_count'] < self::MISSION_ENEMIES) {
                    $leads[] = sprintf(AppUtil::getText("sphere_14003_goblin_nextturn"), $fisher1->getNo(), self::MISSION_ENEMIES - $this->state['x_mission_count']);

                // 6匹目の場合...
                }else if($this->state['x_mission_count'] == self::MISSION_ENEMIES) {

                    // ゴブリンの解説。
                    $leads[] = sprintf("UALGN %03d 2", $fisher1->getNo());
                    $leads = array_merge($leads, AppUtil::getTexts("sphere_14003_goblin_speak_ok", "%03d", sprintf("%03d", $fisher1->getNo())));

                    // 爆弾
                    $item = Service::create('Item_Master')->needRecord(2001);
                    $this->fireItem($leads, $item, $avatar->getPos(), $fisher1);

                    // ゴブリン笑い
                    $leads = array_merge($leads, AppUtil::getTexts("sphere_14003_goblin_laugh", "%03d", sprintf("%03d", $fisher1->getNo())));

                    // シーイーター出現
                    $this->pushStateEvent(array('type'=>'gimmick', 'name'=>'avatar_angry'));
                }
            }
        }

        return $exitUnit;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * checkAchievement() をオーバーライド。
     */
    protected function checkAchievement($resultCode) {

        return $this->state['x_mission_count'] >= self::MISSION_ENEMIES;
    }
}
