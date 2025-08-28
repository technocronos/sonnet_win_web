<?php

/**
 * バトルフラッシュで、開始時の確認のためにリクエストされるアクション。
 */
class BattleOpenAction extends SmfBaseAction {

    protected function doExecute($params) {

        // 指定されたバトルの状態が開始されるに適当かどうかチェックして、
        // 開始処理を行う。
        $result = $this->openBattle($params);

        return $result;
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
            return array('result'=>'error', 'err_code'=>'notfoune');
        }

        // バリデーションコードをチェック。
        if($battle['validation_code'] != $params['code']) {
            $this->log("BattleOpenAction: バリデーションコードが不正\n_GET = " . print_r($params, true));
            return array('result'=>'error', 'err_code'=>'inviled_code');
        }

        // 他人のバトルの場合はエラー。
        if($this->user_id != $battle['player_id']) {
            $this->log("他人のバトルで開始通知をしようとした\n_GET = " . print_r($params, true));
            array('result'=>'error', 'err_code'=>'not_my_battle');
        }

        // すでに開始されているバトルの場合はエラーリターン。
        // 通常遷移でも発生する(戻るなど)のでログはとらない。ただしコンティニューの場合はOK。
        if($battle['true_status'] != Battle_LogService::CREATED  && $battle['true_status'] != Battle_LogService::IN_CONTINUE){
            return array('result'=>'error', 'err_code'=>'already_start');
        }

        // バトル種別に応じたバトルユーティリティを取得。
        $battleUtil = BattleCommon::factory($battle);

        // バトル種別に応じた開始処理。
        $errorCode = $battleUtil->openBattle($battle);
        if($errorCode)
            return array('result'=>'error', 'err_code' => $errorCode);

        // バトルの状態を「バトル中」に遷移させる。
        Service::create('Battle_Log')->setStatus($params['battleId'], Battle_LogService::IN_GAME);

        // ここまで来ればOK。
        if($battle['true_status'] != Battle_LogService::IN_CONTINUE){
            return array('result'=>'ok');
        }else{
            $result["result"]  = "retry";

            // プレイヤーキャラと相手キャラが挑戦側、防衛側のどちらになるのかを取得。
            $sideP = &$battle['ready_detail'][ $battle['side_reverse'] ? 'defender' : 'challenger' ];
            $sideE = &$battle['ready_detail'][ $battle['side_reverse'] ? 'challenger' : 'defender' ];

            // 統計値を初期化
            $result['hpP'] =   $sideP["hp"];
            $result['hpE'] =   $sideE["hp"];

            $result['tactP0'] =   $sideP['summary']['tact0'];     // プレイヤーが「ユニゾン」した回数
            $result['tactP1'] =   $sideP['summary']['tact1'];     // プレイヤーが「強攻」を選択した回数
            $result['tactP2'] =   $sideP['summary']['tact2'];     // プレイヤーが「慎重」を選択した回数
            $result['tactP3'] =   $sideP['summary']['tact3'];     // プレイヤーが「吸収」を選択した回数

            $result['nattCntP'] =   $sideP['summary']['nattCnt'];     // 同、相手側
            $result['nhitCntP'] =   $sideP['summary']['nhitCnt'];     // 
            $result['ndamP'] =   $sideP['summary']['ndam'];     // 
            $result['revCntP'] =   $sideP['summary']['revCnt'];     // 
            $result['rattCntP'] = $sideP['summary']['rattCnt'];   // プレイヤーが通常攻撃を繰り出した回数
            $result['rhitCntP'] = $sideP['summary']['rhitCnt'];   // 同、相手側
            $result['rdamP'] = $sideP['summary']['rdam'];   // プレイヤーが通常攻撃を当てた回数
            $result['odamP'] = $sideP['summary']['odam'];   // 同、相手側

            $result['tactE0'] =   $sideE['summary']['tact0'];     // プレイヤーが「ユニゾン」した回数
            $result['tactE1'] =   $sideE['summary']['tact1'];     // プレイヤーが「強攻」を選択した回数
            $result['tactE2'] =   $sideE['summary']['tact2'];     // プレイヤーが「慎重」を選択した回数
            $result['tactE3'] =   $sideE['summary']['tact3'];     // プレイヤーが「吸収」を選択した回数

            $result['nattCntE'] =   $sideE['summary']['nattCnt'];     // 同、相手側
            $result['nhitCntE'] =   $sideE['summary']['nhitCnt'];     // 
            $result['ndamE'] =   $sideE['summary']['ndam'];     // 
            $result['revCntE'] =   $sideE['summary']['revCnt'];     // 
            $result['rattCntE'] = $sideE['summary']['rattCnt'];   // プレイヤーが通常攻撃を繰り出した回数
            $result['rhitCntE'] = $sideE['summary']['rhitCnt'];   // 同、相手側
            $result['rdamE'] = $sideE['summary']['rdam'];   // プレイヤーが通常攻撃を当てた回数
            $result['odamE'] = $sideE['summary']['odam'];   // 同、相手側

            return $result;
        }
    }
}
