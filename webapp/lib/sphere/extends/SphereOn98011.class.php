<?php

/**
 * 期間限定クエの特殊処理を記述する
 */
class SphereOn98011 extends SphereCommon {

    private $kosan_move = false;
    private $gimmick_tips = array(2553, 2500, 2503, 2546);

    // 地形破壊後の地形ID。
    protected $destructedId = 2548;

    //穴のポジションを定義
    //sourceは入口、destはunionで出口を分ける
    public static $pos_array = array(
            "warp1"=>array("source" =>array(8,7), "dest" => array(1 => "warp4", 2 => "warp4")),
            "warp2"=>array("source" =>array(8,13), "dest" => array(1 => "warp6", 2 => "warp4")),
            "warp3"=>array("source" =>array(7,16), "dest" => array(1 => "warp1", 2 => "warp1")),
            "warp4"=>array("source" =>array(8,16), "dest" => array(1 => "warp1", 2 => "warp1")),
            "warp5"=>array("source" =>array(17,15), "dest" => array(1 => "warp6", 2 => "warp7")),
            "warp6"=>array("source" =>array(17,12), "dest" => array(1 => "warp5", 2 => "warp5")),
            "warp7"=>array("source" =>array(12,5), "dest" => array(1 => "warp5", 2 => "warp5")),
        );

    //progressTurnEndをオーバーライド
    public function progressTurnEnd(&$leads) {
        // コマンドと、コマンド対象になっているユニットを取得。
        $command = $this->state['command'];
        $commUnit = $this->getUnit();

        //room1ルームのみ
        if($this->state["current_room"] == "room1"){
            //ワープマップチップを踏んだ場合
            if(in_array($this->getGraph_no($command["move"]["to"]), $this->gimmick_tips)){
                //穴に入った場合の処理
                $this->fireWarp($leads, $commUnit);
            }
        }

        return parent::progressTurnEnd($leads);
    }

    //progressPreCommをオーバーライド
    public function progressPreComm(&$leads) {
        //startルームのみ
        if($this->state["current_room"] == "start"){

            //敵が全滅してるかどうか
            $units = array();
            $units[] = $this->getUnitByCode("ork1");
            $units[] = $this->getUnitByCode("ork2");
            $units[] = $this->getUnitByCode("ork4");
            $units[] = $this->getUnitByCode("ork5");
            $units[] = $this->getUnitByCode("orkking");

            $kosan = $this->getUnitByCode("kosan");

            $gimmick_flg = true;
            foreach($units as $unit){
                if($unit != null){
                    $gimmick_flg = false;
                    break;
                }
            }

            //ようするに何がやりたいかというと敵を全滅させて最初に主人公にフォーカスが当たる前にギミックを起動させたい
            if(!is_null($kosan) && $gimmick_flg){
                //ゲームが終わっていて主人公のターン
                $commUnit = $this->getUnit();

                // プレイヤーオーナーのユニット
                if( $commUnit->getProperty('player_owner') ) {
                    //クリアフラグを立てる
                    Service::create('Flag_Log')->setValue(Flag_LogService::FLAG, $this->info['user_id'], 980110009,1);
                    //なんとなく向き合う
                    $this->getFaceToFace2($leads, $commUnit, $kosan);
                    //終了会話を起動
                    $this->triggerGimmick($leads, 'speak2', $kosan);
                }
            }
        }else if($this->state["current_room"] == "room1"){
            $erf1 = $this->getUnitByCode("erf1");
            if(is_null($erf1)){
                Service::create('Flag_Log')->setValue(Flag_LogService::FLAG, $this->info['user_id'], 980110019,1);
            }
        }

        return parent::progressPreComm($leads);
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

        // プレイヤーオーナーか敵のユニットは基底処理
        if( $commUnit->getProperty('player_owner') || $commUnit->getProperty('union') == 2) {
            return parent::progressCommand($leads);
    		}

    		//それ以外（NPC）は何もしない（フォーカスも当てない）
        $this->phaseNext($leads);
        return false;
  	}

    //-----------------------------------------------------------------------------------------------------
    /**
     * processSkipBattle()をオーバーライド。
     */
    protected function processSkipBattle(&$leads, $challenger, $defender) {

        // オークに殺される戦闘を出来試合にする。
        if($challenger->getCode() == 'man1'  ||  $defender->getCode() == 'man1' 
                    || $challenger->getCode() == 'man2'  ||  $defender->getCode() == 'man2'
                        || $challenger->getCode() == 'kosan'  ||  $defender->getCode() == 'kosan') {

            $kosanUnit = $this->getUnitByCode("kosan");
            $maxhp = (int)$kosanUnit->getProperty("hp_max");

            if($challenger->getCode() == 'kosan'  ||  $defender->getCode() == 'kosan')
                $damage =  $maxhp / 4;
            else
                $damage = $maxhp;

            // バトルイベントを作成してキューへ。
            $battle = array();
            $battle['type'] = 'battle2';
            $battle['challenger'] = $challenger->getNo();
            $battle['defender'] = $defender->getNo();
            $battle['total']['challenger'] = ($challenger->getCode() == 'man1' || $challenger->getCode() == 'man2' || $challenger->getCode() == 'kosan') ? $damage : 0;
            $battle['total']['defender'] =   ($challenger->getCode() == 'man1' || $challenger->getCode() == 'man2' || $challenger->getCode() == 'kosan') ? 0 : $damage;
            $this->pushStateEvent($battle);

            // 基底の処理は行わない。
            return;
        }

        return parent::processSkipBattle($leads, $challenger, $defender);
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



        //入り口キーから出口を探す
        $pos = $unit->getPos();
        $union = $unit->getProperty('union');

        foreach(self::$pos_array as $k => $value){
            if($value["source"][0] == $pos[0] && $value["source"][1] == $pos[1]){
                $destKey = self::$pos_array[$k]["dest"][$union];
                $startPos = self::$pos_array[$destKey]["source"];
                break;
            }
        }

        $parm = array(
                'pos' => $startPos,
                'character_id' => $unit->getProperty("character_id"),
                'icon' => $unit->getProperty("icon"),
                'union' => $unit->getProperty("union"),
                'code' => $unit->getProperty("code"),
                'hp' => $unit->getProperty("hp"),
            );

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

        if($unit->getProperty("unit_class"))
            $parm['unit_class'] = $unit->getProperty("unit_class");

        if($unit->getProperty("code") == "avatar"){
            //主人公の場合はact_brain引き継ぎ
            if($unit->getProperty("act_brain"))
                $parm['act_brain'] = $unit->getProperty("act_brain");

            //主人公の場合はレベル補正しないように
            $parm['transcend_adapt'] = false;

            //player_ownerにしておく
            $parm['player_owner'] = $unit->getProperty("player_owner");
        }else{
            //ゴブリンはワープしたら一旦genericにしておく
            $parm['act_brain'] = "generic";
        }

        // 登場ギミック作成
        $gimmick = array(
            'type'=>"unit", 
            'unit'=>$parm ,
        );

        //登場ギミック
        $this->fireGimmick($leads, $gimmick, $unit);

    }

    //そのポジションにあるマップチップのIDを得る
    private function getGraph_no($pos){
        $structure = $this->map->getStructure();
        $maptips = $this->map->getMapTips();

        return $maptips[$structure[$pos[1]][$pos[0]]]["graph_no"];
    }

    //room_exitの場合を実装する
    public function removeUnit(&$leads, $unitNo, $reason, $triggerUnit = null){
        if($this->state["current_room"] == "room1"){

//Common::varLog("removeUnit reason=" . $reason);

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
        }else{
            parent::removeUnit($leads, $unitNo, $reason, $triggerUnit);
        }

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * map::changeSquareOnRegionを別実装。特定のチップのみが地形変更するようにする。
     */
    public function changeSquareOnRegion($region, $squareId) {

        $leads = array();
        $destruct_tips = array(1732);

        // 指定された領域に含まれるマスを一つずつ変更していく。
        foreach($region as $y => $line) {
            foreach($line as $x => $dummy) {
                $graphNo = $this->getGraph_no(array($x,$y));
                if(in_array($graphNo, $destruct_tips, true)){
                    $leads[] = $this->map->changeSquare(array($x,$y), $squareId);
                    if($x == 8 && $y == 23){
                        //$this->modifyGimmick('hide-treasure1',   'ornament', 'twinkle');
                    }
                }
            }
        }

        return $leads;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * fireItem()をオーバーライド。
     */
    public function fireItem(&$leads, $item, $to, $firer = null) {

        // 効果範囲になる座標をすべて取得。
        $range = $this->map->getMovables($to, $item['item_spread']);

        // ユニットの所在マップを取得。
        $unitMap = $this->map->getUnitMap();

        // 効果範囲になっているマスを一つずつ処理する。
        $poses = '';
        $exposeUnits = array();
        foreach($range as $y => $line) {
            foreach($line as $x => $dummy) {

                // エフェクトをセットする座標を列挙する文字列を作成していく。
                $poses .= sprintf('%02d%02d', $x, $y);

                // そこにユニットがいる場合は覚えておく。
                if( isset($unitMap[$y][$x]) )
                    $exposeUnits[] = $unitMap[$y][$x];
            }
        }

        // 効果の解説指揮
        $leads[] = 'IPRET ' . $item['item_name'];
        $leads[] = 'DELAY 500';

        // 地形破壊効果がある場合は地形を変更する。
        if($item['item_flags'] & Item_MasterService::DESTRUCT) {
            $region = $this->map->getMovables($to, $item['item_spread']);

            $leads = array_merge($leads, $this->changeSquareOnRegion($region, $this->destructedId));
        }

        // 振動つきの場合は振動をかける。
        if($item['item_flags'] & Item_MasterService::VIB_EFFECT)
            $leads[] = 'VIBRA 03';

        // 効果範囲にエフェクトを再生する指揮を発行
        $leads[] = sprintf('EFFEC %s %s', $this->getItemVfx($item), $poses);

        // 振動つきの場合は振動を止める。
        if($item['item_flags'] & Item_MasterService::VIB_EFFECT)
            $leads[] = 'VIBRA 00';

        // 効果対象になったユニットに効果をもたらす。
        foreach($exposeUnits as $unit){
            //主人公の場合のみ、レベルが上がった場合は相対的にダメージが下がりすぎるため補正する。（defenceXは計算しない）
            if($unit->getProperty("code") == "avatar"){
                $maxhp = $unit->getProperty("hp_max");
                $damage = (int)$maxhp / 6;
                //ただし、最低でも設定されているダメージは与える
                if($item["item_value"] < $damage)
                    $item["item_value"] = $damage;
            }

            $unit->exposeItem($leads, $item, $firer);
        }

        // 解説表示の終了指揮をセットする。
        $leads[] = 'IPRET';

        // 火炎瓶なら、ギミック "hero_surprise" をあとで起動。
        if($item['item_id'] == 3996)
            $this->kickGimmick($leads, 'hero_surprise', $firer);

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * rotateNext() をオーバーライド。
     */
    protected function rotateNext(&$leads) {

        // room2なら...
        if($this->state['current_room'] == 'room2') {
            $commUnit = $this->getUnitByCode("avatar");
            $pos = $commUnit->getPos();

            // オークの補充を処理する。ただし、上のフロアに行ったらもう補充しない。
            if($pos[1] >= 14)
                $this->processAppearance($leads);
        }

        parent::rotateNext($leads);
    }

    /**
     * オークの補充を処理する。
     */
    protected function processAppearance(&$leads) {

        // 補充タイマーを管理。
        $this->state['x_ork_supply_timer']--;
        if($this->state['x_ork_supply_timer'] < 0)  $this->state['x_ork_supply_timer'] = 0;

        // 補充タイミングが来ていないなら補充しない。
        if($this->state['x_ork_supply_timer'] > 0)
            return;

        // オークの数を数える。2匹以上いるなら補充しない。
        $count = 0;
        foreach($this->units as $unit) {
            if($unit->getProperty("character_id") == -9112) {
                if(++$count >= 2)
                    return;
            }
        }

        // 2匹未満なら補充。
        $this->pushStateEvent( array('type'=>'gimmick', 'name'=>'ork_supply') );
        $this->state['x_ork_supply_timer'] = 2;
    }
}
