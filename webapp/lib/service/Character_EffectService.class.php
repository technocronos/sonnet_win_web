<?php

class Character_EffectService extends Service {

    // type列の値を表す定数。
    const TYPE_EXP_INCREASE = 1;
    const TYPE_HP_RECOVER = 2;
    const TYPE_ATTRACT = 10;
    const TYPE_DTECH_POWUP = 11;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された効果の名前を返す。
     *
     * @param int       効果種別を表す値。このクラスの定数を使用。
     * @return string   効果の名前
     */
    public function getEffectName($type) {

        switch($type) {
            case self::TYPE_EXP_INCREASE:   return AppUtil::getText("TYPE_EXP_INCREASE");
            case self::TYPE_HP_RECOVER:     return AppUtil::getText("TYPE_HP_RECOVER");
            case self::TYPE_ATTRACT:        return AppUtil::getText("TYPE_ATTRACT");
            case self::TYPE_DTECH_POWUP:    return AppUtil::getText("TYPE_DTECH_POWUP");
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたキャラクターの、引数で指定された効果の値を返す。
     *
     * @param int       キャラクターID
     * @param int       効果種別を表す値。このクラスの定数を使用。
     * @return int      value列の合計値か最大値(効果種別によって異なる)。
     */
    public function getEffectValue($characterId, $type) {

        // 指定された効果のレコードをすべて取得。
        $effectRecords = $this->getEffects($characterId, $type);

        switch($type) {

            // これらの効果はvalue列の値を合計して返す。
            case self::TYPE_EXP_INCREASE:
            case self::TYPE_HP_RECOVER:
                return ResultsetUtil::sum($effectRecords, 'value');

            // これらの効果はvalue列の最大値を返す。
            case self::TYPE_ATTRACT:
            case self::TYPE_DTECH_POWUP:
                $vals = ResultsetUtil::colValues($effectRecords, 'value');
                return $vals ? max($vals) : 0;
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された時間から現在までの、効果の値を返す。
     *
     * 例えば、次の3つのcharacter_effectレコードがあるときに...
     *
     *        since(引数)     now
     *             │          │
     *     ──→  │          │          1. value列:50   引数で指定された時間(since)の前に期限切れになっている
     *             │8sec      │
     *     ────┼─→      │          2. value列:60   sinceではまだ有効だが、現在時よりも前に期限切れになっている
     *             │          │                          sinceから期限切れまでは8秒。
     *             │   20sec  │
     *     ────┼─────┼─→      3. value列:30   現在時でもまだ期限が切れてない。
     *             │          │                          sinceから現在時までは20秒。
     *
     * 次のような計算を行って、値を返す。
     *
     *     1. value列:50 x 0sec = 0
     *     2. value列:60 x 8sec = 48
     *     3. value列:30 x 20sec = 600
     *     合計して 648 をリターン。
     *
     * @param int       キャラクターID
     * @param int       効果種別を表す値。このクラスの定数を使用。
     * @param int       計算開始時刻のタイムスタンプ。上記のsinceの値。
     * @return float    value列の合計値。
     */
    public function getTimeValue($characterId, $type, $since) {

        // 指定された効果のレコードをすべて取得。レコードがないなら 0 を返す。
        $records = $this->getEffects($characterId, $type, true);
        if(!$records)
            return 0.0;

        // 現在時を取得。
        $now = time();

        // 一つずつ見て、[秒数 x value] を計算していく。
        $result = 0.0;
        foreach($records as $effect) {

            // 期限切れ日時のタイムスタンプを取得。
            $expire = strtotime($effect['expire']);

            // 期限切れがsinceよりも後なら戻り値に加える。
            if($since < $expire)
                $result += (float)(min($expire, $now) - $since) * (float)$effect['value'];
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたキャラクターに設定されている特定の効果の残り時間を返す。
     *
     * @param int       キャラクターID
     * @return array    次のキーを持つ連想配列の配列。
     *                      type            character_effect.typeの値
     *                      effect_name     効果の名前
     *                      expire          期限日時
     *                      seconds         残り秒数
     */
    public function getEffectExpires($characterId) {

        $result = array();

        // 現在有効な効果レコードをすべて取得。
        $effects = $this->getEffects($characterId);

        // 一つずつ見ていく。
        foreach($effects as $effect) {

            // 初めて登場する効果の場合、戻り値に要素追加。
            if( !$result[ $effect['type'] ] ) {
                $result[ $effect['type'] ] = array(
                    'type' => $effect['type'],
                    'effect_name' => $this->getEffectName($effect['type']),
                    'expire' => $effect['expire'],
                    'value' => $this->getEffectValue($characterId, $effect['type']),
                );
            }

            // この効果に関する情報の格納先を取得。
            $summary = &$result[ $effect['type'] ];

            // 期限がより長いなら期限を更新する。
            if($summary['expire'] < $effect['expire'])
                $summary['expire'] = $effect['expire'];

            // 期限から秒数を計算する。
            $summary['seconds'] = strtotime($summary['expire']) - time();
        }

        // リターン
        return $result;
    }


    // private メンバ
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された効果レコードを返すとともに、期限切れになっているレコードを削除する。
     *
     * @param int       キャラクターID
     * @param mixed     取得対象のtypeの値。配列で複数指定も可。すべて取るなら省略可。
     * @param bool      期限切れのレコードも返してほしいかどうか。
     * @return array    取得した効果レコードの配列。
     */
    private function getEffects($characterId, $type = false, $includeExpired = false) {

        $conditions = array();
        $conditions['character_id'] = $characterId;

        if($type)
            $conditions['type'] = $type;

        // 対象のレコードを取得。
        $resultset = $this->selectResultset($conditions);

        // レコードの有効期限をチェックする。
        $now = time();
        foreach($resultset as $index => $record) {

            // 期限が切れてないならスルー。
            if($now < strtotime($record['expire']))
                continue;

            // 引数で期限切れ不要が指定されているなら戻り値からも削除。
            if(!$includeExpired)
                unset($resultset[$index]);

            // ユーザ履歴に効果期限切れを追加。
            $histSvc = new History_LogService();
            $histSvc->insertRecord(array(
                'user_id' => Service::create('Character_Info')->needUserId($characterId),
                'type' => History_LogService::TYPE_EFFECT_TIMEUP,
                'ref1_value' => $record['character_id'],
                'ref2_value' => $record['type'],
                'create_at' => $record['expire'],
            ));

            // レコードを削除。
            $this->deleteRecord($record['effect_id']);
        }

        return $resultset;
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'effect_id';
}
