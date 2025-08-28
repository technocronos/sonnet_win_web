<?php

/**
 * 師匠討伐戦の特殊処理を記述する
 */
class SphereOn11007 extends SphereCommon {

    // 初めてクリアした後は「祝！完全勝利！」を連続実行させる。
    protected $nextQuestId = 11008;

    // ミッション達成までの...
#     const MISSION_ENEMIES = 5;      // 撃破数

    // ミッション達成時の報酬金。
#     protected $missionReward = 400;

    // 地形破壊後の地形ID。
    protected $destructedId = 2;


    //-----------------------------------------------------------------------------------------------------
    /**
     * もじょによる回復補給を処理する。
     */
    protected function processMojohSupply(&$leads, &$gimmick, $trigger) {

        // 主人公ユニットを取得。
        $avatar = $this->getUnitByCode('avatar');

        // 所持アイテムを取得。
        $ids = $avatar->getProperty('items');
        $items = Service::create('User_Item')->getRecordsIn($ids);

        // 回復アイテムの数を変数 $recov に数える。
        $recov = 0;
        foreach($ids as $id) {
            if($items[$id]['item_type'] == Item_MasterService::RECV_HP)
                $recov++;
        }

        // 全部で4つになるように補給数を決める。
        $supply = 4 - $recov;

        // すでに4つ以上あるならリターン。
        if($supply <= 0)
            return false;

        // 補給。
        for($i = 0 ; $i < $supply ; $i++)
            $leads = array_merge($leads, $this->gainTreasure(1001, $avatar, true));

        // その表示。
        $leads[] = sprintf(AppUtil::getText("sphere_11007_11007000_itemget1"), $avatar->getNo());
        $leads[] = sprintf(AppUtil::getText("sphere_11007_11007000_itemget2"), $supply);

        return false;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * fireItem()をオーバーライド。
     */
    public function fireItem(&$leads, $item, $to, $firer = null) {

        // 精霊の証の場合、経験値が入らないようにする。
        if($item['item_id'] == 3998)
            $firer = null;

        // 基底の処理。
        $ret = parent::fireItem($leads, $item, $to, $firer);

        // 精霊の証なら、ギミック "galuf_surprise" をあとで起動。
        if($item['item_id'] == 3998)
            $this->kickGimmick($leads, 'galuf_surprise', $firer);

        return $ret;
    }
}
