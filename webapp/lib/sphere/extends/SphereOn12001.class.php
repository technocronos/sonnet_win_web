<?php

/**
 * 「赤草の実集め」の特殊処理を記述する
 */
class SphereOn12001 extends SphereCommon {

    //-----------------------------------------------------------------------------------------------------
    /**
     * closeGimmick() をオーバーライド
     */
    protected function closeGimmick(&$leads, &$gimmick, $unit) {

        // チェックポイントだったら...
        if($gimmick['x_check']) {

            // 通過カウントをアップ。6つとも通過したなら、"goal" ギミックを起動する。
            if(++$this->state['x_check'] == 6)
                $gimmick['chain'] = "goal";
        }

        return parent::closeGimmick($leads, $gimmick, $unit);
    }
}
