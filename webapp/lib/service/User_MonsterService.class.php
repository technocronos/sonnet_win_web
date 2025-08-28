<?php

class User_MonsterService extends Service {

    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたユーザが倒したモンスターの一覧をページ分けして取得する。
     *
     * @param int       ユーザID
     * @param int       1ページあたりの件数。
     * @param int       何ページ目か。0スタート。
     * @return array    Service::selectPage と同様だが、結果セットのレコードは Monster_MasterService の
     *                  ものになっている。
     */
    public function getTerminateList($userId, $numOnPage, $page) {

        // 指定のユーザが倒したモンスターを倒した日昇順で取得する。
        $condition = array(
            'user_id' => $userId,
            'ORDER BY' => 'terminate_at',
        );

        $list = $this->selectPage($condition, $numOnPage, $page);

        // 結果セットに含まれている character_id を配列で取得。Monster_MasterServiceからすべて取得する。
        $ids = ResultsetUtil::colValues($list['resultset'], 'character_id');
        $monsters = Service::create('Monster_Master')->getRecordsIn($ids);

        // 結果セットのレコードをMonster_MasterServiceのものに置き換える。ただし、terminate_at は残しておく。
        foreach($list['resultset'] as &$record)
            $record += $monsters[ $record['character_id'] ];

        // リターン。
        return $list;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * Monster_MasterService::getMonsterList() と同じだが、指定されたユーザがそのモンスターを
     * いつ倒したかを表す列 "terminate_at" を追加する。倒していない場合、この列はnullになる。
     *
     * @param int       以下のキーを含む配列。
     *                      field       分類のフィールド名。"category", "rare_level", "appearance" のいずれかで指定する。
     *                      value       指定したフィールドの値。
     *                      user_id     ユーザID
     * @param int       1ページあたりの件数。
     * @param int       何ページ目か。0スタート。
     * @return array    Service::selectPage と同様だが、結果セットのレコードは Monster_MasterService の
     *                  ものになっている。
     */
    public function getCollectionList($condition, $numOnPage, $page) {

        // 指定されたフィールド名を Monster_MasterService に渡せる値に変換。
        $colNames = array('category'=>'category', 'rare_level'=>'rare_level', 'appearance'=>'appearance_area');
        $filterCol = $colNames[ $condition['field'] ];
        if(!$filterCol)
            throw new MojaviException('条件列の値が不正です');

        // 指定された分類のモンスターを Monster_MasterService に問い合わせる。
        $section = Service::create('Monster_Master')->getMonsterList(
            $filterCol, $condition['value'], $numOnPage, $page
        );

        // 取得されたモンスターのcharacter_id の一覧を取得。
        $ids = ResultsetUtil::colValues($section['resultset'], 'character_id');

        // 取得されたモンスターの打倒レコードを取得。
        $terminates = $this->selectResultset(array('user_id'=>$condition['user_id'], 'character_id'=>$ids));
        $terminates = ResultsetUtil::colValues($terminates, 'terminate_at', 'character_id');

        // 取得されたモンスターレコードを一つずつ参照して、打倒レコードの "terminate_at" をコピーしていく。
        foreach($section['resultset'] as &$record)
            $record['terminate_at'] = $terminates[ $record['character_id'] ];

        // リターン。
        return $section;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定したユーザがキャプチャしているモンスターの数を返す。
     */
    public function getCaptureCount($userId) {

        return $this->countRecord(array('user_id'=>$userId));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定したユーザが指定したモンスターをキャプチャーできるときの処理を行う。
     *
     * @param int       ユーザID
     * @param int       モンスターのcharacter_id
     * @return int      キャプチャーしたならキャプチャー用character_id、してないならnull
     */
    public function captureMonster($userId, $characterId) {

        $monsSvc = new Monster_MasterService();

        // キャプチャー可能なモンスターかどうか、出来る場合に正規化後のキャラクターIDはどうなるのかを取得。
        // キャプチャー不可ならリターン。
        $characterId = $monsSvc->isCaptureable($characterId);
        if(!$characterId)
            return null;

        // 既にキャプチャーしていないか調べる。既にしているならリターン。
        if( $this->getRecord($userId, $characterId) )
            return null;

        // ここまでくればキャプチャ。

        // レコード挿入
        $this->insertRecord(array('user_id'=>$userId, 'character_id'=>$characterId));

        // レア以上なら履歴挿入
        $monster = $monsSvc->needRecord($characterId);
        if($monster['rare_level'] >= 2) {
            Service::create('History_Log')->insertRecord(array(
                'user_id' => $userId,
                'type' => History_LogService::TYPE_CAPTURE,
                'ref1_value' => $characterId,
            ));
        }

        return $characterId;
    }


    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = array('user_id', 'character_id');
}
