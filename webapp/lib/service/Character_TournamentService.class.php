<?php

class Character_TournamentService extends Service {

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたキャラクターの全戦闘通算勝敗数を返す。
     *
     * @param int       キャラクターID
     * @return array    以下のキーをもつ配列。
     *                      challenge_win       -- 挑戦側勝利数
     *                      challenge_lose      -- 挑戦側敗北数
     *                      challenge_timeup    -- 挑戦側時間切れ数
     *                      challenge_draw      -- 挑戦側相討数
     *                      defend_win          -- 同、防衛側
     *                      defend_lose         --
     *                      defend_timeup       --
     *                      defend_draw         --
     *                      win                 -- 合計勝利数
     *                      lose                -- 合計敗北数
     *                      timeup              -- 合計時間切れ数
     *                      draw                -- 合計相討数
     *                      fights              -- 通算戦闘数
     */
    public function getTotalWins($characterId) {

        // SQL作成。
        $sql = '
            SELECT IFNULL(SUM(challenge_win), 0) AS challenge_win
                 , IFNULL(SUM(challenge_lose), 0) AS challenge_lose
                 , IFNULL(SUM(challenge_timeup), 0) AS challenge_timeup
                 , IFNULL(SUM(challenge_draw), 0) AS challenge_draw
                 , IFNULL(SUM(defend_win), 0) AS defend_win
                 , IFNULL(SUM(defend_lose), 0) AS defend_lose
                 , IFNULL(SUM(defend_timeup), 0) AS defend_timeup
                 , IFNULL(SUM(defend_draw), 0) AS defend_draw
            FROM character_tournament
            WHERE character_id = ?
        ';

        // 実行。
        $result = $this->createDao(true)->getRow($sql, $characterId);

        // 計算列を補う。
        $result['win'] =  $result['challenge_win']  + $result['defend_win'];
        $result['lose'] = $result['challenge_lose'] + $result['defend_lose'];
        $result['timeup'] = $result['challenge_timeup'] + $result['defend_timeup'];
        $result['draw'] = $result['challenge_draw'] + $result['defend_draw'];
        $result['fights'] = $result['win'] + $result['lose'] + $result['timeup'] + $result['draw'];

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたキャラの戦績数を更新する。
     *
     * @param int       キャラクターID
     * @param int       戦闘種別ID。tournament_master.tournament_id。
     * @param int       勝敗を表すコード。Battle_LogServiceで定義されている。
     * @param bool      第一引数で指定したキャラが挑戦側ならtrue、防衛側ならfalse。
     * @return array    処理結果の情報を表す配列。以下のキーがある。
     *                      tour        処理後の character_tournament レコード
     *                      total       処理後の通算勝敗数
     */
    public function recordFight($characterId, $tourId, $winCode, $isChallenger) {

        // 指定のキャラはどうなったのかを変数 $fightResult に取得。
        // "win":勝った、"lose":負けた、"draw":相討になった、"timeup":タイムアップした
        if($winCode == Battle_LogService::DRAW  ||  $winCode == Battle_LogService::TIMEUP)
            $fightResult = ($winCode == Battle_LogService::DRAW) ? 'draw' : 'timeup';
        else
            $fightResult = ($isChallenger ^ ($winCode == Battle_LogService::CHA_WIN)) ? 'lose' : 'win';

        // 戦績列("challenge_win"とか)のうち、どの列を+1するのかを取得。
        $increaseCol = ($isChallenger ? 'challenge' : 'defend') . '_' . $fightResult;

        // INSERT する場合のレコードを作成。
        $record = array('character_id'=>$characterId, 'tournament_id'=>$tourId);
        $record[$increaseCol] = 1;

        // レコードを保存。すでにある場合は対象戦績列が+1されるようにする。
        $this->saveRecord($record, array(
            $increaseCol => array('sql'=>"{$increaseCol} + 1")
        ));

        // 更新後のレコードと総合成績を取得。
        $tour = $this->needRecord($characterId, $tourId);
        $total = $this->getTotalWins($characterId);

        // リターン。
        return array(
            'tour' => $tour,
            'total' => $total,
        );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたキャラの戦績数をリセットする。
     *
     * @param int       キャラクターID
     */
    public function resetScore($characterId) {

        $where = array();
        $where['character_id'] = $characterId;

        $this->createDao()->delete($where);
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('character_id', 'tournament_id');


    //-----------------------------------------------------------------------------------------------------
    /**
     * needRecordをオーバーライド。
     * レコードがなかった場合に例外を投げるのではなく初期値で埋めたレコードを返す。
     */
    public function needRecord(/* 可変引数 */) {
        
        $args = func_get_args();
        $characterId = $args[0];
        $tourId = $args[1];

        // レコードあるならそのまま返す。
        $result = $this->getRecord($characterId, $tourId);
        if($result)
            return $result;

        // レコードないなら初期値で埋めたレコードを返す。
        $record = array(
            'character_id' => $characterId,
            'tournament_id' => $tourId,
            'challenge_win' => 0,
            'challenge_lose' => 0,
            'challenge_timeup' => 0,
            'challenge_draw' => 0,
            'defend_win' => 0,
            'defend_lose' => 0,
            'defend_timeup' => 0,
            'defend_draw' => 0,
        );

        $this->processRecord($record);
        return $record;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * processRecordをオーバーライド。
     * 擬似列 win, lose, draw, fights を追加する。
     */
    protected function processRecord(&$record) {
        $record['win'] =    $record['challenge_win']  +   $record['defend_win'];
        $record['lose'] =   $record['challenge_lose'] +   $record['defend_lose'];
        $record['timeup'] = $record['challenge_timeup'] + $record['defend_timeup'];
        $record['draw'] =   $record['challenge_draw'] +   $record['defend_draw'];
        $record['fights'] = $record['win'] + $record['lose'] + $record['timeup'] + $record['draw'];
    }
}
