<?php

/**
 * あまり使用されない特殊な行動ロジックを集約しているSphereUnit派生クラス。
 */
class SphereUnitExBrains extends SphereUnit {

    //-----------------------------------------------------------------------------------------------------
    /**
     * "excurse" 型行動決定ルーチン。基本的には遊覧軌道をたどるが、移動可能な範囲に攻撃可能な場所が
     * ある場合は攻撃する。
     * 攻撃のための移動をすると、遊覧軌道がずれる。
     *
     * ユニットプロパティで以下の値を設定できる。
     *     excurse_path     遊覧軌道。"2":上、"6":右、"8":下、"4":左 で文字列として記述する。
     *     excurse_step     1ターンの遊覧距離。
     *
     * brain_noattack の指定にも対応している。
     *
     * 例)
     *     例えば、1ターンに3マスずつ、上上右右右下下下左左左上 の順で遊覧させたいなら次のように指定する。
     *     excurse_path     "226668884442"
     *     excurse_step     3
     *
     * @return array    決定したコマンドを格納する配列。SphereCommon::checkCommand() と同様。
     */
    protected function brainExcurse() {

        $map = $this->sphere->getMap();

        // 現在の場所から回復アイテム使用が可能ならそうする。
        $use = $this->thinkItem(Item_MasterService::RECV_HP);
        if($use)
            return array('use'=>$use);

        // 直接攻撃が可能ならばそうする。
        $this->requireBrainFlash('assault');
        if($this->brainFlash['assault'])
            return $this->brainFlash['assault'][0];

        // 以降は、遊覧を行う。

        $this->requireBrainFlash('unit_map');

        // 遊覧軌道がないなら、その場から動かない。
        if(strlen($this->data['excurse_path']) == 0)
            return array();

        // このターンでの遊覧パスを取得。
        $path = $this->data['excurse_path'].$this->data['excurse_path'];
        $path = substr($path, $this->data['excurse_pos'], $this->data['excurse_step']);

        // 遊覧距離を一つずつ縮めながら見ていく。
        $command = array();
        for( ; strlen($path) > 0 ; $path = substr($path, 0, strlen($path) - 1) ) {

            // 経路をたどるとどの座標に行き着くのかを取得。
            $dest = $map->walk($this->getPos(), $path);

            // 他のユニットがいてそこへは移動できないなら次へ。
            if( isset($this->brainFlash['unit_map'][$dest[1]][$dest[0]]) )
                continue;

            // 経路を取得。到達不能な場合は次へ。
            $cost = $map->getRoute($this, $dest, $route);
            if($cost > $this->getProperty('move_pow'))
                continue;

            // ここまで来たなら、その場所へ移動。
            $command = array( 'move' => array('to'=>$dest, 'path'=>$route) );
            break;
        }

        // 次のターンのために、周遊参照位置を進める。
        $this->data['excurse_pos'] += strlen($path);
        $this->data['excurse_pos'] %= strlen($this->data['excurse_path']);

        // 決定したコマンドをリターン。
        return $command;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * "wistful" 型行動決定ルーチン。基本的には "generic" 型と同じだが、到達可能な範囲に敵ユニットが
     * いない場合に、マンハッタン距離が最も短い敵ユニットへ、マンハッタン距離が小さくなる場所に移動
     * しようとする。
     *
     * ユニットプロパティで以下の値を設定できる。
     *     wistful_slow     省略可能。到達可能な範囲に敵ユニットがいない場合に、移動距離をここで
     *                      設定された値以下に抑える。
     *
     * @return array    決定したコマンドを格納する配列。SphereCommon::checkCommand() と同様。
     */
    protected function brainWistful() {

        // まずは "generic" で考える。
        $ret = $this->brainGeneric();
        if($ret)
            return $ret;

        // マンハッタン距離が最も短い敵ユニットを変数 $nearestUnit に取得する。
        $nearestDist = 0x7FFFFFFF;
        $nearestUnit = null;
        foreach($this->sphere->getUnits() as $unit) {

            if($this->getCode() == $unit->getCode())
                continue;

            $dist = $this->sphere->getMap()->getManhattanDist($this->getPos(), $unit->getPos());
            if($dist < $nearestDist) {
                $nearestDist = $dist;
                $nearestUnit = $unit;
            }
        }

        // 敵ユニットがいない場合は何もしない。
        if(!$nearestUnit)
            return array();

        // そのユニットへの経路情報を取得。
        $this->requireBrainFlash('route_to_enemy');
        $route = $this->brainFlash['route_to_enemy'][ $nearestUnit->getNo() ];

        // "wistful_slow" が指定されている場合、経路をそれ以下の長さにカットする。
        if( isset($this->data['wistful_slow']) )
            $route['path'] = substr($route['path'], 0, $this->data['wistful_slow']);

        // 行ける範囲で経路を行く。
        $command = array();
        $command['move'] = $this->thinkWalk($route['path']);

        // 接近後の場所でもう一度アイテム使用を考える。
        $use = $this->thinkItem(0, $command['move']['to']);
        if($use)
            $command['use'] = $use;

        // リターン。
        return $command;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * ドワーフを攻撃しない
     *
     * @return array    決定したコマンドを格納する配列。SphereCommon::checkCommand() と同様。
     */
    protected function brainThroughDwarf() {

        // 現在の場所から回復アイテム使用が可能ならそうする。
        $use = $this->thinkItem(Item_MasterService::RECV_HP);
        if($use)
            return array('use'=>$use);

        // フラグ "brain_item_orient" がONならば...
        if($this->data['brain_item_orient']) {

            // 到達可能な領域すべてにおいてアイテムの使用を考えて、適切な場所があるならそうする。
            $this->requireBrainFlash('movables');
            $command = $this->thinkItemOnRegion(0, $this->brainFlash['movables']);
            if($command)
                return $command;
        }

        $dwarf =  $this->sphere->getUnitByCode('dwarf');
        $dwarfNo =  sprintf('%03d', $dwarf->getNo());

        // 直接攻撃が可能ならばそうする。
        $this->requireBrainFlash('assault');
        if($this->brainFlash['assault']){
            foreach($this->brainFlash['assault'] as $assault){
                if($assault['attack'] != $dwarfNo)
                    return $assault;
            }
        }

        // 直接攻撃可能なポイントにいる最も近い敵への接近を行う。
        $command = $this->thinkApproach('nearest', null, $dwarfNo);
        return $command ?: array();

    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * ゲバルを攻撃しない
     *
     * @return array    決定したコマンドを格納する配列。SphereCommon::checkCommand() と同様。
     */
    protected function brainThroughGebaru() {

        // 現在の場所から回復アイテム使用が可能ならそうする。
        $use = $this->thinkItem(Item_MasterService::RECV_HP);
        if($use)
            return array('use'=>$use);

        // フラグ "brain_item_orient" がONならば...
        if($this->data['brain_item_orient']) {

            // 到達可能な領域すべてにおいてアイテムの使用を考えて、適切な場所があるならそうする。
            $this->requireBrainFlash('movables');
            $command = $this->thinkItemOnRegion(0, $this->brainFlash['movables']);
            if($command)
                return $command;
        }

        $gebaru =  $this->sphere->getUnitByCode('gebaru');
        $gebaruNo =  sprintf('%03d', $gebaru->getNo());

        // 直接攻撃が可能ならばそうする。
        $this->requireBrainFlash('assault');
        if($this->brainFlash['assault']){
            foreach($this->brainFlash['assault'] as $assault){
                if($assault['attack'] != $gebaruNo)
                    return $assault;
            }
        }


        // 直接攻撃可能なポイントにいる最も近い敵への接近を行う。
        $command = $this->thinkApproach('nearest', null, $gebaruNo);
        return $command ?: array();

    }
}
