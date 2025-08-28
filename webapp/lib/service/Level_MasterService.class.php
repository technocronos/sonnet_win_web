<?php

class Level_MasterService extends Service {

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定でされたユーザの仲間人数上限を返す。
     *
     * @param int       ユーザID
     * @return int      仲間人数上限。
     */
    public function getMemberLimit($userId) {

        // アバターキャラの情報を取得。
        $charaSvc = new Character_InfoService();
        $avt = $charaSvc->needAvatar($userId);

        // そのレベルの情報を取得して、member_limit列の値をリターン。
        $level = $this->needRecord($avt['race'], $avt['level']);
        return $level['member_limit'];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたレベルに必要な経験値(蓄積)を返す。
     *
     * @param int       種族ID
     * @param int       レベル
     * @param mixed     指定のレベルレコードがなかった場合に受け取りたい値。
     * @return int      レベルに必要な経験値(蓄積)。
     */
    public function getExpByLevel($race, $level, $ifNothing = null) {

        $record = $this->getRecord($race, $level);

        return $record ? $record['exp'] : $ifNothing;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された経験値(蓄積)でのレベルレコードを返す。
     *
     * @param int       種族ID
     * @param int       経験値(蓄積)
     * @return array    レベルレコード
     */
    public function getLevelByExp($race, $exp) {

        return $this->selectRecord(array(
            'exp' => array('sql'=>'<= ?', 'value'=>$exp),
            'ORDER BY' => 'exp DESC',
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 設定されている最高レベルを返す。
     *
     * @param int       種族ID
     * @return int      最高レベル
     */
    public function getMaxLevel($race) {

        $sql = '
            SELECT MAX(level)
            FROM level_master
            WHERE race = ?
        ';

        return $this->createDao(true)->getOne($sql, $race);
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 設定されている最高レベルを返す。
     *
     * @param int       種族ID
     * @return int      最高レベル
     */
    public function getAllRecord($race) {

        $sql = '
            SELECT *
            FROM level_master
            WHERE race = ?
        ';

        return $this->createDao(true)->getAll($sql, $race);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 設定されているレベルまでで取得できるステータスポイントを返す
     *
     * @return int      レベル
     */
    public function getAllParam($level) {

        $sql = '
            SELECT sum(level_master.param_growth) + sum(level_master.auto_growth) as total
            FROM level_master
            WHERE race = ? AND level <= ?
        ';

        return $this->createDao(true)->getOne($sql, array('PLA', $level));

    }



    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で示された経験値の範囲内で発生するレベルアップ情報を返す。
     *
     * @param int       種族ID
     * @param int       現在の経験値
     * @param int       加算される経験値
     * @return array    発生するレベルアップ情報を表す配列。以下のキーを持つ。
     *                      level_growth            レベルアップ数
     *                      param_growth            ステータスpt増加値
     *                      attackN_growth          Nは整数。攻撃力増加値
     *                      defenceN_growth         Nは整数。防御力増加値
     *                      speed_growth            スピード増加値
     *                      hp_growth               最大HP増加値
     */
    public function getGrowth($race, $currentExp, $gainExp) {

        // 指定された範囲にあるレベルレコードを集計して、成長値を取得する。
        $sql = '
            SELECT COUNT(*) AS level_growth
                 , IFNULL(SUM(param_growth), 0) AS param_growth
                 , IFNULL(SUM(auto_growth), 0) AS auto_growth
            FROM level_master
            WHERE race = ?
              AND exp > ?
              AND exp <= ?
        ';

        $growth = $this->createDao(true)->getRow($sql, array(
            $race, $currentExp, $currentExp + $gainExp
        ));

        // 各パラメータの成長値初期化。
        $growth['attack1_growth'] = 0;  $growth['attack2_growth'] = 0;  $growth['attack3_growth'] = 0;
        $growth['defence1_growth'] = 0;  $growth['defence2_growth'] = 0;  $growth['defence3_growth'] = 0;
        $growth['speed_growth'] = 0;  $growth['hp_growth'] = 0;

        // auto_growthを処理する。
        // まず、レベルアップの分だけパラメータ名を格納する配列を作成。
        $params = array();
        for($i = 0 ; $i < $growth['level_growth'] * 2 ; $i++) {
            $params[] = 'attack1_growth';    $params[] = 'attack2_growth';    $params[] = 'attack3_growth';
            $params[] = 'defence1_growth';   $params[] = 'defence2_growth';   $params[] = 'defence3_growth';
            $params[] = 'speed_growth';      $params[] = 'hp_growth';
        }

        // auto_growthの値の回数、パラメータ名をランダムポップして+1していく。
        for($i = 0 ; $i < $growth['auto_growth'] ; $i++)
            $growth[ MathUtil::randomPop($params) ]++;

        // リターン。
        return $growth;
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('race', 'level');

    protected $isMaster = true;
}
