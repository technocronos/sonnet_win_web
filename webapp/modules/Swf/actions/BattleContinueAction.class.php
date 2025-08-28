<?php

/**
 * バトルフラッシュで、開始時の確認のためにリクエストされるアクション。
 */
class BattleContinueAction extends TransmitBaseAction {

    protected function doExecute($params) {

        // 指定されたバトルの状態が開始されるに適当かどうかチェックして、
        // 開始処理を行う。
        $errorCode = $this->ContinueBattle($params);

        // Flashにエラーコードを返す。
        return array('result'=>$errorCode);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたバトルの状態が開始されるに適当かどうかチェックして開始処理を行う。
     *
     * @params array    Flash から loadVariables で送信されている GET パラメータ
     * @return string   FLASHに返すエラーコード
     */
    private function ContinueBattle($params) {

        // 指定されているバトル情報をロード。見つからないならエラーリターン。
        $battle = Service::create('Battle_Log')->getRecord($params['battleId']);
        if(!$battle) {
            $this->log("BattleOpenAction: 指定されているバトル情報が見つからない\n_GET = " . print_r($params, true));
            return 'error';
        }

        // バリデーションコードをチェック。
        if($battle['validation_code'] != $params['code']) {
            $this->log("BattleOpenAction: バリデーションコードが不正\n_GET = " . print_r($params, true));
            return 'error';
        }

        // 他人のバトルの場合はエラー。
        if($this->user_id != $battle['player_id']) {
            $this->log("他人のバトルで開始通知をしようとした\n_GET = " . print_r($params, true));
            return 'error';
        }

        //エラーが無ければ以下継続

        // バトル種別に応じたバトルユーティリティを取得。
        $battleUtil = BattleCommon::factory($battle);

        // プレイヤーキャラと相手キャラが挑戦側、防衛側のどちらになるのかを取得。
        $sideP = &$battle['ready_detail'][ $battle['side_reverse'] ? 'defender' : 'challenger' ];
        $sideE = &$battle['ready_detail'][ $battle['side_reverse'] ? 'challenger' : 'defender' ];

        //hpを書き換える
        $sideP["hp"] = (int)$sideP['hp_max']; //全回復
        $sideE["hp"] = (int)$params["hpE"]; //減ったまま
        $sideP["starcnt"] = (int)$params['starP']; 
        $sideE["starcnt"] = (int)$params["starE"]; 

        //自分が受けたコンティニュー以前のダメージは無かったことにする。サマリーも引き継がない。
        $sideP['summary']['tact0'] = 0;
        $sideP['summary']['tact1'] = 0;
        $sideP['summary']['tact2'] = 0;
        $sideP['summary']['tact3'] = 0;
        $sideP['summary']['nattCnt'] = 0;
        $sideP['summary']['nhitCnt'] = 0;
        $sideP['summary']['ndam'] = (int)$params['ndamP'];
        $sideP['summary']['revCnt'] = 0;
        $sideP['summary']['rattCnt'] = 0;
        $sideP['summary']['rhitCnt'] = 0;
        $sideP['summary']['rdam'] = (int)$params['rdamP'];
        $sideP['summary']['odam'] = (int)$params['odamP'];
        $sideE['summary']['tact0']= 0;
        $sideE['summary']['tact1']= 0;
        $sideE['summary']['tact2']= 0;
        $sideE['summary']['tact3']= 0;
        $sideE['summary']['nattCnt'] = 0;
        $sideE['summary']['nhitCnt'] = 0;
        $sideE['summary']['ndam'] = 0;
        $sideE['summary']['revCnt']= 0;
        $sideE['summary']['rattCnt'] = 0;
        $sideE['summary']['rhitCnt'] = 0;
        $sideE['summary']['rdam'] = 0;
        $sideE['summary']['odam'] = 0;

        $sideP["continueInfo"]["continueItemCnt"]--; //コンティニューアイテム数を減らす
        $battle['ready_detail']["continue_count"]++;//コンティニュー回数を増やす

        // バトル種別に応じた開始処理。
        $errorCode = $battleUtil->continueBattle($battle);
        if($errorCode)
            return $errorCode;


        // ここまで来ればOK。
        return 'ok';
    }
}
