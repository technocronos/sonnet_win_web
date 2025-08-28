<?php

/**
 * マルティーニの塔のクエストの特殊処理を記述する
 */
class SphereOn31022 extends SphereCommon {

    //-----------------------------------------------------------------------------------------------------
    /**
     * fireGimmick() をオーバーライド。
     */
/*
    protected function fireGimmick(&$leads, &$gimmick, $unit) {
        // goalの場合
        if($gimmick['name'] == 'goal') {
            if(strtotime(BTC_31002001_START_DATE) <= strtotime(Common::getCurrentTime()) && strtotime(BTC_31002001_END_DATE) > strtotime(Common::getCurrentTime())){
                $flg = Service::create('Flag_Log')->getValue(Flag_LogService::BITCOIN, $this->info['user_id'], BTC_31002001_FLAG_ID);

                if(!$flg){
                    Service::create('User_Info')->setVirtualCoin($this->user_id, BTC_31002001_AMOUNT ,BTC_31002001_FLAG_ID);
                    $gimmick['leads'][1] = "NOTIF ビットコインを" . BTC_31002001_AMOUNT . "BTC獲得！！！";
                }
            }
        }

        // あとは、通常通り処理。
        return parent::fireGimmick($leads, $gimmick, $unit);
    }
*/

}
