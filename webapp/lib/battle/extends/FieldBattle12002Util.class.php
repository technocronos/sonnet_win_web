<?php

/**
 * 発見!西の森の洞窟 でのバトルについての、バトルユーティリティ実装。
 * 条件付きでニワトリの時計、マルティーニの槌を追加でドロップさせる。
 */
Class FieldBattle12002Util extends FieldBattleUtil {


    //-----------------------------------------------------------------------------------------------------
    /**
     * getBattleItemsをオーバーライド。
     */
    protected function getBattleItems($battle, $detail) {

        // 戻り値初期化。
        $result = array('challenger'=>array(), 'defender'=>array());
        $rate = 1;

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

                //プレイヤーのレベル-5よりモンスターが上の場合。あまりに弱い敵だと取れないように。
                if(($winer["level"] - 5) <= $loser["level"]){
                    //ニワトリの時計
                    if(mt_rand(1, 1000) <= 50){
                            $gain[] = 1902;
                    //マルティーニの槌
                    }else if(mt_rand(1, 1000) <= 25){
                            $gain[] = 1905;
                    //ダガー
                    }else if(mt_rand(1, 1000) <= 10){
                            $gain[] = 11003;
                    //レザーガード
                    }else if(mt_rand(1, 1000) <= 10){
                            $gain[] = 12004;
                    //赤いバンダナ
                    }else if(mt_rand(1, 1000) <= 10){
                            $gain[] = 13003;
                    //ぬいぐるみ
                    }else if(mt_rand(1, 1000) <= 10){
                            $gain[] = 14003;
                    }
                }
            }
        }

        // リターン。
        return $result;
    }
}
