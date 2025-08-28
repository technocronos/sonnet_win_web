<?php

/**
 * バトルフラッシュで、開始時の確認のためにリクエストされるアクション。
 */
class BattleOpenAction extends TransmitBaseAction {

    protected function doExecute($params) {

        // 指定されたバトルの状態が開始されるに適当かどうかチェックして、
        // 開始処理を行う。
        $errorCode = $this->openBattle($params);

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
    private function openBattle($params) {

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

        // すでに開始されているバトルの場合はエラーリターン。
        // 通常遷移でも発生する(戻るなど)のでログはとらない。ただしコンティニューの場合はOK。
        if($battle['true_status'] != Battle_LogService::CREATED  && $battle['true_status'] != Battle_LogService::IN_CONTINUE){
            return 'already_start';
        }

        // バトル種別に応じたバトルユーティリティを取得。
        $battleUtil = BattleCommon::factory($battle);

        // バトル種別に応じた開始処理。
        $errorCode = $battleUtil->openBattle($battle);
        if($errorCode)
            return $errorCode;

        // バトルの状態を「バトル中」に遷移させる。
        Service::create('Battle_Log')->setStatus($params['battleId'], Battle_LogService::IN_GAME);

        // ここまで来ればOK。
        if($battle['true_status'] != Battle_LogService::IN_CONTINUE){
            return 'ok';
        }else{
            // プレイヤーキャラと相手キャラが挑戦側、防衛側のどちらになるのかを取得。
            $sideP = &$battle['ready_detail'][ $battle['side_reverse'] ? 'defender' : 'challenger' ];
            $sideE = &$battle['ready_detail'][ $battle['side_reverse'] ? 'challenger' : 'defender' ];

            $arr = array(
                "hpP" . '[' . $sideP["hp"],
                "hpE" . '[' . $sideE["hp"],
                "tactP0" . '[' . $sideP['summary']['tact0'] ,
                "tactP1" . '[' . $sideP['summary']['tact1'],
                "tactP2" . '[' . $sideP['summary']['tact2'],
                "tactP3" . '[' . $sideP['summary']['tact3'],
                "nattCntP" . '[' . $sideP['summary']['nattCnt'],
                "nhitCntP" . '[' . $sideP['summary']['nhitCnt'],
                "ndamP" . '[' . $sideP['summary']['ndam'],
                "revCntP" . '[' . $sideP['summary']['revCnt'],
                "rattCntP" . '[' . $sideP['summary']['rattCnt'],
                "rhitCntP" . '[' . $sideP['summary']['rhitCnt'],
                "rdamP" . '[' . $sideP['summary']['rdam'],
                "odamP" . '[' . $sideP['summary']['odam'],
                "tactE0" . '[' . $sideE['summary']['tact0'] ,
                "tactE1" . '[' . $sideE['summary']['tact1'],
                "tactE2" . '[' . $sideE['summary']['tact2'],
                "tactE3" . '[' . $sideE['summary']['tact3'],
                "nattCntE" . '[' . $sideE['summary']['nattCnt'],
                "nhitCntE" . '[' . $sideE['summary']['nhitCnt'],
                "ndamE" . '[' . $sideE['summary']['ndam'],
                "revCntE" . '[' . $sideE['summary']['revCnt'],
                "rattCntE" . '[' . $sideE['summary']['rattCnt'],
                "rhitCntE" . '[' . $sideE['summary']['rhitCnt'],
                "rdamE" . '[' . $sideE['summary']['rdam'],
                "odamE" . '[' . $sideE['summary']['odam'],
            );

            $result = implode("]", $arr) . "]";

            return $result;
        }
    }
}
