<?php

require_once(dirname(__FILE__).'/SphereUnitExBrains.class.php');

/**
 * 自爆するユニットを処理するクラス。
 * このクラスで表されるユニットは、所属が自分とは違うユニットが周囲にいるときに自爆をしようとする。
 */
class SphereUnitBlower extends SphereUnitExBrains {

    //-----------------------------------------------------------------------------------------------------
    /**
     * decideCommand() をオーバーライド
     */
    public function decideCommand(&$leads) {

        // 自爆可能ならば、このターンで自分は何もしない。このまま流れて、行動終了フェーズで自爆する。
        if($this->thinkBlowup())
            return array();

        return parent::decideCommand($leads);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * afterCommand() をオーバーライド。
     */
    public function afterCommand(&$leads) {

        // 自爆不能ならば何もしない。
        if(!$this->thinkBlowup())
            return;

        // 以降、自爆する。

        // 自爆アイテムを自分のいるポイントで起動。
        $item = Service::create('Item_Master')->needRecord(3997);
        $this->sphere->fireItem($leads, $item, $this->getPos(), $this);

        // 自分は死んだことにする。
        $this->collapse();
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 自爆範囲に敵がいるかどうかを返す。
     *
     * @return bool     自爆を適切ならtrue、そうでないならfalse。
     */
    private function thinkBlowup() {

        $map = $this->sphere->getMap();

        // 自爆アイテムを取得。
        $item = Service::create('Item_Master')->needRecord(3997);

        // すべてのユニットをみる。
        foreach($this->sphere->getUnits() as $unit) {

            // 自分と同じ所属は無視。
            if($this->data['union'] == $unit->data['union'])
                continue;

            // 自爆範囲にいないユニットは無視。
            if($map->getManhattanDist($unit->getPos(), $this->getPos()) > $item['item_spread'])
                continue;

            // ここまでくれば、自爆範囲に敵ユニットがいる。
            return true;
        }

        // 自爆範囲に敵ユニットがいないなら自爆しても仕方ない。
        return false;
    }
}
