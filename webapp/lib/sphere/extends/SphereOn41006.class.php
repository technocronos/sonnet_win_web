<?php

/**
 * 手伝いの特殊処理を記述する
 */
class SphereOn41006 extends SphereCommon {

    // 初めてクリアした後は後劇を連続実行させる。
    //protected $nextQuestId = 41007;

    const DESC_TIP_NO = 517;      // 机のチップ番号
    const SAND_TIP_NO = 2;      // 砂漠のチップ番号
    const DESC_ITEM_ID = -2999;      // 机のユーザーアイテムID
    const ALL_DESC_COUNT = 12;      // 置く机の数

    //progressTurnEndをオーバーライド
    public function progressTurnEnd(&$leads) {

        // コマンドと、コマンド対象になっているユニットを取得。
        $command = $this->state['command'];
        $commUnit = $this->getUnit();

        //startルームのみ
        if($this->state["current_room"] == "start"){

            //もう机運びをクリアしてるかどうか
            $flag = Service::create('Flag_Log')->getValue(Flag_LogService::FLAG, $this->info['user_id'], 410060001);

            //主人公の場合
            if($commUnit->getCode() == 'avatar' && !$flag){
                $pos = $command["move"]["to"];
                if($this->getGraph_no($pos) == self::DESC_TIP_NO){
                    //すでに正解の位置に置かれてる場合
                    if($pos[0] >= 4 && $pos[0] <=9 && $pos[1] >= 4 && $pos[1] <= 5){
                        //何もしない
                    }else{
                        $itemtable = $this->getItemTable();
                        $data = $commUnit->getData();

                        if(in_array(self::DESC_ITEM_ID,$data["items"])){
                            $avatarNo = sprintf('%03d', $commUnit->getNo());
                            $gebal =  $this->getUnitByCode('gebal');
                            $gebalNo =  sprintf('%03d', $gebal->getNo());

                            $leads = array_merge($leads, AppUtil::getTexts("sphere_41004_progressTurnEnd_1", array("%avatar%", "%gebal%"), array($avatarNo, $gebalNo)));

                        }else{
                            //机を踏んだ（持った）
                            $this->getDesc($leads, $commUnit);
                        }
                    }
                }
            }
        }else{
            //子蜘蛛の場合
            if($commUnit->getProperty("character_id") == -10053){

                //攻撃しかけた後はネットを張らない
                if(!$command["attack"]){
                    //移動の場合
                    //pathは携帯のテンキーで表現されている　例：4488→左左下下
                    if($command["move"]["path"]){
                        $lastmove = substr($command["move"]["path"], -1);    // 最後に移動した方向を返す
                        $x = $command["move"]["to"][0];
                        $y = $command["move"]["to"][1];

                        switch($lastmove){
                            case 2:
                                $this->changetips($leads, array($x, $y-1));
                                break;
                            case 4:
                                $this->changetips($leads, array($x-1, $y));
                                break;
                            case 6:
                                $this->changetips($leads, array($x+1, $y));
                                break;
                            case 8:
                                $this->changetips($leads, array($x, $y+1));
                                break;
                        }
                    }else{
                        //動かない場合、どこかしらにネットを貼る
                        $pos = $commUnit->getPos();
                        $x = $pos[0];
                        $y = $pos[1];

                        if($this->getGraph_no(array($x, $y+1)) == 2)
                            $this->changetips($leads, array($x, $y+1));
                        else if($this->getGraph_no(array($x, $y-1)) == 2)
                            $this->changetips($leads, array($x, $y-1));
                        else if($this->getGraph_no(array($x+1, $y)) == 2)
                            $this->changetips($leads, array($x+1, $y));
                        else if($this->getGraph_no(array($x-1, $y)) == 2)
                            $this->changetips($leads, array($x-1, $y));

                    }
                }
            }else{
            //その他の場合
                if($this->getGraph_no($command["move"]["to"]) == 515){
                    //蜘蛛の巣を踏んだ
                    $this->fireTrap($leads, $commUnit);
                }else if($this->getGraph_no($command["move"]["to"]) == 516){
                    //穴に入った
                    $this->fireWarp($leads, $commUnit);
                }
            }
        }
        return parent::progressTurnEnd($leads);
    }

    //progressPreCommをオーバーライド
    public function progressPreComm(&$leads) {
        //startルームのみ
        if($this->state["current_room"] != "start")
            return parent::progressPreComm($leads);

        //ようするに何がやりたいかというとドラマから戻って来て最初に主人公にフォーカスが当たる前にギミックを起動させたい
        if(self::ALL_DESC_COUNT <= $this->state['memory']['put_count']){
            //ゲームが終わっていて主人公のターン
            $commUnit = $this->getUnit();

            // プレイヤーオーナーのユニットは基底処理
            if( $commUnit->getProperty('player_owner') ) {
                $pos = $commUnit->getPos();
                if($pos[0] == 7 && $pos[1] == 4){
                    //指定の位置にいる
                    $this->kickGimmick($leads, 'layla', $commUnit, false);
                }
            }
        }

        return parent::progressPreComm($leads);
    }

    //チップを変更
    private function changetips(&$leads, $pos){

        //チップ番号2（土）以外はネットを張らない
        if($this->getGraph_no($pos) != 2)
            return false;

        $leads[] = "NOTIF " . AppUtil::getText("TEXT_SPIDER_NET");
        $leads[] = 'DELAY 200';

        // マップチップを蜘蛛の巣に変更する。
        $leads[] = $this->map->changeSquare($pos, 515);
        $leads[] = 'DELAY 200';

        return true;
    }

    //そのポジションにあるマップチップのIDを得る
    private function getGraph_no($pos){
        $structure = $this->map->getStructure();
        $maptips = $this->map->getMapTips();

        return $maptips[$structure[$pos[1]][$pos[0]]]["graph_no"];
    }

    //机をゲット
    private function getDesc(&$leads, $unit) {

        // ace_cardギミック作成
        $gimmick = array(
            'type'=>"ace_card", 
            'user_item_id'=>self::DESC_ITEM_ID,
            'treasure_catcher'=>"avatar",
        );

        //ace_cardギミック
        $this->fireGimmick($leads, $gimmick, $unit);

        //現在の位置のチップ変更
        $pos = $unit->getPos();
        $x = $pos[0];
        $y = $pos[1];

        $leads[] = $this->map->changeSquare(array($x, $y), self::SAND_TIP_NO);

        $avatarNo = sprintf('%03d', $unit->getNo());
        $gebal =  $this->getUnitByCode('gebal');
        $gebalNo =  sprintf('%03d', $gebal->getNo());

        $leads = array_merge($leads, AppUtil::getTexts("sphere_41004_getDesc_1", array("%avatar%", "%gebal%"), array($avatarNo, $gebalNo)));

        return true;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * アイテムの使用を処理する。
     *
     * @param reference     スフィアに起きた出来事をあらわす指揮内容を格納する配列。
     * @param array         ユニットコマンドの "use" キーの値。
     * @return bool         SWFへのリターンが発生したかどうか。
     */
    public function processItem(&$leads, $use) {

        // アイテム使用の効果を処理する。
        $leads[] = sprintf('FOCUS %03d', $this->getUnit()->getNo());
        $ret = $this->fireItem($leads, $use['uitem'], $use['to'], $this->getUnit());

        if(!$ret)
            return false;

        // 装備でなくアイテムの場合は消費する。
        // ユニットデータから該当のアイテムを消去。それをSWFに伝えるための指揮を追加。
        if($use['page'] == 'item')
            $leads[] = $this->getUnit()->lostItem($use['slot']);

        return false;
    }

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
     * fireItem()をオーバーライド。
     */
    public function fireItem(&$leads, $item, $to, $firer = null) {

        // ステージ台の場合
        if($item['item_id'] == self::DESC_ITEM_ID * -1){
            $avatarNo = sprintf('%03d', $firer->getNo());
            $gebal =  $this->getUnitByCode('gebal');
            $gebalNo =  sprintf('%03d', $gebal->getNo());

            $pos = $firer->getPos();

            //正解の位置に置かれてる場合
            if($pos[0] >= 4 && $pos[0] <=9 && $pos[1] >= 4 && $pos[1] <= 5){

                if($this->getGraph_no($pos) == self::DESC_TIP_NO){
                    //すでに机が置いてある

                    $leads = array_merge($leads, AppUtil::getTexts("sphere_41004_fireItem_1", array("%avatar%", "%gebal%"), array($avatarNo, $gebalNo)));

                    return false;
                }else{
                    // マップチップをｽﾃｰｼﾞ台に変更する。
                    $leads[] = $this->map->changeSquare($pos, self::DESC_TIP_NO);

                    $leads = array_merge($leads, AppUtil::getTexts("sphere_41004_fireItem_2", array("%avatar%", "%gebal%"), array($avatarNo, $gebalNo)));

                    $this->state['memory']['put_count']++;

                    if(self::ALL_DESC_COUNT > $this->state['memory']['put_count']){
                        $leads = array_merge($leads, AppUtil::getTexts("sphere_41004_fireItem_3", array("{0}"), array((self::ALL_DESC_COUNT - $this->state['memory']['put_count']))));
                    }else{
                        if($pos[0] == 6 && $pos[1] == 5)
                            $movepos = array(7,5);
                        else
                            $movepos = array(6,5);

                        $path = $this->getMap()->getThroughRoute($gebal->getPos(), $movepos, 'y');
                        
                        $leads = array_merge($leads, AppUtil::getTexts("sphere_41004_fireItem_4", array("%avatar%", "%gebal%"), array($avatarNo, $gebalNo)));

                        $leads[] = "UMOVE {$gebalNo} {$path}";
                        $gebal->setPos($movepos[0], $movepos[1]);

                        $leads = array_merge($leads, AppUtil::getTexts("sphere_41004_fireItem_5", array("%avatar%", "%gebal%"), array($avatarNo, $gebalNo)));

                        //みんな集合
                        $this->triggerGimmick($leads, 'audience1', $gebal);

                        $leads = array_merge($leads, AppUtil::getTexts("sphere_41004_fireItem_6", array("%avatar%", "%gebal%"), array($avatarNo, $gebalNo)));

                        $this->triggerGimmick($leads, 'audience7', $gebal);

                        $leads = array_merge($leads, AppUtil::getTexts("sphere_41004_fireItem_7", array("%avatar%", "%gebal%"), array($avatarNo, $gebalNo)));

                        if($pos[0] != 7 || $pos[1] != 4){
                            $leads = array_merge($leads, AppUtil::getTexts("sphere_41004_fireItem_8", array("%avatar%", "%gebal%"), array($avatarNo, $gebalNo)));

                            $path = $this->getMap()->getThroughRoute($firer->getPos(), array(7,4), 'y');
                            $leads[] = "UMOVE {$avatarNo} {$path}";
                            $firer->setPos(7, 4);
                            
                        }

                        $this->kickGimmick($leads, 'drama1', $firer, true);
                        //$this->kickGimmick($leads, 'gebaru_speach', $firer, true);

                    }

                    // 基底の処理はスキップ。
                    //$ret = parent::fireItem($leads, $item, $to, $firer);
                    return true;
                }

            }else{
                $leads = array_merge($leads, AppUtil::getTexts("sphere_41004_fireItem_9", array("%avatar%", "%gebal%"), array($avatarNo, $gebalNo)));

                return false;
            }
        }else{
            // 基底の処理。
            parent::fireItem($leads, $item, $to, $firer);
            return true;
        }

    }


    //トラップを作動させる
    private function fireTrap(&$leads, $unit) {

        // ユーザの初回突入レベルを取得。
        $firstLevel = Service::create('Flag_Log')->getValue(Flag_LogService::FIRST_TRY, $this->info['user_id'], $this->info['quest_id']);

        //ユーザの初回突入レベル分消費させる
        $damage = $firstLevel / 2;

        // ﾄﾗｯﾌﾟアイテムを自分のいるポイントで起動。
        $item = array(
            'item_id'=>0, 
            'item_name'=>AppUtil::getText("TEXT_POISON_NET"), 
            'item_value'=>$damage, 
            'item_type'=>Item_MasterService::TACT_ATT,
            'item_vfx'=>3,
        );

        $this->fireItem($leads, $item, $unit->getPos(), $unit);

        $avatarNo = sprintf('%03d', $unit->getNo());

//        $leads[] = "LINES %avatar% ぎゃっ！！";
//        $leads[] = "SPEAK {$avatarNo} もじょ この蜘蛛の巣、毒があるのだ";

        return true;
    }

    //穴から穴へワープする
    private function fireWarp(&$leads, $unit) {

        // unit_eventギミック作成
        $gimmick = array(
            'type'=>"unit_event", 
            'event'=>array(
                'name' => 'exit',
                'reason' => 'room_exit',
            ),
            'target_unit'=>$unit->getProperty("code"),
        );


        //消去ギミック
        $this->fireGimmick($leads, $gimmick, $unit);

        //穴のポジションを定義
        $pos_array = array(
            "warp1"=>array(2,12),
            "warp2"=>array(4,0),
            "warp3"=>array(9,7),
            "warp4"=>array(14,7),
            "warp5"=>array(16,15),
            "warp6"=>array(2,6),
            "warp7"=>array(6,11),
            "warp8"=>array(11,4),
        );

        //入り口キーは削除
        $pos = $unit->getPos();
        foreach($pos_array as $k => $value){
            if($value[0] == $pos[0] && $value[1] == $pos[1]){
                unset($pos_array[$k]);
                break;
            }
        }

        $key = array_rand($pos_array,1);

        $startPos = $pos_array[$key];

        $parm = array(
                'pos' => $startPos,
                'character_id' => $unit->getProperty("character_id"),
                'icon' => $unit->getProperty("icon"),
                'union' => $unit->getProperty("union"),
                'code' => $unit->getProperty("code"),
                'hp' => $unit->getProperty("hp"),
            );

        if($unit->getProperty("act_brain"))
            $parm['act_brain'] = $unit->getProperty("act_brain");

        if($unit->getProperty("target_union"))
            $parm['target_union'] = $unit->getProperty("target_union");

        if($unit->getProperty("target_unit"))
            $parm['target_unit'] = $unit->getProperty("target_unit");

        if($unit->getProperty("early_gimmick"))
            $parm['early_gimmick'] = $unit->getProperty("early_gimmick");

        if($unit->getProperty("trigger_gimmick"))
            $parm['trigger_gimmick'] = $unit->getProperty("trigger_gimmick");

        if($unit->getProperty("items"))
            $parm['items'] = $unit->getProperty("items");

        if($unit->getProperty("sequip"))
            $parm['sequip'] = $unit->getProperty("sequip");

        if($unit->getProperty("add_level"))
            $parm['add_level'] = $unit->getProperty("add_level");

        //主人公の場合はレベル補正しないように
        if($unit->getProperty("code") == "avatar")
            $parm['transcend_adapt'] = false;

        // 登場ギミック作成
        $gimmick = array(
            'type'=>"unit", 
            'unit'=>$parm ,
        );

        //登場ギミック
        $this->fireGimmick($leads, $gimmick, $unit);

    }

    //room_exitの場合を実装する
    public function removeUnit(&$leads, $unitNo, $reason, $triggerUnit = null){

        $unit = $this->units[$unitNo];

        //退出理由が退室以外
        if($reason != "room_exit"){
            parent::removeUnit($leads, $unitNo, $reason, $triggerUnit);
            return;
        }

        // ユニットを削除。
        unset($this->units[$unitNo]);

        // スフィアSWFにユニット退出の指揮を送る。
        $leads[] = sprintf('UEXIT %03d collap', $unitNo);

        // 削除したユニットをリターン。
        return $unit;

    }
}
