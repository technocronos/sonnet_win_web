<?php

class Character_InfoService extends Service {

    // 初期パラメータ。
    const INITIAL_HP = 120.0;
    const INITIAL_ATTACK = 30;
    const INITIAL_DEFENCE = 30;
    const INITIAL_SPEED = 30;
    const INITIAL_FACE = 16004;

    // 振り分けpt 1 でHP-MAXがいくつ上昇するか
    const HP_SCALE = 7;

    // アバターの表情の一覧。
    public static $AVATAR_FACES = array(
        16001 => '',
        16002 => '',
        16003 => '',
        16004 => '',
    );


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザのアバターキャラクターのIDを返す。
     *
     * @param int   ユーザID。
     * @return int  アバターキャラクターのID。ない場合はfalse。
     */
    public function getAvatarId($userId) {

        // まだ取得していない場合は取得。
        if(!array_key_exists($userId, self::$avatarIdCache)) {

            // DBに問い合わせて取得する。
            $sql = "
                SELECT character_id
                FROM character_info
                WHERE user_id = ?
                  AND entry = 'AVT'
            ";

            self::$avatarIdCache[$userId] = $this->createDao(true)->getOne($sql, $userId);
        }

        return self::$avatarIdCache[$userId];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getAvatarId と同じだが、レコードがなかった場合に、例外を投げてエラーとする。
     */
    public function needAvatarId($userId) {

        $charaId = $this->getAvatarId($userId);

        if(!$charaId) {
            throw new MojaviException(sprintf('アバターキャラクターレコードがありません。ユーザID:%s', $userId));
        }

        return $charaId;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザのアバターキャラクターを返す。
     *
     * @param int       ユーザID。
     * @param bool      getRecordExと同じように擬似列を加えるかどうか。
     * @return array    現在のキャラクターを表す連想配列。
     */
    public function getAvatar($userId, $extend = false) {

        $charaId = $this->getAvatarId($userId);
        if($charaId === false)
            return null;

        return $extend ? $this->getExRecord($charaId) : $this->getRecord($charaId);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getAvatar() と同じだが、レコードがなかった場合に、例外を投げてエラーとする。
     */
    public function needAvatar($userId, $extend = false) {

        $chara = $this->getAvatar($userId, $extend);

        if(!$chara)
            throw new MojaviException(sprintf('アバターキャラクターレコードがありません。ユーザID:%s', $userId));

        return $chara;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたキャラクターの持ち主になっているユーザのIDを返す。
     */
    public function needUserId($characterId) {

        static $cache = array();

        // まだ取得してないなら取得。
        if(!array_key_exists($characterId, $cache)) {
            $sql = '
                SELECT user_id
                FROM character_info
                WHERE character_id = ?
            ';
            $cache[$characterId] = $this->createDao(true)->getOne($sql, $characterId);
        }

        // リターン。
        return $cache[$characterId];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたキャラクターの持ち主になっているユーザのレコードを返す。
     */
    public function needUser($characterId) {

        $svc = new User_InfoService();
        return $svc->needRecord( $this->needUserId($characterId) );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたユーザがオーナーになっているキャラクターのIDをすべて返す。
     */
    public function getCharaIds($userId) {

        $sql = '
            SELECT character_id
            FROM character_info
            WHERE user_id = ?
        ';

        return $this->createDao(true)->getCol($sql, $userId);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたキャラクターの経験値に関する情報を返す。
     *
     * @param array     キャラクターレコード。
     * @return array    次のキーを持つ連想配列。
     *                      absolute_next   蓄積の経験値がいくつになったら次のレベルになるか。
     *                                      次のレベルがない場合は0。
     *                      relative_exp    現在のレベルにおける経験値
     *                                      (蓄積ではない値。レベルアップしたら0にリセット)
     *                      relative_next   relative_expがいくつになったら次のレベルになるか。
     *                                      次のレベルがない場合は0。
     */
    public function getExpInfo($record) {

        $levelSvc = new Level_MasterService();

        // 現在のレベルと次のレベルのレコードを取得。
        $current = $levelSvc->needRecord($record['race'], $record['level']);
        $next = $levelSvc->getRecord($record['race'], $record['level'] + 1);

        // リターン。
        return array(
            'absolute_next' => $next ? $next['exp'] : 0,
            'relative_exp' => $record['exp'] - $current['exp'],
            'relative_next' => $next ? $next['exp'] - $current['exp'] : 0,
        );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 対戦相手となりそうな character_info レコードを数件抽出する。
     *
     * @param array     抽出の基準となるキャラクター情報。
     * @param int       抽出件数。最大でこの件数抽出するが、この数を下回る場合もある(0 もあり)ことに注意
     * @return array    抽出した対戦相手の情報。
     *                  "character_id" 列と "user_id" 列を持つ結果セットになっている。
     */
    public function getRivals($chara, $findCount) {

        $dao = $this->createDao(true);

        // 戻り値初期化。
        $result = array();

        // 基準キャラの上下2段の階級を取得。
        $gradeRange = Service::create('Grade_Master')->getRangeBorder($chara['grade_id'], 2);
        $lowerGrade = $gradeRange['lower'];  $upperGrade = $gradeRange['upper'];

        // 候補対象の経験値範囲を取得。
        $sql = '
            SELECT MIN(exp) AS min
                 , MAX(exp) AS max
            FROM character_info
            WHERE grade_id BETWEEN ? AND ?
              AND user_id > 0
        ';
        $minmax = $dao->getRow($sql, array($lowerGrade, $upperGrade));

        // 誰もいないならカラ配列をリターン。
        if( is_null($minmax['min']) )
            return $result;

        // 経験値範囲を抽出件数の数のバンドで区切るとして、バンドの幅を求める。
        // ただし、最小でも1とする。
        $step = (int)( ($minmax['max'] - $minmax['min']) / $findCount );
        if($step < 1) $step = 1;

        // 最下位バンドの経験値範囲をランダムに取得。
        // ただし、それが自分の経験値よりも高い場合はランダムを使用しない。
        $band = $minmax['min'] + mt_rand(0, $step - 1);
        if($chara['exp'] < $band)
            $band = $minmax['min'];

        // 各バンドから一人ずつ抽出。
        for($i = 0 ; $i < $findCount ; $i++) {

            $sql = '
                SELECT character_id
                     , user_id
                FROM character_info
                WHERE grade_id BETWEEN ? AND ?
                  AND exp BETWEEN ? AND ?
                  AND exp > 0
                  AND user_id > 0
                LIMIT 1
            ';

            // 取得できたなら結果セットに追加。
            $record = $dao->getRow($sql, array($lowerGrade, $upperGrade, $band, $band + $step - 1));
            if($record)
                $result[] = $record;

            // 次のバンドへ。
            $band += $step;
        }

        // シャッフルしてリターン。
        shuffle($result);
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された範囲の経験値で、経験値段階別、階級別にキャラクターの数を集計する。
     *
     * @param int       経験値範囲下限
     * @param int       経験値範囲上限
     * @return array    次のキーを持つ結果セット。
     *                      grade_id        階級ID
     *                      step            経験値/1000(切り捨て) の値
     *                      count           その段階＆階級のキャラクター数
     */
    public function getDistribution($expFrom, $expTo) {

        // SQL作成＆実行
        $sql = "
            SELECT grade_id
                 , exp DIV 1000 AS step
                 , COUNT(*) AS count
            FROM character_info
            WHERE user_id > 0
              AND exp >= ?
              AND exp < ?
            GROUP BY grade_id, step
        ";

        // SQLを実行してリターン。
        return $this->createDao(true)->getAll($sql, array($expFrom, $expTo));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 経験値取得処理を行う。
     *
     * @param int       キャラクターID
     * @param int       取得した経験値
     * @return array    処理結果の情報を表す配列。以下のキーがある。
     *                      before      取得前のキャラクタ情報
     *                      after       取得後のキャラクタ情報
     */
    public function gainExp($charaId, $exp) {

        $levelSvc = new Level_MasterService();

        // 現在のキャラ情報を取得。
        $chara = $this->needRecord($charaId);

        // 念のため、システムユーザには経験値が入らないようにしておく。
        if($chara['user_id'] < 0)
            $exp = 0;

        // すでに最高レベルの場合は経験値を0にする。
        if($levelSvc->getMaxLevel($chara['race']) <= $chara['level'])
            $exp = 0;

        // この時点で経験値0なら後の処理は不要。
        if($exp == 0)
            return array('before'=>$chara, 'after'=>$chara);

        // 低レベル時のレベルアップのペース調整。
        switch($chara['level']) {
            case 1:     $exp *= 4;      break;
            case 2:     $exp = (int)($exp * 3.5);      break;
            case 3:     $exp *= 3;      break;
            case 4:     $exp *= 2;      break;
        }

        // ユーザに経験値アップの効果が付いているなら、反映しておく。
        $effectSvc = new Character_EffectService();
        $expEffect = $effectSvc->getEffectValue($charaId, Character_EffectService::TYPE_EXP_INCREASE);
        $exp += (int)ceil($exp * $expEffect/100);

        // 経験値取得によるレベルアップ情報を取得。
        $growth = $levelSvc->getGrowth($chara['race'], $chara['exp'], $exp);

        // キャラ情報に、経験値取得・能力アップを反映。
        $this->plusValue($charaId, array(
            'exp' => $exp,
            'param_seed' => $growth['param_growth'],
            'attack1' => $growth['attack1_growth'],
            'attack2' => $growth['attack2_growth'],
            'attack3' => $growth['attack3_growth'],
            'defence1' => $growth['defence1_growth'],
            'defence2' => $growth['defence2_growth'],
            'defence3' => $growth['defence3_growth'],
            'speed' => $growth['speed_growth'],
            'hp_max' => $growth['hp_growth'] * self::HP_SCALE,
        ));

        // 反映後の情報を $afterChara に取得。
        $afterChara = $this->needRecord($charaId);

        // レベルが上がった場合。
        if($growth['level_growth'] > 0) {

            // 履歴を作成。
            $histSvc = new History_LogService();
            $histSvc->insertRecord(array(
                'type' => History_LogService::TYPE_LEVEL_UP,
                'user_id' => $chara['user_id'],
                'ref1_value' => $afterChara['character_id'],
                'ref2_value' => $afterChara['level'],
            ));

            // 記念すべきレベル数のときのみアクティビティ送信
            for($i = $chara['level'] + 1 ; $i <= $afterChara['level'] ; $i++) {
                if($i == 10  ||  $i == 50  ||  $i%100 == 0) {
                    $trans = array('[:name:]'=>Text_LogService::get($chara['name_id']), '[:level:]'=>$i);
                    PlatformApi::postActivity(strtr(ACTIVITY_MEMORIAL_LEVEL, $trans));
                }
            }
        }

        // リターン。
        return array('before'=>$chara, 'after'=>$afterChara);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 階級ptの増減処理を行う。
     *
     * @param int       キャラクターID
     * @param reference 増減する階級pt。減るならマイナス。
     *                  ここに指定した変数に、実際に増減された値がセットされる。
     * @return array    処理結果の情報を表す配列。以下のキーがある。
     *                      before      取得前のキャラクタ情報
     *                      after       取得後のキャラクタ情報
     */
    public function gainGradePt($charaId, &$gain) {

        $gradeSvc = new Grade_MasterService();

        // 現在のキャラ情報を取得。
        $chara = $this->needRecord($charaId);

        // 本当に付与できるのかチェック。
        $gain = $gradeSvc->validateGain($chara, $gain);

        // この時点でpt0なら後の処理は不要。
        if($gain == 0)
            return array('before'=>$chara, 'after'=>$chara);

        // 増減後の階級ptを求めて、昇格／降格判定を行う。
        $gradePt = $chara['grade_pt'] + $gain;
        $newGrade = $gradeSvc->judgeGrade($chara['grade_id'], $gradePt);

        // 昇格／降格が発生しているなら、階級ptがリセットされるようにする。
        if($newGrade != $chara['grade_id'])  $gradePt = 0;

        // レコード更新。
        $this->updateRecord($charaId, array(
            'grade_id' => $newGrade,
            'grade_pt' => $gradePt,
        ));

        // 反映後の情報を $afterChara に取得。
        $afterChara = $this->needRecord($charaId);

        // 昇格／降格が発生している場合...
        if($newGrade != $chara['grade_id']) {

            // 履歴作成。
            Service::create('History_Log')->insertRecord(array(
                'user_id' => $chara['user_id'],
                'type' => History_LogService::TYPE_CHANGE_GRADE,
                'ref1_value' => $charaId,
                'ref2_value' => $newGrade * ($newGrade > $chara['grade_id'] ? +1 : -1),
            ));

            // アクセス中のユーザのキャラである場合はアクティビティ送信
            if($_REQUEST["opensocial_owner_id"] == $chara['user_id']) {
                $activity = ($newGrade > $chara['grade_id']) ? ACTIVITY_GRADE_UP : ACTIVITY_GRADE_DOWN;
                PlatformApi::postActivity(strtr( $activity, array('[:grade:]'=>$gradeSvc->name($newGrade)) ));
            }
        }

        // リターン。
        return array('before'=>$chara, 'after'=>$afterChara);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたキャラのHPを減算する。
     *
     * @param int   キャラクターID
     * @param int   HPの減少量。あるいは、HP0になったことを意味する値:false。
     *              マイナスの値を指定して回復させることもできる。
     */
    public function damageHp($characterId, $damage) {

        // HP:0 になったのなら、HPを最大回復＆death_countをプラス1。
        if($damage === false) {
            $this->updateRecord($characterId, array(
                'hp' => array('sql'=>'hp_max'),
                'death_count' => array('sql'=>'death_count + 1'),
            ));

        // HP:0 になってないのならダメージ分を減算。ただし、0以下にはならないようにする。
        }else if($damage > 0) {
            $this->updateRecord($characterId, array(
                'hp' => array('sql'=>'GREATEST(1.0, hp - ?)', 'value'=>$damage),
            ));

        // HPが回復している場合はそれを処理する。
        }else if($damage < 0) {
            $this->plusValue($characterId, array('hp'=>-1 * $damage));
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたキャラに指定されたスフィアへの出撃マークをつける。
     *
     * @param int   キャラクターID
     * @param int   出撃先のスフィアID、あるいは、スフィアからの帰還を意味するnull。
     */
    public function sallyTo($characterId, $sphereId) {

        $this->updateRecord($characterId, array(
            'sally_sphere' => $sphereId,
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたスフィアへ出撃していたキャラの出撃マークをクリアする。
     *
     * @param int   スフィアID。
     */
    public function endSphere($sphereId) {

        $this->createDao()->update(
            array('sally_sphere' => $sphereId),
            array('sally_sphere' => null)
        );
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたキャラの名前を変更する。
     *
     * @param int       キャラクタID
     * @param string    変更後の名前
     */
    public function updateName($charaId, $newName) {

        $chara = $this->needRecord($charaId);

        Service::create('Text_Log')->updateText($chara['name_id'], $newName);
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'character_id';

    // getAvatarId() のキャッシュ。
    protected static $avatarIdCache = array();


    //-----------------------------------------------------------------------------------------------------
    /**
     * exColumnをオーバーライド。以下の拡張列を追加する。
     *     equip            装備内容。Character_EquipmentService::getEquipments の戻り値。
     *     equip_attackN    Nは整数。装備の合計攻撃力
     *     equip_defenceN   Nは整数。装備の合計防御力
     *     equip_speed      装備の合計スピード
     *     equip_spatt      装備の合計特功
     *     equip_spdef      装備の合計特防
     *     total_attackN    Nは整数。素＋装備の攻撃力
     *     total_defenceN   Nは整数。素＋装備の防御力
     *     total_speed      素＋装備のスピード
     *     total_defenceX   合計特防
     */
    protected function exColumn(&$record) {

        // 装備を取得。
        $record['equip'] = Service::create('Character_Equipment')->getEquipments($record['character_id']);

        // 装備の合計ステータスを取得。
        $record['equip_attack1'] =  0;  $record['equip_attack2'] =  0;  $record['equip_attack3'] =  0;
        $record['equip_defence1'] = 0;  $record['equip_defence2'] = 0;  $record['equip_defence3'] = 0;
        $record['equip_speed'] =    0;  $record['total_defenceX'] = 0;
        foreach($record['equip'] as $equip) {
            $record['equip_attack1'] +=  $equip['attack1'];
            $record['equip_attack2'] +=  $equip['attack2'];
            $record['equip_attack3'] +=  $equip['attack3'];
            $record['equip_defence1'] += $equip['defence1'];
            $record['equip_defence2'] += $equip['defence2'];
            $record['equip_defence3'] += $equip['defence3'];
            $record['equip_speed'] +=    $equip['speed'];
            $record['equip_defenceX'] += $equip['defenceX'];
        }

        // ステータス合計を計算。
        $record['total_attack1'] =  $record['attack1'] +  $record['equip_attack1'];
        $record['total_attack2'] =  $record['attack2'] +  $record['equip_attack2'];
        $record['total_attack3'] =  $record['attack3'] +  $record['equip_attack3'];
        $record['total_defence1'] = $record['defence1'] + $record['equip_defence1'];
        $record['total_defence2'] = $record['defence2'] + $record['equip_defence2'];
        $record['total_defence3'] = $record['defence3'] + $record['equip_defence3'];
        $record['total_speed'] =    $record['speed'] +    $record['equip_speed'];
        $record['total_defenceX'] = $record['defenceX'] + $record['equip_defenceX'];
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * getSelectPhraseをオーバーライド。
     * 階級名とレベルを一緒に取得する。
     */
    protected function getSelectPhrase() {

        return '
           SELECT character_info.*
                , (
                      SELECT level
                      FROM level_master
                      WHERE level_master.race = character_info.race
                        AND level_master.exp <= character_info.exp
                      ORDER BY level_master.exp DESC
                      LIMIT 1
                  ) AS level
            FROM character_info
        ';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * processRecord をオーバーライド。
     * 時間によるHP回復を処理する。
     */
    protected function processRecord(&$record) {
        $this->processRecovery($record);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * insertRecordをオーバーライド。
     * 以下の擬似列を受け取ることができる。
     *     name     キャラクター名。
     */
    public function insertRecord($values, $returnAutoNumber = false) {

        // ユーザIDは必須。
        if(!$values['user_id'])
            throw new MojaviException('ユーザIDが必要です。');

        // すでにいるのに entry='AVT' のキャラを作成しようとしているならエラー。
        if( $values['entry'] == 'AVT'  &&  $this->getAvatarId($values['user_id']) )
            throw new MojaviException('アバターキャラを二つ作成しようとした');

        // 各列のデフォルト値を補う。
        $values += array(
            'grade_id' => Grade_MasterService::INITIAL_GRADE,
            'graphic_id' => self::INITIAL_FACE,
            'hp' => self::INITIAL_HP,
            'hp_max' => self::INITIAL_HP,
            'attack1' => self::INITIAL_ATTACK,
            'attack2' => self::INITIAL_ATTACK,
            'attack3' => self::INITIAL_ATTACK,
            'defence1' => self::INITIAL_DEFENCE,
            'defence2' => self::INITIAL_DEFENCE,
            'defence3' => self::INITIAL_DEFENCE,
            'speed' => self::INITIAL_SPEED,
            'last_affected' => array('sql'=>'NOW()'),
        );

        // name擬似列を処理する。
        if($values['name']) {
            $textSvc = new Text_LogService();
            $values['name_id'] = $textSvc->postText('CHR', $values['name'], $values['user_id']);
            unset($values['name']);
        }

        // 引数で指定された値を適用してINSERT。
        return parent::insertRecord($values, $returnAutoNumber);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * plusValueをオーバーライド。
     * hp列を増減するときに、おかしな値にならないようにする。
     */
    public function plusValue($primaryKey, $plusValues) {

        // hp列が増減されようとしているとき。
        if(!empty($plusValues['hp'])) {

            // 0未満や、hp_max超過 にならないようにする。
            $plusValues['hp'] = array(
                'sql' => 'LEAST(GREATEST(hp + ?, 0.0), hp_max)',
                'value' => $plusValues['hp'],
            );
        }

        // 親メソッドでUPDATE作業。
        parent::plusValue($primaryKey, $plusValues);

        // もしhp_maxが減らされているのなら、hpもあわせる。
        if(isset($plusValues['hp_max'])  &&  $plusValues['hp_max'] < 0) {
            $this->updateRecord($primaryKey, array(
                'hp' => array('sql'=>'LEAST(hp, hp_max)'),
            ));
        }
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * deleteRecordをオーバーライド。
     * キャッシュをクリアするようにする。
     */
    public function deleteRecord(/* 可変引数 */) {

        self::$avatarIdCache = array();

        return parent::deleteRecord(...$args);
    }


    // private メソッド
    //=====================================================================================================

    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定されたCharacter_Infoレコードに対して、時間によるHP回復を処理を行う。
     */
    private function processRecovery(&$record) {

        // システムキャラの場合は無視。
        if($record['character_id'] < 0)
            return;

        // 現在時を取得。前回処理時から5分も経過していないなら行わない。
        $now = time();
        $lastAffected = strtotime($record['last_affected']);
        if($now < $lastAffected + 5*60)
            return;

        // 1秒で何pt回復するのかを取得。
        $recovery = $record['hp_max'] * HP_RECOVERY;

        // 時間によるポイント回復分を計算。
        $hpRecv = ($now - $lastAffected) * $recovery;

        // アイテム効果による回復分を追加。
        $effectValue = Service::create('Character_Effect')->getTimeValue(
            $record['character_id'], Character_EffectService::TYPE_HP_RECOVER, $lastAffected
        );
        $hpRecv += $effectValue/100 * $recovery;

        // レコードを更新。
        $record['hp'] = min($record['hp_max'], $record['hp'] + $hpRecv);
        $record['last_affected'] = date('Y/m/d H:i:s', $now);
        $this->updateRecord($record['character_id'], array(
            'hp' => $record['hp'],
            'last_affected' => $record['last_affected'],
        ));
    }
}