<?php

/**
 * マルティーニの塔でのラストバトルについての、バトルユーティリティ実装。
 */
Class FieldBattle31022Util extends FieldBattleUtil {


    //-----------------------------------------------------------------------------------------------------
    /**
     * makeResultDetailをオーバーライド。
     * result_detailにbitcoinゲット情報を入れる。
     */
/*
    public function makeResultDetail($battle, $detail, $updateResult, $spendResult, $tourResult) {
        //親を実行
        $result = parent::makeResultDetail($battle, $detail, $updateResult, $spendResult, $tourResult);

        // 相打ちや時間切れではアイテムはない。勝敗が決まっているなら...
        if($detail['result'] == Battle_LogService::CHA_WIN  ||  $detail['result'] == Battle_LogService::DEF_WIN) {

            // 勝ったほう、負けたほうを取得。
            $winer = $battle['ready_detail'][($detail['result'] == Battle_LogService::CHA_WIN) ? 'challenger' : 'defender'];
            $loser = $battle['ready_detail'][($detail['result'] == Battle_LogService::CHA_WIN) ? 'defender' : 'challenger'];

            // 勝ったほうがプレイヤー配下のユニットかつ負けた方が王ならば判定する。
            if($winer['player_owner'] && $loser["character_id"] == -10119) {
                //キャンペーン期間中なら・・
                if(strtotime(BTC_31002001_START_DATE) <= strtotime(Common::getCurrentTime()) && strtotime(BTC_31002001_END_DATE) > strtotime(Common::getCurrentTime())){

                    $user_id = $winer["user_id"];
                    $flg = Service::create('Flag_Log')->getValue(Flag_LogService::BITCOIN, $user_id, BTC_31002001_FLAG_ID);

                    //まだ与えていないなら・・
                    if(!$flg){
                        Service::create('User_Info')->setVirtualCoin($user_id, BTC_31002001_AMOUNT ,BTC_31002001_FLAG_ID);
                        $result["get_vcoin"] = BTC_31002001_AMOUNT;
                    }
                }
            }
        }

        return $result;

   }
*/

}
