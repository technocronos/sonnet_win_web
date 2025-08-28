<?php

class Sphere_InfoService extends Service {

    // "result" 列の値。
    const ACTIVE = 0;
    const SUCCESS = 1;  // 成功
    const FAILURE = 2;  // 失敗
    const GIVEUP = 3;   // ギブアップ
    const ESCAPE = 4;   // 脱出


    //-----------------------------------------------------------------------------------------------------
    /**
     * needRecordと同じだが、"state" 列を取得しない。
     * state列は巨大なので、必要ない場合はこちらのほうが好ましい。
     *
     * @param int       スフィアID
     * @return array    sphere_infoレコード。ただし、state列はない。
     */
    public function needCoreRecord($sphereId) {

        $sql = '
            SELECT sphere_id, user_id, quest_id, revision, result, validation_code, update_at, result_at, create_at
            FROM sphere_info
            WHERE sphere_id = ?
        ';

        $record = $this->createDao(true)->getRow($sql, $sphereId);

        if(!$record)
            throw new MojaviException("必要なレコードが見つからない。ID({$sphereId})");

        return $record;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のキャラがスフィアに持ち出しているアイテムとその数のリストを取得する。
     *
     * @param int       キャラクタID
     * @return array    user_item_id をキー、装備個数を値とする配列。
     */
    public function getTakeOuts($charaId) {

        // キャラクタ情報を取得。
        $chara = Service::create('Character_Info')->needRecord($charaId);

        // そもそもスフィアに出ていないならカラ配列をリターン。
        if(!$chara['sally_sphere'])
            return array();

        // 戻り値初期化。
        $list = array();

        // 出撃先のスフィアを取得。
        $sphere = $this->needRecord($chara['sally_sphere']);

        // ユニットを一つずつ見ていく。
        foreach($sphere['state']['units'] as $unit) {

            // 指定のキャラを探す。
            if($unit['character_id'] != $charaId)
                continue;

            // 指定のキャラを見つけたらその所持アイテムを走査
            foreach($unit['items'] as $uitemId) {

                if(!$uitemId)
                    continue;

                // カウントしていく。
                if(array_key_exists($uitemId, $list))
                    $list[$uitemId]++;
                else
                    $list[$uitemId] = 1;
            }

            break;
        }

        // リストをリターン。
        return $list;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定のキャラが出撃しているスフィアが最近更新されたかどうかを返す。
     *
     * @param int       キャラクタID
     * @return bool     最近更新されたならtrue、されてない、あるいは出撃していないなら false。
     */
    public function isActive($charaId) {

        // 指定のキャラ情報を取得。
        $chara = Service::create('Character_Info')->needRecord($charaId);

        // 出撃していないなら false リターン。
        if(!$chara['sally_sphere'])
            return false;

        // 出撃先のスフィアを取得。
        $sphere = $this->needCoreRecord($chara['sally_sphere']);

        // 更新日時が最近かどうかを返す。
        return (time() - strtotime($sphere['update_at']) < DUEL_SPHERE_PROTECT_HOURS*60*60);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * state を更新する。
     *
     * @param int       スフィアID
     * @param array     state 列のデータ
     * @param int       リビジョン番号
     */
    public function updateState($sphereId, $state, $rev) {

        $this->updateRecord($sphereId, array(
            'revision' => $rev,
            'state' => $state,
            'update_at' => array('sql'=>'NOW()'),
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * スフィアを閉じる。
     *
     * @param int       スフィアID
     * @param int       終了コード
     */
    public function closeSphere($sphereId, $result) {

        // スフィアレコードを取得。
        $record = $this->needCoreRecord($sphereId);

        // クエスト終了処理。
        $quest = QuestCommon::factory($record['quest_id'], $record['user_id']);
        $quest->endQuest($result == self::SUCCESS, $result);

        // character_info のスフィア出撃マークをクリア。
        Service::create('Character_Info')->endSphere($sphereId);

        // レコードに終了マークを付ける。
        $this->updateRecord($sphereId, array(
            'result' => $result,
            'result_at' => array('sql'=>'NOW()'),
        ));

        // 履歴に挿入。
        Service::create('History_Log')->insertRecord(array(
            'user_id' => $record['user_id'],
            'type' => History_LogService::TYPE_QUEST_FIN2,
            'ref1_value' => $sphereId,
        ));
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'sphere_id';

    // 終了してから7日以上経過しているスフィアを削除するようにする。
    protected $deleteOlds = 7;


    //-----------------------------------------------------------------------------------------------------
    /**
     * processRecordをオーバーライド。
     * stateをデコードする。
     */
    protected function processRecord(&$record) {

        $record['state'] = json_decode($record['state'], true);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * insertRecordをオーバーライド。
     */
    public function insertRecord($values, $returnAutoNumber = false) {

        // validation_code の値を自動で決定。
        $values['validation_code'] = Common::createRandomString(32);

        // 指定されていないカラムを補う。
        $values['update_at'] = array('sql'=>'NOW()');
        
        // resultフィールドが設定されていない場合はデフォルト値を設定
        if (!isset($values['result'])) {
            $values['result'] = self::ACTIVE;
        }

        // stateをエンコードする。
        $values['state'] = json_encode($values['state']);

        // INSERT。
        return parent::insertRecord($values, $returnAutoNumber);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * updateRecordをオーバーライド。
     * stateをエンコードする。
     */
    public function updateRecord($pk, $update) {

        if(isset($update['state']))
            $update['state'] = json_encode($update['state']);

        return parent::updateRecord($pk, $update);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * deleteOldRecordsをオーバーライド。
     * 終了していないスフィアを削除しないようにする。
     */
    public function deleteOldRecords() {

        $this->createDao()->delete(array(
            'result_at' => array('sql'=>'< NOW() - INTERVAL ? DAY', 'value'=>$this->deleteOlds),
            'LIMIT' => 40,
        ));
    }
}
