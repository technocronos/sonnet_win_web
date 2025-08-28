<?php

/**
 * 注文の多いダンジョンでのバトルについての、バトルユーティリティ実装。
   リレイザーを1/10の確率でドロップする
 */
Class FieldBattle98051Util extends FieldBattleUtil {

    //リレイザー
    const DROP_ITEM = "";

    //-----------------------------------------------------------------------------------------------------
    /**
     * getBattleItemsをオーバーライド。
     */
    protected function getBattleItems($battle, $detail) {

        // 戻り値初期化。
        $result = array('challenger'=>array(), 'defender'=>array());

        // 相打ちや時間切れではアイテムはない。勝敗が決まっているなら...
        if($detail['result'] == Battle_LogService::CHA_WIN  ||  $detail['result'] == Battle_LogService::DEF_WIN) {

            // 勝ったほう、負けたほうを取得。
            $winer = $battle['ready_detail'][($detail['result'] == Battle_LogService::CHA_WIN) ? 'challenger' : 'defender'];
            $loser = $battle['ready_detail'][($detail['result'] == Battle_LogService::CHA_WIN) ? 'defender' : 'challenger'];

            // 勝ったほうがプレイヤー配下のユニットならば判定する。
            if($winer['player_owner']) {

                // 戻り値の勝ったほうへの参照を取得。
                $gain = &$result[($detail['result'] == Battle_LogService::CHA_WIN) ? 'challenger' : 'defender'];

                // "srare_drop", "rare_drop", "normal_drop" のうち設定されているものを、低確率のものから
                // 判定していく。
                if($loser['srare_drop']  &&  mt_rand(1, 1000) <= 1 * $rate)
                    $gain[] = $loser['srare_drop'];
                else if($loser['rare_drop']  &&  mt_rand(1, 1000) <= 10 * $rate)
                    $gain[] = $loser['rare_drop'];
                else if($loser['normal_drop']  &&  mt_rand(1, 1000) <= 100 * $rate)
                    $gain[] = $loser['normal_drop'];


                //ニワトリの時計
                if(mt_rand(1, 1000) <= 50){
                        $gain[] = 1902;
                //マルティーニの槌
                }else if(mt_rand(1, 1000) <= 25){
                        $gain[] = 1905;
                }

                //特別ドロップを1/10の確率でドロップする
                //if(mt_rand(1, 1000) <= 100)
                //    $gain[] = self::DROP_ITEM;

                //ガチャチケットを1/30の確率でドロップする
                //if(mt_rand(1, 1000) <= 30)
                //    $gain[] = 99001;

            }
        }

        // リターン。
        return $result;
    }
}
