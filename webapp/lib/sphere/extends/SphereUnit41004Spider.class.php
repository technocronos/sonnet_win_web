<?php

/**
 * 砂漠の子蜘蛛を処理するクラス。
 */
class SphereUnit41004Spider extends SphereUnit {

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

        // まずは "generic" で考える。
        $ret = $this->brainGeneric();
        if($ret)
            return $ret;

        // 主人公への経路を取得。
        $this->requireBrainFlash('route_to_enemy');
        $routes = $this->brainFlash['route_to_enemy'];

		    $avatar = $this->sphere->getUnitByCode('avatar');
        $route = $routes[ $avatar->getNo() ];

        // 行ける範囲で経路を行く。
        $command = array();
        $command['move'] = $this->thinkWalk($route['path']);

        // リターン。あとはSphereOn41004クラスでスパイダーネットを張る。
        return $command;

    }

}
