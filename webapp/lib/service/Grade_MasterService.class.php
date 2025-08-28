<?php

class Grade_MasterService extends Service {

    // 最初の階級
    const INITIAL_GRADE = 10;

    // 最上位階級のときの持ち点最大値
    const GRADE_PT_MAX = 100;

    // 階級ごとのキャラ数分布をキャッシュしている時間。
    const DISTRIBUTION_CACHE_HOURS = 1;


    //-----------------------------------------------------------------------------------------------------
    /**
     * 階級IDを受け取って、階級名を返す。
     *
     * @param int       階級ID
     * @return string   階級名
     */
    public static function name($gradeId) {

        $svc = new self();
        $record = $svc->getRecord($gradeId);
        return $record ? $record['grade_name'] : '';
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 階級レコードの一覧を取得する。
     *
     * @param string    昇順なら"ASC"、降順なら"DESC"。
     * @param int       取得する上限の階級ID。
     * @param int       取得する下限の階級ID。
     * @return array    取得した階級レコードの配列。
     */
    public function getList($direction = 'DESC', $upper = 0x7FFFFFFF, $lower = 0) {

        return $this->selectResultset(array(
            'grade_id' => array('sql'=>'BETWEEN ? AND ?', 'value'=>array($lower, $upper)),
            'ORDER BY' => "grade_master.grade_id {$direction}",
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 存在するキャラクターの中で最高の階級のIDを取得する。
     */
    public function getHighestGrade() {

        return $this->createDao(true)->getOne("
            SELECT max(grade_id)
            FROM character_info
            WHERE user_id > 0
        ");
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 設定されている最高階級を返す。
     */
    public function getMaxGrade() {

        return $this->selectRecord(array(
            'raise_border' => null,
            'ORDER BY' => 'grade_id',
        ));
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 各階級にキャラクターが何人いるかを取得する。
     *
     * @return array    キーにgrade_id、値にその階級に何人いるかを持つ配列。
     */
    public function getDistribution() {

        $cacheSvc = new Cache_InfoService();

        // 恒久キャッシュから取得する。
        $result = $cacheSvc->getGroup(Cache_InfoService::GRADE);

        // ない、あるいはキャッシュ有効期限を過ぎている場合は...
        if( !$result  ||  $result[-1] + self::DISTRIBUTION_CACHE_HOURS*60*60 < time() ) {

            // 集計SQLを作成。
            $sql = '
                SELECT grade_id
                     , COUNT(*) AS count
                FROM character_info
                WHERE user_id > 0
                GROUP BY grade_id
            ';

            // 集計。階級IDをインデックス、その人数を値とする配列を作成。
            $resultset = $this->createDao(true)->getAll($sql);
            $result = ResultsetUtil::colValues($resultset, 'count', 'grade_id');

            // -1に今のタイムスタンプを入れてキャッシュに保存。
            $result[-1] = time();
            $cacheSvc->clearGroup(Cache_InfoService::GRADE);
            $cacheSvc->insertGroup(Cache_InfoService::GRADE, $result);
            unset($result[-1]);
        }

        // リターン。
        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された階級のユーザ一覧を取得する。
     *
     * @param int       階級ID
     * @param int       1ページあたりの件数。
     * @param int       何ページ目か。0スタート。
     * @return array    Service::selectPage と同様。
     */
    public function getCharacterList($gradeId, $showOnPage, $page) {

        // 一覧を取得。ただし、character_id のみ。
        $sql = "
            SELECT character_id
            FROM character_info
            WHERE grade_id = ?
              AND user_id > 0
            ORDER BY exp DESC
        ";
        $list = $this->createDao(true)->getPage($sql, $gradeId, $showOnPage, $page);

        // character_idからキャラレコードを取得。
        $ids = ResultsetUtil::colValues($list['resultset'], 'character_id');
        $charas = self::create('Character_Info')->getRecordsIn($ids, false);
        Service::create('Character_Info')->addExColumn($charas, true);

        // 順番を再度整えてリターン。
        $list['resultset'] = ResultsetUtil::sort($charas, array('exp'=>'DESC'));
        return $list;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 引数で指定された階級の昇格／降格判定を行う。
     *
     * @param int       現在の階級ID
     * @param int       現在の持ち点
     * @return int      昇格／降格後の階級ID。昇格／降格しないなら、第一引数と同じ値が返る。
     */
    public function judgeGrade($currentGrade, $point) {

        // 現在の階級の情報を取得。
        $grade = $this->needRecord($currentGrade);

        // 変数 $slide に 昇格／降格／そのまま を表す値を入れる。
        if(!is_null($grade['abase_border'])  &&  $point <= $grade['abase_border']) {
            $slide = -1;
        }else if(!is_null($grade['raise_border'])  &&  $point >= $grade['raise_border']) {
            $slide = +1;
        }else {
            $slide = 0;
        }

        // 昇格か降格なら、その先の階級情報を取得。
        if($slide)
            $neighbor = $this->getNeighbor($currentGrade, $slide);

        // リターン。昇格／降格か、そのままかで分岐する。
        return $slide ? $neighbor['grade_id'] : $currentGrade;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された階級から見て、指定段上／下の階級のレコードを返す。
     *
     * @param int       基準になる階級のID
     * @param int       何段上／下の階級がほしいか。下の場合はマイナスで指定する。
     * @return array    指定された階級のレコード。なかった場合はNULL
     */
    public function getNeighbor($baseId, $direction) {

        $where = array();

        if($direction > 0) {
            $where['grade_id'] = array('sql'=>'> ?', 'value'=>$baseId);
            $where['ORDER BY'] = 'grade_id ASC';
        }else {
            $where['grade_id'] = array('sql'=>'< ?', 'value'=>$baseId);
            $where['ORDER BY'] = 'grade_id DESC';
        }

        $where['OFFSET'] = abs($direction) - 1;

        return $this->selectRecord($where);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された階級を中心に、上下N段の範囲の上限と下限を返す。
     *
     * 例) 階級ID が下から 10, 20, 30, 40, 50, 60, 70 とある場合に...
     *         getRangeBorder(40, 2);     // array('lower'=>20, 'upper'=>60) が返る。
     *         getRangeBorder(60, 2);     // array('lower'=>40, 'upper'=>70) が返る。
     *         getRangeBorder(10, 2);     // array('lower'=>10, 'upper'=>30) が返る。
     *
     * @param int       基準になる階級のID
     * @param int       上下何段の範囲とするか。
     * @return array    キー"lower"に下限のID、キー"upper"に上限のIDを格納する配列。
     */
    public function getRangeBorder($baseId, $range) {

        // SQLにそのまま埋め込むので、一応正規化。
        $range = (int)$range;

        $sql = "
            SELECT (
                       SELECT MIN(grade_id)
                       FROM (
                                SELECT grade_id
                                FROM grade_master
                                WHERE grade_id < ?
                                ORDER BY grade_id DESC
                                LIMIT {$range}
                            ) AS table1
                   ) AS lower
                 , (
                       SELECT MAX(grade_id)
                       FROM (
                                SELECT grade_id
                                FROM grade_master
                                WHERE grade_id > ?
                                ORDER BY grade_id ASC
                                LIMIT {$range}
                            ) AS table2
                   ) AS upper
        ";

        $result = $this->createDao(true)->getRow($sql, array($baseId, $baseId));

        if( is_null($result['lower']) )     $result['lower'] = $baseId;
        if( is_null($result['upper']) )     $result['upper'] = $baseId;

        return $result;
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された二つの階級を比べて、その段階差を返す。
     * 存在しない階級を指定した場合の動作は未定義。
     *
     * 例) 階級ID が下から 10, 20, 30, 40, 50, 60, 70 とある場合に...
     *         getRangeBorder(40, 20);     // 2 が返る。
     *         getRangeBorder(40, 60);     // -2 が返る。
     *         getRangeBorder(40, 40);     // 0 が返る。
     *
     * @param int       基準になる階級のID
     * @param int       比較対象の階級のID
     * @return int      例を参照。
     */
    public function gradeCmp($baseId, $targetId) {

        // 同じなら 0 をリターン。
        if($baseId == $targetId)
            return 0;

        // 指定された範囲に含まれる階級レコードの数をカウント。
        $sql = '
            SELECT COUNT(*)
            FROM grade_master
            WHERE grade_id BETWEEN ? AND ?
        ';

        // 指定された階級は必ず存在すると仮定して、レコード数から1を引いたものが差になる。
        $count = $this->createDao(true)->getOne($sql, array(min($baseId, $targetId), max($baseId, $targetId)));
        return ($count - 1) * ($baseId > $targetId ? +1 : -1);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定された階級が下から何番目の階級かを返す。
     *
     * @param int       階級のID
     * @return int      下から何番目の階級か
     */
    public function getGradeOrder($gradeId) {

        // 指定された範囲に含まれる階級レコードの数をカウントして返す。
        $sql = '
            SELECT COUNT(*)
            FROM grade_master
            WHERE grade_id <= ?
        ';

        return $this->createDao()->getOne($sql, $gradeId);
    }


    //-----------------------------------------------------------------------------------------------------
    /**
     * 指定されたキャラクターに対して、指定された階級ptを増減しようとしているとき、
     * 付与可能な範囲に補正した値を返す。
     *
     * @param array     character_infoレコード。
     * @param int       増減しようとしているpt。減らすならマイナス。
     * @return int      補正されたpt。
     */
    public function validateGain($character, $gain) {

        // 0なら調べる必要はない。
        if($gain == 0)
            return 0;

        // 現在の階級の情報を取得。
        $grade = $this->needRecord($character['grade_id']);

        // 降格ボーダーがない階級なら、マイナスにはならない。
        if(is_null($grade['abase_border'])  &&  $gain < 0)
            return 0;

        // 昇格ボーダーがない階級では、階級ptが一定値以上にならないようにする。
        if(is_null($grade['raise_border'])  &&  $character['grade_pt'] + $gain > self::GRADE_PT_MAX)
            return self::GRADE_PT_MAX - $character['grade_pt'];

        // ここまで来たら補正の必要はない。
        return $gain;
    }

    //-----------------------------------------------------------------------------------------------------
    /**
     * 翻訳をする
     */
    public function getTransText($record){
        $columns = ["grade_name"];

        foreach($columns as $column){
            $data = AppUtil::getText("grade_master_" . $column . "_" . $record[$this->primaryKey]);

            if($data == "")
                $data = $record[$column];

            $record[$column] = $data;
        }

        return $record;
    }

    // 基底メンバの上書き。
    //=====================================================================================================

    protected $primaryKey = 'grade_id';

    protected $isMaster = true;

    /**
     * getRecordをオーバーライドして多言語対応
     */
    public function getRecord(/* 可変引数 */) {
        $args = func_get_args();
        $record = parent::getRecord($args[0]);

        $record = $this->getTransText($record);

        return $record;
    }

    /**
     * selectResultsetをオーバーライドして多言語対応
     */
    public function selectResultset($where) {
        $record = parent::selectResultset($where);

        foreach($record as &$row){
            $row = $this->getTransText($row);
        }

        return $record;
    }

}
