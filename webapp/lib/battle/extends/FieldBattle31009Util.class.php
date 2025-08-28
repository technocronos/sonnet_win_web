<?php

/**
 * 大脱走でのバトルについての、バトルユーティリティ実装。
　オーディンでコンティニュー不可にする
 */
Class FieldBattle31009Util extends FieldBattleUtil {


    //-----------------------------------------------------------------------------------------------------
    /**
     * getContinueInfoをオーバーライド。
     */
   public function getContinueInfo($battle) {

        // プレイヤーキャラと相手キャラが挑戦側、防衛側のどちらになるのかを取得。
        $sideP = &$battle['ready_detail'][ $battle['side_reverse'] ? 'defender' : 'challenger' ];
        $sideE = &$battle['ready_detail'][ $battle['side_reverse'] ? 'challenger' : 'defender' ];

        //敵がオーディンの場合(どこで出てきても)コンティニューはできない
        if($sideE["character_id"] == -10102){
            $sideE["continue_not_use"] = true;
        }

        //後は親を呼ぶ
        $errorInfo = parent::getContinueInfo($battle);
        return $errorInfo;
   }

}
