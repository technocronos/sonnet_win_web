<?php

/**
 * 期間限定クエの特殊処理を記述する
 */
class SphereOn98031 extends SphereCommon {

    // クリアした後は何度でも「後劇」を連続実行させる。
    protected $nextQuestId = 98032;
    protected $nextQuestRepeat = true;

}
